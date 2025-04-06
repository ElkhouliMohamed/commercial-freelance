<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'Basic',
            'price' => 3000.00,
            'description' => "Site web simple\nConfiguration de base des réseaux sociaux\nSupport par email",
        ]);

        Plan::create([
            'name' => 'Advanced',
            'price' => 4800.00,
            'description' => "Site web responsive avancé\nGestion complète des réseaux sociaux\nSupport prioritaire\nSEO de base",
        ]);

        Plan::create([
            'name' => 'Premium E-commerce',
            'price' => 9600.00,
            'description' => "Site e-commerce complet\nStratégie marketing personnalisée\nSupport dédié 24/7\nSEO avancé\nIntégration de système de paiement",
        ]);
    }
}
