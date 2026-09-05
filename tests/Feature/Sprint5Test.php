<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Shipment;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\Payment\MidtransGateway;
use App\Services\Payment\PaymentGatewayContract;
use App\Services\Payment\PaymentService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class Sprint5Test extends TestCase
{
    use DatabaseTransactions;

    protected User $customer;
    protected User $otherCustomer;
    protected Product $product;
    protected ProductVariant $variant;
    protected CartService $cartService;
    protected OrderService $orderService;
    protected PaymentService $paymentService;
    protected string $testServerKey = 'SB-Mid-server-TEST-KEY-123456';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        Config::set('services.midtrans.server_key', $this->testServerKey);
        Config::set('services.midtrans.client_key', 'SB-Mid-client-TEST-KEY-123456');

        $this->cartService = app(CartService::class);
        $this->orderService = app(OrderService::class);
        $this->paymentService = app(PaymentService::class);

        $this->customer = User::where('role', 'customer')->first() ?? User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi_sprint5@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '081234567890',
        ]);

        $this->otherCustomer = User::create([
            'name' => 'Siti Rahma',
            'email' => 'siti_sprint5@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '089876543210',
        ]);

        $this->product = Product::with('variants')->where('is_active', true)->first();
        $this->variant = $this->product->variants->first();
        $this->variant->update(['stock' => 10]);
    }

    /**
     * Helper to generate valid SHA512 signature key for Midtrans webhook tests.
     */
    protected function generateSignature(string $orderId, string $statusCode, string $grossAmount): string
    {
        return hash('sha512', $orderId . $statusCode . $grossAmount . $this->testServerKey);
    }

    /*
    |--------------------------------------------------------------------------
    | Section 1: Payment Gateway & Architecture Tests
    |--------------------------------------------------------------------------
    */

    public function test_payment_service_resolves_midtrans_gateway_contract(): void
    {
        $gateway = $this->paymentService->getGateway();
        $this->assertInstanceOf(PaymentGatewayContract::class, $gateway);
        $this->assertInstanceOf(MidtransGateway::class, $gateway);
    }

    /*
    |--------------------------------------------------------------------------
    | Section 2: Order Creation & Stock Lock Tests
    |--------------------------------------------------------------------------
    */

    public function test_place_order_creates_order_decrements_stock_and_clears_cart(): void
    {
        $initialStock = 10;
        $this->variant->update(['stock' => $initialStock]);

        $cart = $this->cartService->getCart($this->customer, 'sess_sprint5_test');
        $cart->items()->delete();
        $this->cartService->addItem($cart, $this->variant->id, 2);

        $addressData = [
            'recipient_name' => 'Budi Santoso',
            'phone' => '081234567890',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'postal_code' => '12190',
            'address_line' => 'Jl. Senopati No. 88',
            'biteship_area_id' => 'IDNP6IDNC148IDND859',
        ];

        $shippingData = [
            'courier_company' => 'jne',
            'courier_name' => 'JNE',
            'courier_service_name' => 'REG (Reguler)',
            'courier_service_code' => 'reg',
            'price' => 12000,
        ];

        $response = $this->actingAs($this->customer)->postJson(route('checkout.process'), [
            'address' => $addressData,
            'shipping' => $shippingData,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'order_number',
            'order_id',
            'snap_token',
            'success_url',
        ]);

        $orderNumber = $response->json('order_number');
        $order = Order::where('order_number', $orderNumber)->first();
        $this->assertNotNull($order);
        $this->assertEquals('pending_payment', $order->status);
        $this->assertEquals(1, $order->items()->count());

        // Assert stock decremented by 2
        $this->assertEquals($initialStock - 2, $this->variant->fresh()->stock);

        // Assert cart is cleared
        $this->assertEquals(0, $cart->fresh()->items()->count());
    }

    public function test_place_order_prevents_overselling_race_condition(): void
    {
        // Set variant stock to 1
        $this->variant->update(['stock' => 1]);

        $cart = $this->cartService->getCart($this->customer, 'sess_sprint5_race');
        $cart->items()->delete();
        // Force cart item qty to 2 (simulating race condition after another customer bought the last item)
        $cart->items()->create([
            'product_variant_id' => $this->variant->id,
            'qty' => 2,
        ]);

        $addressData = [
            'recipient_name' => 'Budi Santoso',
            'phone' => '081234567890',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'postal_code' => '12190',
            'address_line' => 'Jl. Senopati No. 88',
        ];

        $shippingData = [
            'courier_company' => 'jne',
            'courier_service_name' => 'REG',
            'price' => 12000,
        ];

        $response = $this->actingAs($this->customer)->postJson(route('checkout.process'), [
            'address' => $addressData,
            'shipping' => $shippingData,
        ]);

        $response->assertStatus(422);
        // Stock should remain 1, not negative
        $this->assertEquals(1, $this->variant->fresh()->stock);
    }

    /*
    |--------------------------------------------------------------------------
    | Section 3: Midtrans Webhook Signature Verification Tests
    |--------------------------------------------------------------------------
    */

    public function test_webhook_rejects_invalid_signature(): void
    {
        $payload = [
            'order_id' => 'AB-20260905-9999',
            'status_code' => '200',
            'gross_amount' => '1500000.00',
            'signature_key' => 'INVALID_SIGNATURE_HASH_123',
            'transaction_status' => 'settlement',
        ];

        $response = $this->postJson(route('webhooks.midtrans'), $payload);
        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Invalid signature key.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Section 4: Webhook Settlement (Payment Paid) & Idempotency Tests
    |--------------------------------------------------------------------------
    */

    public function test_webhook_settlement_marks_order_paid_and_creates_shipment(): void
    {
        $cart = $this->cartService->getCart($this->customer, 'sess_sprint5_settle');
        $cart->items()->delete();
        $this->cartService->addItem($cart, $this->variant->id, 1);

        $order = $this->orderService->createOrderFromCart(
            $cart,
            [
                'recipient_name' => 'Budi Santoso',
                'phone' => '081234567890',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'district' => 'Kebayoran Baru',
                'postal_code' => '12190',
                'address_line' => 'Jl. Sudirman No. 1',
            ],
            [
                'courier_company' => 'SiCepat',
                'courier_service_name' => 'SIUNTUNG',
                'price' => 11000,
            ],
            $this->customer
        );

        $statusCode = '200';
        $grossAmount = number_format($order->total, 2, '.', '');
        $signature = $this->generateSignature($order->order_number, $statusCode, $grossAmount);

        $payload = [
            'order_id' => $order->order_number,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_id' => 'midtrans-tx-123456',
            'transaction_status' => 'settlement',
            'payment_type' => 'bca_va',
            'settlement_time' => now()->toDateTimeString(),
        ];

        // First Webhook Call
        $response1 = $this->postJson(route('webhooks.midtrans'), $payload);
        $response1->assertStatus(200);
        $response1->assertJson(['success' => true]);

        $orderFresh = $order->fresh();
        $this->assertEquals('paid', $orderFresh->status);
        $this->assertNotNull($orderFresh->payment);
        $this->assertEquals('success', $orderFresh->payment->status);
        $this->assertEquals('midtrans-tx-123456', $orderFresh->payment->gateway_reference);

        $this->assertNotNull($orderFresh->shipment);
        $this->assertEquals('pending', $orderFresh->shipment->status);

        // Second Webhook Call (Idempotency Test - exact same webhook sent twice)
        $response2 = $this->postJson(route('webhooks.midtrans'), $payload);
        $response2->assertStatus(200);
        $response2->assertJson([
            'success' => true,
            'message' => 'Order already processed (idempotent).',
        ]);

        // Payment and Shipment records should NOT be duplicated
        $this->assertEquals(1, Payment::where('order_id', $order->id)->count());
        $this->assertEquals(1, Shipment::where('order_id', $order->id)->count());
    }

    /*
    |--------------------------------------------------------------------------
    | Section 5: Webhook Expire / Cancel (Stock Restoration) Tests
    |--------------------------------------------------------------------------
    */

    public function test_webhook_expire_restores_inventory_stock_idempotently(): void
    {
        $initialStock = 10;
        $this->variant->update(['stock' => $initialStock]);

        $cart = $this->cartService->getCart($this->customer, 'sess_sprint5_cancel');
        $cart->items()->delete();
        $this->cartService->addItem($cart, $this->variant->id, 3);

        $order = $this->orderService->createOrderFromCart(
            $cart,
            [
                'recipient_name' => 'Budi Santoso',
                'phone' => '081234567890',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'district' => 'Kebayoran Baru',
                'postal_code' => '12190',
                'address_line' => 'Jl. Sudirman No. 1',
            ],
            [
                'courier_company' => 'JNE',
                'courier_service_name' => 'REG',
                'price' => 12000,
            ],
            $this->customer
        );

        // Stock decreased by 3 upon order creation
        $this->assertEquals($initialStock - 3, $this->variant->fresh()->stock);

        $statusCode = '202';
        $grossAmount = number_format($order->total, 2, '.', '');
        $signature = $this->generateSignature($order->order_number, $statusCode, $grossAmount);

        $payload = [
            'order_id' => $order->order_number,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_id' => 'midtrans-tx-expire-999',
            'transaction_status' => 'expire',
            'payment_type' => 'echannel',
        ];

        // First Expire notification
        $response1 = $this->postJson(route('webhooks.midtrans'), $payload);
        $response1->assertStatus(200);

        $this->assertEquals('cancelled', $order->fresh()->status);
        // Stock should be restored to initial 10 (+3)
        $this->assertEquals($initialStock, $this->variant->fresh()->stock);

        // Second Expire notification (Idempotency - must NOT add +3 stock again)
        $response2 = $this->postJson(route('webhooks.midtrans'), $payload);
        $response2->assertStatus(200);
        $response2->assertJson([
            'success' => true,
            'message' => 'Order already cancelled (idempotent).',
        ]);

        // Stock must STILL be exactly 10, not 13
        $this->assertEquals($initialStock, $this->variant->fresh()->stock);
    }

    /*
    |--------------------------------------------------------------------------
    | Section 6: Order Success & Payment Retry Tests
    |--------------------------------------------------------------------------
    */

    public function test_order_success_page_displays_order_details(): void
    {
        $cart = $this->cartService->getCart($this->customer, 'sess_sprint5_success');
        $cart->items()->delete();
        $this->cartService->addItem($cart, $this->variant->id, 1);

        $order = $this->orderService->createOrderFromCart(
            $cart,
            [
                'recipient_name' => 'Budi Santoso',
                'phone' => '081234567890',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'district' => 'Kebayoran Baru',
                'postal_code' => '12190',
                'address_line' => 'Jl. Sudirman No. 1',
            ],
            [
                'courier_company' => 'JNE',
                'courier_service_name' => 'REG',
                'price' => 12000,
            ],
            $this->customer
        );

        $response = $this->actingAs($this->customer)->get(route('orders.success', $order->order_number));
        $response->assertStatus(200);
        $response->assertSee($order->order_number);
        $response->assertSee('Nomor Pesanan');
        $response->assertSee('Rincian Biaya');
    }

    public function test_retry_payment_generates_token_for_pending_order(): void
    {
        $cart = $this->cartService->getCart($this->customer, 'sess_sprint5_retry');
        $cart->items()->delete();
        $this->cartService->addItem($cart, $this->variant->id, 1);

        $order = $this->orderService->createOrderFromCart(
            $cart,
            [
                'recipient_name' => 'Budi Santoso',
                'phone' => '081234567890',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'district' => 'Kebayoran Baru',
                'postal_code' => '12190',
                'address_line' => 'Jl. Sudirman No. 1',
            ],
            [
                'courier_company' => 'JNE',
                'courier_service_name' => 'REG',
                'price' => 12000,
            ],
            $this->customer
        );

        $response = $this->actingAs($this->customer)->postJson(route('orders.retry-payment', $order));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'snap_token',
            'redirect_url',
        ]);
        $this->assertTrue($response->json('success'));
    }
}
