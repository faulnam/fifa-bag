<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Page;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Shipment;
use App\Models\ShipmentTracking;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\ImageOptimizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Sprint9AuditTest extends TestCase
{
    protected User $admin;
    protected User $customer;
    protected Product $product;
    protected ProductVariant $variant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['email' => 'audit_admin@fifa.test'],
            [
                'name' => 'Audit Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '081234567890',
            ]
        );
        $this->admin->role = 'admin';
        $this->admin->save();

        $this->customer = User::firstOrCreate(
            ['email' => 'audit_customer@fifa.test'],
            [
                'name' => 'Audit Customer',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => '081987654321',
            ]
        );
        $this->customer->role = 'customer';
        $this->customer->save();

        $category = Category::firstOrCreate(
            ['slug' => 'audit-men-shoes'],
            [
                'name' => 'Audit Men Shoes',
                'gender' => 'men',
                'parent_id' => null,
                'order' => 1,
            ]
        );

        $this->product = Product::firstOrCreate(
            ['slug' => 'audit-wool-runner'],
            [
                'category_id' => $category->id,
                'name' => 'Audit Wool Runner',
                'description' => 'Comfortable wool shoe for audit testing',
                'base_price' => 1800000,
                'compare_at_price' => 2000000,
                'weight_grams' => 800,
                'meta_title' => 'Audit Wool Runner — Sepatu Wol Alami',
                'meta_description' => 'Beli Audit Wool Runner bahan wol alami super nyaman.',
            ]
        );
        $this->product->is_active = true;
        $this->product->save();

        $this->variant = ProductVariant::firstOrCreate(
            ['sku' => 'AUDIT-WR-BLK-42'],
            [
                'product_id' => $this->product->id,
                'color_name' => 'Natural Black',
                'color_hex' => '#212121',
                'size' => '42',
                'stock' => 20,
            ]
        );
        $this->variant->stock = 20;
        $this->variant->save();
    }

    public function test_sitemap_xml_renders_valid_xml_with_all_entities(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee('<urlset', false);
        $response->assertSee($this->product->slug);
    }

    public function test_seo_meta_tags_render_dynamically_on_pdp_and_pages(): void
    {
        // PDP SEO meta
        $pdpResponse = $this->get(route('products.show', $this->product->slug));
        $pdpResponse->assertStatus(200);
        $pdpResponse->assertSee($this->product->meta_title ?? $this->product->name);
        $pdpResponse->assertSee('og:title', false);
        $pdpResponse->assertSee('twitter:card', false);

        // CMS Page SEO meta
        $page = Page::first();
        if ($page) {
            $pageResponse = $this->get(route('pages.show', $page->slug));
            $pageResponse->assertStatus(200);
            $pageResponse->assertSee($page->title);
        }
    }

    public function test_complete_e2e_order_fulfillment_and_tracking_lifecycle(): void
    {
        $this->actingAs($this->customer);

        // 1. Add to cart
        $cartAddRes = $this->postJson(route('cart.add'), [
            'product_variant_id' => $this->variant->id,
            'qty' => 1,
        ]);
        $cartAddRes->assertStatus(200);
        $cartAddRes->assertJsonPath('success', true);

        // 2. Checkout rate calculation
        $rateRes = $this->postJson(route('checkout.rates'), [
            'destination_area_id' => 'IDNP6IDNC148IDND859',
        ]);
        $rateRes->assertStatus(200);
        $this->assertTrue($rateRes->json('success'));
        $this->assertNotEmpty($rateRes->json('rates'));

        // 3. Save shipping selection
        $addressPayload = [
            'recipient_name' => 'Budi Santoso',
            'phone' => '08123456789',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'postal_code' => '12190',
            'address_line' => 'Jl. Sudirman No. 100',
            'biteship_area_id' => 'IDNP6IDNC148IDND859',
        ];

        $shippingPayload = [
            'courier_company' => 'sicepat',
            'courier_service_name' => 'SIUNT - Regular',
            'price' => 20000,
            'etd' => '1-2 hari',
        ];

        $saveShippingRes = $this->postJson(route('checkout.save-shipping'), [
            'address' => $addressPayload,
            'shipping' => $shippingPayload,
        ]);
        $saveShippingRes->assertStatus(200);

        // 4. Place order and generate Snap token
        $processRes = $this->postJson(route('checkout.process'), [
            'address' => $addressPayload,
            'shipping' => $shippingPayload,
            'notes' => 'Tolong kirim sebelum jam 5 sore',
        ]);
        $processRes->assertStatus(200);
        $orderNumber = $processRes->json('order_number');
        $this->assertNotEmpty($orderNumber);

        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        $this->assertEquals('pending_payment', $order->status);

        // 5. Midtrans Webhook: Payment Settled
        $serverKey = config('services.midtrans.server_key', '');
        $grossAmount = number_format($order->grand_total, 2, '.', '');
        $signature = hash('sha512', $order->order_number . '200' . $grossAmount . $serverKey);

        $midtransPayload = [
            'order_id' => $order->order_number,
            'status_code' => '200',
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'payment_type' => 'bank_transfer',
            'transaction_id' => 'midtrans_txn_' . $order->id,
            'transaction_time' => now()->toDateTimeString(),
        ];

        $webhookRes = $this->postJson(route('webhooks.midtrans'), $midtransPayload);
        $webhookRes->assertStatus(200);

        $order->refresh();
        $this->assertEquals('paid', $order->status);

        // 6. Admin processes shipping to Biteship
        $this->actingAs($this->admin);
        $adminProcessRes = $this->post(route('admin.orders.process-shipping', $order));
        $adminProcessRes->assertStatus(302);
        $adminProcessRes->assertSessionHas('success');

        $order->refresh();
        $shipment = $order->shipment;
        $this->assertNotNull($shipment);
        $this->assertNotEmpty($shipment->biteship_order_id);

        // 7. Admin requests pickup
        $pickupRes = $this->post(route('admin.orders.request-pickup', $order));
        $pickupRes->assertStatus(302);
        $pickupRes->assertSessionHas('success');

        $shipment = $order->fresh()->shipment;

        // 8. Biteship Webhook updates tracking status
        $biteshipPayload = [
            'event' => 'order.status',
            'order_id' => $shipment->biteship_order_id,
            'status' => 'delivered',
            'tracking' => [
                'status' => 'delivered',
                'note' => 'Paket telah diterima oleh Budi Santoso',
                'updated_at' => now()->toDateTimeString(),
            ],
        ];

        $biteshipWebhookRes = $this->postJson(route('webhooks.biteship'), $biteshipPayload);
        $biteshipWebhookRes->assertStatus(200);
        $biteshipWebhookRes->assertJson(['message' => 'Biteship webhook processed successfully (idempotent).']);

        $shipmentFresh = Shipment::find($shipment->id);
        $orderFresh = Order::find($order->id);
        $this->assertEquals('delivered', $shipmentFresh->status);
        $this->assertEquals('delivered', $orderFresh->status);

        // 9. Customer views tracking timeline on account orders detail
        $this->actingAs($this->customer);
        $customerTrackRes = $this->get(route('account.orders.show', $orderFresh));
        $customerTrackRes->assertStatus(200);
        $customerTrackRes->assertSee($order->order_number);
    }

    public function test_security_current_password_mandatory_on_password_change(): void
    {
        $this->customer->password = Hash::make('password123');
        $this->customer->save();

        $this->actingAs($this->customer);

        // Invalid current password should fail
        $failRes = $this->put(route('account.profile.password'), [
            'current_password' => 'wrongpassword',
            'password' => 'newSecretPassword123',
            'password_confirmation' => 'newSecretPassword123',
        ]);
        $failRes->assertSessionHasErrors('current_password');

        // Valid current password should succeed
        $successRes = $this->put(route('account.profile.password'), [
            'current_password' => 'password123',
            'password' => 'newSecretPassword123',
            'password_confirmation' => 'newSecretPassword123',
        ]);
        $successRes->assertRedirect(route('account.profile'));
        $successRes->assertSessionHas('success');

        $this->customer->refresh();
        $this->assertTrue(Hash::check('newSecretPassword123', $this->customer->password));
    }

    public function test_image_optimizer_stores_and_resizes_uploaded_file(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test_product.jpg', 2000, 2000);
        $storedPath = ImageOptimizer::optimizeAndStore($file, 'products', 1200, 80);

        $this->assertNotEmpty($storedPath);
        Storage::disk('public')->assertExists($storedPath);
    }

    public function test_checkout_process_with_simulate_payment_success(): void
    {
        $this->actingAs($this->customer);

        // Add item to cart
        $cartAdd = $this->postJson(route('cart.add'), [
            'product_variant_id' => $this->variant->id,
            'qty' => 1,
        ]);
        $cartAdd->assertStatus(200);

        $payload = [
            'address' => [
                'recipient_name' => 'Budi Santoso',
                'phone' => '08123456789',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'district' => 'Kebayoran Baru',
                'postal_code' => '12110',
                'address_line' => 'Jl. Senopati No. 10',
                'biteship_area_id' => 'IDNP6IDNC148IDND843IDZ12110',
            ],
            'shipping' => [
                'courier_name' => 'JNE',
                'courier_code' => 'jne',
                'courier_service_name' => 'Reguler',
                'courier_service_code' => 'reg',
                'courier_company' => 'jne',
                'price' => 15000,
                'duration' => '1-2 hari',
            ],
            'simulate_success' => true,
        ];

        $response = $this->postJson(route('checkout.process'), $payload);
        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'simulated' => true]);

        $orderNumber = $response->json('order_number');
        $order = Order::where('order_number', $orderNumber)->first();
        $this->assertNotNull($order);
        $this->assertEquals('paid', $order->status);

        $payment = $order->payment;
        $this->assertNotNull($payment);
        $this->assertEquals('success', $payment->status);
        $this->assertEquals('simulation_sandbox', $payment->payment_method);
    }

    public function test_order_simulate_payment_endpoint(): void
    {
        $this->actingAs($this->customer);

        $order = Order::create([
            'order_number' => 'ORD-SIM-' . uniqid(),
            'user_id' => $this->customer->id,
            'status' => 'pending_payment',
            'subtotal' => 100000,
            'discount' => 0,
            'shipping_cost' => 10000,
            'total' => 110000,
            'recipient_name' => 'Test Simulation',
            'recipient_phone' => '08123456789',
            'shipping_address' => 'Jl. Test No. 1',
            'city' => 'Surabaya',
            'province' => 'Jawa Timur',
            'postal_code' => '60111',
            'courier_name' => 'JNE',
            'courier_service' => 'REG',
        ]);

        Payment::create([
            'order_id' => $order->id,
            'gateway' => 'midtrans',
            'gateway_reference' => 'TEST-123',
            'payment_method' => 'midtrans_snap',
            'status' => 'pending',
            'amount' => 110000,
        ]);

        $response = $this->postJson(route('orders.simulate-payment', $order));
        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'order_status' => 'paid']);

        $order->refresh();
        $this->assertEquals('paid', $order->status);
        $this->assertEquals('success', $order->payment->status);
    }
}
