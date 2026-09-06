<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\DemoActivity;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\DemoCleanupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DemoRoleAutoCleanupTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdminReal;
    protected User $superAdminDemo;
    protected User $adminReal;
    protected User $adminDemo;
    protected User $customerReal;
    protected User $customerDemo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdminReal = User::create([
            'name' => 'Super Admin Real',
            'email' => 'superadmin@fifa.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_demo' => false,
        ]);

        $this->superAdminDemo = User::create([
            'name' => 'Demo Super Admin',
            'email' => 'demo.superadmin@fifa.test',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_demo' => true,
        ]);

        $this->adminReal = User::create([
            'name' => 'Admin Real',
            'email' => 'admin@fifa.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_demo' => false,
        ]);

        $this->adminDemo = User::create([
            'name' => 'Demo Admin',
            'email' => 'demo.admin@fifa.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_demo' => true,
        ]);

        $this->customerReal = User::create([
            'name' => 'Customer Real',
            'email' => 'customer@fifa.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_demo' => false,
        ]);

        $this->customerDemo = User::create([
            'name' => 'Demo Customer',
            'email' => 'demo.customer@fifa.test',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_demo' => true,
        ]);
    }

    public function test_roles_and_demo_flags_are_configured_properly(): void
    {
        $this->assertTrue($this->superAdminReal->isSuperAdmin());
        $this->assertFalse($this->superAdminReal->isDemo());

        $this->assertTrue($this->superAdminDemo->isSuperAdmin());
        $this->assertTrue($this->superAdminDemo->isDemo());

        $this->assertTrue($this->adminReal->isAdmin());
        $this->assertFalse($this->adminReal->isDemo());

        $this->assertTrue($this->adminDemo->isAdmin());
        $this->assertTrue($this->adminDemo->isDemo());

        $this->assertTrue($this->customerReal->isCustomer());
        $this->assertFalse($this->customerReal->isDemo());

        $this->assertTrue($this->customerDemo->isCustomer());
        $this->assertTrue($this->customerDemo->isDemo());
    }

    public function test_quick_login_endpoint_authenticates_demo_and_real_roles(): void
    {
        $response = $this->post(route('demo.quick-login'), [
            'role' => 'super_admin',
            'is_demo' => 1,
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->superAdminDemo);

        // Quick login as customer demo
        $responseCustomer = $this->post(route('demo.quick-login'), [
            'role' => 'customer',
            'is_demo' => 1,
        ]);

        $responseCustomer->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($this->customerDemo);
    }

    public function test_content_created_by_demo_account_is_tracked_and_auto_deleted_after_10_minutes(): void
    {
        $this->actingAs($this->adminDemo);

        // 1. Demo Admin creates a new Category
        $category = Category::create([
            'name' => 'Demo Sepatu Lari',
            'slug' => 'demo-sepatu-lari',
            'description' => 'Kategori dibuat oleh demo admin',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('categories', ['id' => $category->id]);

        // Verify DemoActivity was created with 10-minute expiry
        $activity = DemoActivity::where('record_type', Category::class)
            ->where('record_id', (string) $category->id)
            ->where('action', 'created')
            ->first();

        $this->assertNotNull($activity);
        $this->assertEquals($this->adminDemo->id, $activity->user_id);
        $this->assertTrue($activity->expires_at->gt(now()));

        // 2. Before 10 minutes: cleanup does NOT delete the category
        $service = app(DemoCleanupService::class);
        $cleaned = $service->cleanupExpired();
        $this->assertEquals(0, $cleaned);
        $this->assertDatabaseHas('categories', ['id' => $category->id]);

        // 3. Fast-forward time past 10 minutes (11 minutes later)
        $this->travel(11)->minutes();

        // 4. Run cleanup -> category should be automatically deleted!
        $cleanedAfter10 = $service->cleanupExpired();
        $this->assertEquals(1, $cleanedAfter10);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertDatabaseMissing('demo_activities', ['id' => $activity->id]);
    }

    public function test_content_modified_by_demo_account_is_reverted_back_after_10_minutes(): void
    {
        // Setup initial baseline category created by system/real admin
        $category = Category::withoutEvents(function () {
            return Category::create([
                'name' => 'Original Sneaker',
                'slug' => 'original-sneaker',
                'description' => 'Original Description',
                'is_active' => true,
            ]);
        });

        // Act as Demo Super Admin and edit the category
        $this->actingAs($this->superAdminDemo);

        $category->update([
            'name' => 'Hacked / Demo Changed Name',
            'description' => 'Altered by demo user',
        ]);

        $this->assertEquals('Hacked / Demo Changed Name', $category->fresh()->name);

        // Verify update activity tracked original data
        $activity = DemoActivity::where('record_type', Category::class)
            ->where('record_id', (string) $category->id)
            ->where('action', 'updated')
            ->first();

        $this->assertNotNull($activity);
        $this->assertEquals('Original Sneaker', $activity->original_data['name']);

        // Fast-forward 10 minutes and cleanup
        $this->travel(10)->minutes();
        $this->travel(1)->seconds();

        $service = app(DemoCleanupService::class);
        $service->cleanupExpired();

        // Category should be reverted back to Original Sneaker!
        $revertedCategory = $category->fresh();
        $this->assertEquals('Original Sneaker', $revertedCategory->name);
        $this->assertEquals('Original Description', $revertedCategory->description);
    }

    public function test_content_created_by_real_account_is_not_tracked_and_never_deleted(): void
    {
        $this->actingAs($this->adminReal);

        // Real Admin creates a Category
        $category = Category::create([
            'name' => 'Permanent Category',
            'slug' => 'permanent-category',
            'description' => 'Created by real admin',
            'is_active' => true,
        ]);

        // No DemoActivity should be created
        $this->assertDatabaseMissing('demo_activities', [
            'record_type' => Category::class,
            'record_id' => (string) $category->id,
        ]);

        // Fast-forward time
        $this->travel(30)->minutes();

        $service = app(DemoCleanupService::class);
        $service->cleanupExpired();

        // Permanent category still exists!
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_manual_demo_reset_endpoint_immediately_reverts_changes(): void
    {
        $this->actingAs($this->superAdminDemo);

        $category = Category::create([
            'name' => 'Temporary Category',
            'slug' => 'temp-category',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('categories', ['id' => $category->id]);

        // Call manual reset endpoint
        $response = $this->post(route('demo.reset'));
        $response->assertRedirect();

        // Should immediately be cleaned up without waiting 10 minutes
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
