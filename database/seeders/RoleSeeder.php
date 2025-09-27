<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'label' => 'Administrator',
                'description' => 'Full access to all settings and data.',
                'is_system' => true,
                'is_default' => false,
                'abilities' => [
                    'dashboard', 'products', 'warehouses', 'units', 'customers', 'suppliers', 'company-settings', 'roles', 'users', 'reports'
                ],
            ],
            [
                'name' => 'manager',
                'label' => 'Manager',
                'description' => 'Can manage inventory, sales, and purchases.',
                'is_system' => false,
                'is_default' => false,
                'abilities' => [
                    'dashboard', 'products', 'customers', 'suppliers', 'warehouses', 'units', 'quotes', 'sales', 'purchases', 'expenses', 'reports', 'credentials'
                ],
            ],
            [
                'name' => 'staff',
                'label' => 'Staff',
                'description' => 'Standard access for day-to-day operations.',
                'is_system' => false,
                'is_default' => true,
                'abilities' => [
                    'dashboard', 'products', 'customers', 'suppliers', 'quotes', 'sales', 'expenses', 'credentials'
                ],
            ],
        ];

        foreach ($roles as $data) {
            Role::updateOrCreate(
                ['name' => $data['name']],
                [
                    'label' => $data['label'],
                    'description' => $data['description'],
                    'is_system' => $data['is_system'],
                    'is_default' => $data['is_default'],
                ]
            );
        }

        $adminRole = Role::where('name', 'admin')->first();
        $defaultRole = Role::where('is_default', true)->first();

        if ($adminRole) {
            $user = User::first();
            if ($user && ! $user->roles()->where('roles.id', $adminRole->id)->exists()) {
                $user->assignRole($adminRole);
            }
        }

        if ($defaultRole) {
            Role::where('id', '!=', $defaultRole->id)->update(['is_default' => false]);
        }
    }
}


