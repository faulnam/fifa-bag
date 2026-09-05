<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\BiteshipService;
use App\Services\CartService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class Sprint4Test extends TestCase
{
    use DatabaseTransactions;

    protected User $customer;
    protected User $otherCustomer;
    protected Product $product;
    protected ProductVariant $variant;
    protected CartService $cartService;
    protected BiteshipService $biteshipService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->cartService = app(CartService::class);
        $this->biteshipService = app(BiteshipService::class);

        $this->customer = User::where('role', 'customer')->first() ?? User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi_sprint4@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '081234567890',
        ]);

        $this->otherCustomer = User::create([
            'name' => 'Siti Rahma',
            'email' => 'siti_sprint4@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '089876543210',
        ]);

        $this->product = Product::with('variants')->where('is_active', true)->first();
        $this->variant = $this->product->variants->first();
        $this->variant->update(['stock' => 15]);
    }

    /*
    |--------------------------------------------------------------------------
    | Section 1: Customer Address CRUD Tests
    |--------------------------------------------------------------------------
    */

    public function test_guest_cannot_access_addresses_page(): void
    {
        $response = $this->get(route('account.addresses.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_customer_can_view_addresses_page(): void
    {
        $response = $this->actingAs($this->customer)->get(route('account.addresses.index'));
        $response->assertStatus(200);
        $response->assertSee('Buku Alamat');
    }

    public function test_customer_can_create_address_and_it_becomes_default_if_first(): void
    {
        // Ensure no address exists initially
        $this->customer->addresses()->delete();

        $addressData = [
            'label' => 'Rumah Utama',
            'recipient_name' => 'Budi Santoso',
            'phone' => '081234567890',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'postal_code' => '12190',
            'address_line' => 'Jl. Senopati No. 45, RT 02 / RW 03',
            'biteship_area_id' => 'IDNP6IDNC148IDND859',
            'is_default' => 0, // Even if 0, first address automatically defaults to true
        ];

        $response = $this->actingAs($this->customer)->post(route('account.addresses.store'), $addressData);
        $response->assertRedirect(route('account.addresses.index'));

        $this->assertDatabaseHas('addresses', [
            'user_id' => $this->customer->id,
            'label' => 'Rumah Utama',
            'postal_code' => '12190',
            'is_default' => 1,
        ]);
    }

    public function test_setting_new_address_as_default_unsets_previous_default(): void
    {
        $this->customer->addresses()->delete();

        $address1 = $this->customer->addresses()->create([
            'label' => 'Rumah 1',
            'recipient_name' => 'Budi 1',
            'phone' => '081234567890',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'postal_code' => '12190',
            'address_line' => 'Alamat 1',
            'is_default' => true,
        ]);

        $address2 = $this->customer->addresses()->create([
            'label' => 'Kantor 2',
            'recipient_name' => 'Budi 2',
            'phone' => '081234567890',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Pusat',
            'district' => 'Menteng',
            'postal_code' => '10310',
            'address_line' => 'Alamat 2',
            'is_default' => false,
        ]);

        $response = $this->actingAs($this->customer)->patch(route('account.addresses.default', $address2));
        $response->assertRedirect(route('account.addresses.index'));

        $this->assertTrue($address2->fresh()->is_default);
        $this->assertFalse($address1->fresh()->is_default);
    }

    public function test_customer_can_update_own_address(): void
    {
        $address = $this->customer->addresses()->create([
            'label' => 'Alamat Lama',
            'recipient_name' => 'Budi Lama',
            'phone' => '081234567890',
            'province' => 'Jawa Barat',
            'city' => 'Kota Bandung',
            'district' => 'Coblong',
            'postal_code' => '40115',
            'address_line' => 'Jl. Dago No. 10',
            'is_default' => true,
        ]);

        $response = $this->actingAs($this->customer)->put(route('account.addresses.update', $address), [
            'label' => 'Alamat Baru',
            'recipient_name' => 'Budi Update',
            'phone' => '081299998888',
            'province' => 'Jawa Barat',
            'city' => 'Kota Bandung',
            'district' => 'Coblong',
            'postal_code' => '40115',
            'address_line' => 'Jl. Dago No. 99 (Gedung Baru)',
            'is_default' => 1,
        ]);

        $response->assertRedirect(route('account.addresses.index'));
        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'label' => 'Alamat Baru',
            'recipient_name' => 'Budi Update',
        ]);
    }

    public function test_customer_cannot_update_or_delete_other_customer_address(): void
    {
        $foreignAddress = $this->otherCustomer->addresses()->create([
            'label' => 'Rumah Siti',
            'recipient_name' => 'Siti Rahma',
            'phone' => '089876543210',
            'province' => 'Bali',
            'city' => 'Kota Denpasar',
            'district' => 'Denpasar Selatan',
            'postal_code' => '80234',
            'address_line' => 'Jl. Sanur No. 12',
            'is_default' => true,
        ]);

        $response = $this->actingAs($this->customer)->put(route('account.addresses.update', $foreignAddress), [
            'recipient_name' => 'Hacker Name',
            'phone' => '081234567890',
            'province' => 'Bali',
            'city' => 'Kota Denpasar',
            'district' => 'Denpasar Selatan',
            'postal_code' => '80234',
            'address_line' => 'Hacked Line',
        ]);

        $response->assertStatus(403);

        $deleteResponse = $this->actingAs($this->customer)->delete(route('account.addresses.destroy', $foreignAddress));
        $deleteResponse->assertStatus(403);
    }

    /*
    |--------------------------------------------------------------------------
    | Section 2: Biteship Area Autocomplete Tests
    |--------------------------------------------------------------------------
    */

    public function test_area_autocomplete_requires_minimum_3_characters(): void
    {
        $response = $this->getJson(route('shipping.areas', ['query' => 'bd']));
        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
            'areas' => [],
        ]);
    }

    public function test_area_autocomplete_returns_structured_results(): void
    {
        $response = $this->getJson(route('shipping.areas', ['query' => 'Bandung']));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'areas' => [
                '*' => [
                    'id',
                    'name',
                    'province',
                    'city',
                    'district',
                    'postal_code',
                ],
            ],
        ]);
        $this->assertTrue($response->json('success'));
        $this->assertNotEmpty($response->json('areas'));
    }

    /*
    |--------------------------------------------------------------------------
    | Section 3: Checkout Rates & Multi-Step Flow Tests
    |--------------------------------------------------------------------------
    */

    public function test_empty_cart_redirects_away_from_checkout(): void
    {
        $cart = $this->cartService->getCart($this->customer, 'sess_test_123');
        $cart->items()->delete();

        $response = $this->actingAs($this->customer)->get(route('checkout.index'));
        $response->assertRedirect(route('cart.index'));
    }

    public function test_checkout_page_loads_with_cart_items(): void
    {
        $cart = $this->cartService->getCart($this->customer, 'sess_test_123');
        $cart->items()->delete();
        $this->cartService->addItem($cart, $this->variant->id, 1);

        $response = $this->actingAs($this->customer)->get(route('checkout.index'));
        $response->assertStatus(200);
        $response->assertSee('Alamat Pengiriman');
        $response->assertSee('Ringkasan Pesanan');
    }

    public function test_checkout_rates_calculation_returns_couriers_list(): void
    {
        $cart = $this->cartService->getCart($this->customer, 'sess_test_123');
        $cart->items()->delete();
        $this->cartService->addItem($cart, $this->variant->id, 2);

        $response = $this->actingAs($this->customer)->postJson(route('checkout.rates'), [
            'destination_area_id' => 'IDNP6IDNC148IDND843IDZ12250',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'rates' => [
                '*' => [
                    'courier_company',
                    'courier_name',
                    'courier_service_name',
                    'courier_service_code',
                    'duration',
                    'price',
                    'price_formatted',
                    'type',
                ],
            ],
            'is_free_shipping',
            'free_shipping_threshold',
        ]);
        $this->assertTrue($response->json('success'));
        $this->assertNotEmpty($response->json('rates'));
    }

    public function test_checkout_handles_no_couriers_available_edge_case(): void
    {
        $cart = $this->cartService->getCart($this->customer, 'sess_test_123');
        $cart->items()->delete();
        $this->cartService->addItem($cart, $this->variant->id, 1);

        $response = $this->actingAs($this->customer)->postJson(route('checkout.rates'), [
            'destination_area_id' => 'SIMULATE_NO_COURIERS',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
            'rates' => [],
            'error_code' => 'NO_COURIERS',
        ]);
        $this->assertStringContainsString('Tidak ada layanan kurir', $response->json('error'));
    }

    public function test_checkout_handles_api_timeout_error_edge_case(): void
    {
        $cart = $this->cartService->getCart($this->customer, 'sess_test_123');
        $cart->items()->delete();
        $this->cartService->addItem($cart, $this->variant->id, 1);

        $response = $this->actingAs($this->customer)->postJson(route('checkout.rates'), [
            'destination_area_id' => 'SIMULATE_TIMEOUT',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
            'rates' => [],
            'error_code' => 'API_ERROR',
        ]);
        $this->assertStringContainsString('Gagal terhubung', $response->json('error'));
    }

    public function test_checkout_save_shipping_persists_to_session(): void
    {
        $cart = $this->cartService->getCart($this->customer, 'sess_test_123');
        $cart->items()->delete();
        $this->cartService->addItem($cart, $this->variant->id, 1);

        $payload = [
            'address' => [
                'recipient_name' => 'Budi Santoso',
                'phone' => '081234567890',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'district' => 'Kebayoran Baru',
                'postal_code' => '12190',
                'address_line' => 'Jl. Senopati No. 12',
                'biteship_area_id' => 'IDNP6IDNC148IDND859',
            ],
            'shipping' => [
                'courier_company' => 'jne',
                'courier_name' => 'JNE',
                'courier_service_name' => 'REG (Reguler)',
                'courier_service_code' => 'reg',
                'price' => 12000,
                'duration' => '1 - 2 hari',
            ],
        ];

        $response = $this->actingAs($this->customer)->postJson(route('checkout.save-shipping'), $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'next_step' => 3,
        ]);
    }
}
