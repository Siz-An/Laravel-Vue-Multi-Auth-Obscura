<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create([
            'name' => 'admin',
            'description' => 'Administrator role with full access',
        ]);
        
        $userRole = Role::create([
            'name' => 'user',
            'description' => 'Regular user role',
        ]);
        
        // Create permissions
        $permissions = [
            // Role permissions
            ['name' => 'view roles', 'description' => 'View roles'],
            ['name' => 'create roles', 'description' => 'Create roles'],
            ['name' => 'edit roles', 'description' => 'Edit roles'],
            ['name' => 'delete roles', 'description' => 'Delete roles'],
            
            // Permission permissions
            ['name' => 'view permissions', 'description' => 'View permissions'],
            ['name' => 'create permissions', 'description' => 'Create permissions'],
            ['name' => 'edit permissions', 'description' => 'Edit permissions'],
            ['name' => 'delete permissions', 'description' => 'Delete permissions'],
            
            // User permissions
            ['name' => 'view users', 'description' => 'View users'],
            ['name' => 'create users', 'description' => 'Create users'],
            ['name' => 'edit users', 'description' => 'Edit users'],
            ['name' => 'delete users', 'description' => 'Delete users'],
        ];
        
        foreach ($permissions as $permissionData) {
            Permission::create($permissionData);
        }
        
        // Assign all permissions to admin role
        $adminRole->permissions()->sync(Permission::all());
        
        // Create a super admin user
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'is_super_admin' => true,
            'password' => Hash::make('password'),
        ]);
        
        // Assign admin role to super admin
        $superAdmin->roles()->attach($adminRole);
    }
}
