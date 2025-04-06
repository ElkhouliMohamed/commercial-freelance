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
        $roles = [
            'Super Admin',
            'Admin',
            'Account Manager',
            'Freelancer',
            'Client', // New role for clients
        ];

        // Create roles if they don't already exist
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Define permissions
        $permissions = [
            'manage users',
            'manage contacts',
            'manage rdvs', // This is a general permission
            'create devis',
            'update devis',
            'request commission',
            'manage subscriptions',
            'view rdvs',
            'view plans',
            'assign plans',
            'update rdvs', // Add this new permission
            'delete rdvs', // Add this new permission
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
        $client = Role::findByName('Client', 'web'); // New role

        // Super Admin has all permissions
        $superAdmin->syncPermissions(Permission::all());

        // Admin can manage users, contacts, rdvs, and subscriptions
        $admin->syncPermissions([
            'manage users',
            'manage contacts',
            'manage rdvs',
            'manage subscriptions',
            'view plans',
        ]);

        // Account Manager can manage contacts, rdvs, create and update devis, and assign plans
        $accountManager->syncPermissions([
            'manage contacts',
            'manage rdvs',
            'create devis',
            'update devis',
            'assign plans',
            'view rdvs',
        ]);

        // Freelancer can manage contacts, create rdvs, request commission, and view plans
        $freelancer->syncPermissions([
            'manage contacts',
            'manage rdvs', // Ensure this is included for all RDV actions
            'request commission',
            'view plans',
            'view rdvs',
            'update rdvs', // Add if missing
            'delete rdvs', // Add if missing
        ]);

        // Client can only view plans
        $client->syncPermissions([
            'view plans',
        ]);
    }
}
