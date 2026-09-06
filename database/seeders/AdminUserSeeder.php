<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SUPER ADMIN
        // Real Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@fifa.com'],
            [
                'name' => 'Super Administrator (Real)',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'is_demo' => false,
                'email_verified_at' => now(),
            ]
        );

        // Demo Super Admin
        User::updateOrCreate(
            ['email' => 'demo.superadmin@fifa.test'],
            [
                'name' => 'Demo Super Admin',
                'phone' => '081200000001',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'is_demo' => true,
                'email_verified_at' => now(),
            ]
        );

        // Backward compatibility for existing admin@fifa.test
        User::updateOrCreate(
            ['email' => 'admin@fifa.test'],
            [
                'name' => 'Super Admin (Legacy)',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'is_demo' => false,
                'email_verified_at' => now(),
            ]
        );

        // 2. ADMIN / STORE MANAGER
        // Real Admin
        User::updateOrCreate(
            ['email' => 'admin@fifa.com'],
            [
                'name' => 'Store Admin (Real)',
                'phone' => '081234567891',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_demo' => false,
                'email_verified_at' => now(),
            ]
        );

        // Demo Admin
        User::updateOrCreate(
            ['email' => 'demo.admin@fifa.test'],
            [
                'name' => 'Demo Store Admin',
                'phone' => '081200000002',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_demo' => true,
                'email_verified_at' => now(),
            ]
        );

        // 3. CUSTOMER
        // Real Customer
        User::updateOrCreate(
            ['email' => 'customer@fifa.com'],
            [
                'name' => 'Customer (Real)',
                'phone' => '081987654321',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'is_demo' => false,
                'email_verified_at' => now(),
            ]
        );

        // Demo Customer
        User::updateOrCreate(
            ['email' => 'demo.customer@fifa.test'],
            [
                'name' => 'Demo Customer',
                'phone' => '081900000001',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'is_demo' => true,
                'email_verified_at' => now(),
            ]
        );

        // Backward compatibility for customer@fifa.test
        User::updateOrCreate(
            ['email' => 'customer@fifa.test'],
            [
                'name' => 'Demo Customer (Legacy)',
                'phone' => '081987654321',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'is_demo' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
