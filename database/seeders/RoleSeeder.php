<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Define roles
        $roles = ['Super Admin', 'Admin', 'Account Manager', 'Freelancer'];

        // Create roles if they don't already exist
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Define permissions
        $permissions = [
            'manage users',
            'manage contacts',
            'manage rdvs',
            'create devis',
            'update devis', // Add this
            'request commission',
            'manage subscriptions',
        ];

        // Create permissions if they don't already exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign permissions to roles
        $superAdmin = Role::findByName('Super Admin', 'web');
        $admin = Role::findByName('Admin', 'web');
        $accountManager = Role::findByName('Account Manager', 'web');
        $freelancer = Role::findByName('Freelancer', 'web');

        // Super Admin has all permissions
        $superAdmin->syncPermissions(Permission::all());

        // Admin can manage users, contacts, rdvs, subscriptions
        $admin->syncPermissions([
            'manage users',
            'manage contacts',
            'manage rdvs',
            'manage subscriptions',
        ]);

        // Account Manager can manage contacts, rdvs, create devis
        $accountManager->syncPermissions([
            'manage contacts',
            'manage rdvs',
            'create devis',
            'update devis', // Add this
        ]);

        // Freelancer can manage contacts, create rdvs, and request commission
        $freelancer->syncPermissions([
            'manage contacts',
            'manage rdvs',
            'request commission',
        ]);
    }
}
