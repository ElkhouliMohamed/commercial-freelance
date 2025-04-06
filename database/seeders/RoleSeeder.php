<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'Super Admin',
            'Admin',
            'Account Manager',
            'Freelancer',
            'Client',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $permissions = [
            'manage users',
            'manage contacts',
            'manage rdvs',
            'create devis',
            'update devis',
            'request commission',
            'manage subscriptions',
            'view rdvs',
            'view plans',
            'assign plans',
            'update rdvs', // Added
            'delete rdvs', // Added
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdmin = Role::findByName('Super Admin', 'web');
        $admin = Role::findByName('Admin', 'web');
        $accountManager = Role::findByName('Account Manager', 'web');
        $freelancer = Role::findByName('Freelancer', 'web');
        $client = Role::findByName('Client', 'web');

        $superAdmin->syncPermissions(Permission::all());

        $admin->syncPermissions([
            'manage users',
            'manage contacts',
            'manage rdvs',
            'manage subscriptions',
            'view plans',
            'update rdvs', // Added
            'delete rdvs', // Added
        ]);

        $accountManager->syncPermissions([
            'manage contacts',
            'manage rdvs',
            'create devis',
            'update devis',
            'assign plans',
            'view rdvs',
            'update rdvs', // Added
            'delete rdvs', // Added
        ]);

        $freelancer->syncPermissions([
            'manage contacts',
            'manage rdvs',
            'request commission',
            'view plans',
            'view rdvs',
            'update rdvs', // Added
            'delete rdvs', // Added
        ]);

        $client->syncPermissions([
            'view plans',
        ]);
    }
}
