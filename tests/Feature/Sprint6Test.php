<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Shipment;
use App\Models\ShipmentTracking;
use App\Models\User;
use App\Services\BiteshipService;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class Sprint6Test extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected User $customer;
    protected User $otherCustomer;
    protected Product $product;
    protected ProductVariant $variant;
    protected CartService $cartService;
    protected OrderService $orderService;
    protected BiteshipService $biteshipService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->cartService = app(CartService::class);
        $this->orderService = app(OrderService::class);
        $this->biteshipService = app(BiteshipService::class);

        $this->admin = User::where('role', 'admin')->first() ?? User::where('role', 'super_admin')->first() ?? User::create([
            'name' => 'Admin Test',
            'email' => 'admin_sprint6@fifa.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->customer = User::where('role', 'customer')->first() ?? User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi_sprint6@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '081234567890',
        ]);

        $this->otherCustomer = User::create([
            'name' => 'Siti Rahma',
            'email' => 'siti_sprint6@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '089876543210',
        ]);

        $this->product = Product::with('variants')->where('is_active', true)->first();
        $this->variant = $this->product->variants->first();
        $this->variant->update(['stock' => 10]);
    }

    /**
     * Helper to create a paid test order.
     */
    protected function createTestOrder(User $user, int $qty = 1): Order
    {
        $cart = $this->cartService->getCart($user, 'sess_sprint6_' . uniqid());
        $cart->items()->delete();
        $this->cartService->addItem($cart, $this->variant->id, $qty);

        $order = $this->orderService->createOrderFromCart(
            $cart,
            [
                'recipient_name' => $user->name,
                'phone' => $user->phone ?? '081234567890',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'district' => 'Kebayoran Baru',
                'postal_code' => '12190',
                'address_line' => 'Jl. Senopati No. 88',
                'biteship_area_id' => 'IDNP6IDNC148IDND859',
            ],
            [
                'courier_company' => 'jne',
                'courier_name' => 'JNE',
                'courier_service_name' => 'REG (Reguler)',
                'courier_service_code' => 'reg',
                'price' => 12000,
            ],
            $user
        );

        return $this->orderService->markOrderPaid($order, [
            'transaction_id' => 'tx-' . uniqid(),
            'payment_type' => 'bca_va',
            'amount' => $order->total,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Section 1: Admin Order Management Tests
    |--------------------------------------------------------------------------
    */

    public function test_admin_can_view_orders_index_with_filters(): void
    {
        $order = $this->createTestOrder($this->customer);

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index', ['status' => 'paid']));
        $response->assertStatus(200);
        $response->assertSee($order->order_number);
        $response->assertSee('Kelola Pesanan');
    }

    public function test_admin_can_view_order_details(): void
    {
        $order = $this->createTestOrder($this->customer);

        $response = $this->actingAs($this->admin)->get(route('admin.orders.show', $order));
        $response->assertStatus(200);
        $response->assertSee($order->order_number);
        $response->assertSee('Aksi Fulfillment');
        $response->assertSee('Item Produk');
    }

    public function test_admin_can_update_order_status_manually(): void
    {
        $order = $this->createTestOrder($this->customer);

        $response = $this->actingAs($this->admin)->patch(route('admin.orders.status', $order), [
            'status' => 'processing',
        ]);

        $response->assertRedirect();
        $this->assertEquals('processing', $order->fresh()->status);
    }

    public function test_admin_cancelling_order_restores_variant_stock(): void
    {
        $initialStock = 10;
        $this->variant->update(['stock' => $initialStock]);

        $order = $this->createTestOrder($this->customer, 2);
        $this->assertEquals($initialStock - 2, $this->variant->fresh()->stock);

        $response = $this->actingAs($this->admin)->patch(route('admin.orders.status', $order), [
            'status' => 'cancelled',
        ]);

        $response->assertRedirect();
        $this->assertEquals('cancelled', $order->fresh()->status);
        // Stock should be restored to 10
        $this->assertEquals($initialStock, $this->variant->fresh()->stock);
    }

    public function test_admin_can_update_internal_notes(): void
    {
        $order = $this->createTestOrder($this->customer);

        $response = $this->actingAs($this->admin)->patch(route('admin.orders.notes', $order), [
            'notes' => 'Customer meminta pengiriman dikemas dengan kardus tebal.',
        ]);

        $response->assertRedirect();
        $this->assertEquals('Customer meminta pengiriman dikemas dengan kardus tebal.', $order->fresh()->notes);
    }

    /*
    |--------------------------------------------------------------------------
    | Section 2: Biteship Fulfillment & Pickup Request Tests
    |--------------------------------------------------------------------------
    */

    public function test_admin_can_process_shipping_to_biteship(): void
    {
        $order = $this->createTestOrder($this->customer);

        $response = $this->actingAs($this->admin)->post(route('admin.orders.process-shipping', $order));
        $response->assertRedirect();

        $shipment = $order->fresh()->shipment;
        $this->assertNotNull($shipment);
        $this->assertNotNull($shipment->biteship_order_id);
        $this->assertNotNull($shipment->tracking_id);
        $this->assertNotNull($shipment->waybill_id);

        $this->assertEquals('processing', $order->fresh()->status);
        $this->assertTrue($shipment->trackings()->where('status', 'order_created')->exists());
    }

    public function test_admin_can_request_courier_pickup(): void
    {
        $order = $this->createTestOrder($this->customer);

        // Process shipping first to get biteship_order_id
        $this->actingAs($this->admin)->post(route('admin.orders.process-shipping', $order));

        // Request pickup
        $response = $this->actingAs($this->admin)->post(route('admin.orders.request-pickup', $order));
        $response->assertRedirect();

        $shipment = $order->fresh()->shipment;
        $this->assertEquals('requested', $shipment->status);
        $this->assertNotNull($shipment->pickup_scheduled_at);
        $this->assertEquals('ready_to_ship', $order->fresh()->status);
        $this->assertTrue($shipment->trackings()->where('status', 'pickup_requested')->exists());
    }

    public function test_admin_request_pickup_failure_displays_specific_error(): void
    {
        $order = $this->createTestOrder($this->customer);

        // Process shipping first
        $this->actingAs($this->admin)->post(route('admin.orders.process-shipping', $order));

        // Force simulate pickup failure on shipment
        $shipment = $order->fresh()->shipment;
        $shipment->update(['biteship_order_id' => 'SIMULATE_PICKUP_FAIL']);

        $response = $this->actingAs($this->admin)->post(route('admin.orders.request-pickup', $order));
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertStringContainsString('Saldo akun Biteship tidak mencukupi', session('error'));
    }

    /*
    |--------------------------------------------------------------------------
    | Section 3: Biteship Webhook & Idempotent Tracking Tests
    |--------------------------------------------------------------------------
    */

    public function test_biteship_webhook_updates_shipment_and_records_tracking_idempotently(): void
    {
        $order = $this->createTestOrder($this->customer);
        $this->actingAs($this->admin)->post(route('admin.orders.process-shipping', $order));

        $shipment = $order->fresh()->shipment;
        $biteshipOrderId = $shipment->biteship_order_id;

        $payload = [
            'event' => 'order.status',
            'order_id' => $biteshipOrderId,
            'courier_tracking_id' => 'TRK-987654',
            'courier_waybill_id' => 'JNE-WAY-12345678',
            'courier_company' => 'jne',
            'courier_type' => 'reg',
            'status' => 'dropping_off',
            'tracking' => [
                'status' => 'dropping_off',
                'note' => 'Paket sedang diantar oleh kurir ke alamat tujuan.',
                'updated_at' => now()->toDateTimeString(),
            ],
        ];

        // 1. First Webhook Request
        $response1 = $this->postJson(route('webhooks.biteship'), $payload);
        $response1->assertStatus(200);
        $response1->assertJson(['success' => true]);

        $shipmentFresh = $shipment->fresh();
        $this->assertEquals('on_process', $shipmentFresh->status);
        $this->assertEquals('shipped', $order->fresh()->status);
        $this->assertEquals('TRK-987654', $shipmentFresh->tracking_id);
        $this->assertEquals('JNE-WAY-12345678', $shipmentFresh->waybill_id);

        $initialTrackingCount = $shipmentFresh->trackings()->count();

        // 2. Second Duplicate Webhook Request (Idempotency Test)
        $response2 = $this->postJson(route('webhooks.biteship'), $payload);
        $response2->assertStatus(200);
        $response2->assertJson(['success' => true]);

        // Assert no duplicate tracking history was inserted
        $this->assertEquals($initialTrackingCount, $shipmentFresh->fresh()->trackings()->count());
    }

    public function test_biteship_webhook_delivered_status_updates_order_to_delivered(): void
    {
        $order = $this->createTestOrder($this->customer);
        $this->actingAs($this->admin)->post(route('admin.orders.process-shipping', $order));

        $shipment = $order->fresh()->shipment;

        $payload = [
            'event' => 'order.status',
            'order_id' => $shipment->biteship_order_id,
            'status' => 'delivered',
            'tracking' => [
                'status' => 'delivered',
                'note' => 'Paket telah diterima oleh Budi Santoso.',
                'updated_at' => now()->toDateTimeString(),
            ],
        ];

        $response = $this->postJson(route('webhooks.biteship'), $payload);
        $response->assertStatus(200);

        $this->assertEquals('delivered', $shipment->fresh()->status);
        $this->assertEquals('delivered', $order->fresh()->status);
    }

    /*
    |--------------------------------------------------------------------------
    | Section 4: Customer Account Orders & Live Tracking View Tests
    |--------------------------------------------------------------------------
    */

    public function test_customer_can_view_orders_and_vertical_live_tracking(): void
    {
        $order = $this->createTestOrder($this->customer);

        // Process shipping to have tracking events
        $this->actingAs($this->admin)->post(route('admin.orders.process-shipping', $order));

        $response = $this->actingAs($this->customer)->get(route('account.orders.show', $order));
        $response->assertStatus(200);
        $response->assertSee($order->order_number);
        $response->assertSee('Pelacakan Pengiriman (Live Tracking)');
        $response->assertSee('Rincian Produk');
    }

    public function test_customer_cannot_view_other_customer_order(): void
    {
        $foreignOrder = $this->createTestOrder($this->otherCustomer);

        $response = $this->actingAs($this->customer)->get(route('account.orders.show', $foreignOrder));
        $response->assertStatus(403);
    }
}
