<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'staff']);

        $adminActions = [
            'users' => ['view', 'create', 'edit', 'delete'],
            'products' => ['view', 'create', 'edit', 'delete'],
            'categories' => ['view', 'create', 'edit', 'delete'],
            'reports' => ['view', 'create', 'edit', 'delete'],
            'roles' => ['view', 'create', 'edit', 'delete'],
            'suppliers' => ['view', 'create', 'edit', 'delete'],
            'settings' => ['view', 'create', 'edit', 'delete'],
        ];

        $userActions = [
            'products' => ['view', 'create', 'edit', 'delete'],
            'categories' => ['view', 'create', 'edit', 'delete'],
            'reports' => ['view', 'create', 'edit', 'delete'],
            'suppliers' => ['view', 'create'],
        ];

        foreach ($adminActions as $resource => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$resource}.{$action}", 'guard_name' => 'web']);
            }
        }

        $adminRole->givePermissionTo(Permission::all());
        $userRole->givePermissionTo([
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',
            'reports.view',
            'suppliers.view',
            'suppliers.create',
        ]);

        // create a default admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);
        $adminUser->assignRole($adminRole);

        // create a default staff user
        $staffUser = User::create([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'password' => bcrypt('password123'),
        ]);
        $staffUser->assignRole($userRole);
    }
}
