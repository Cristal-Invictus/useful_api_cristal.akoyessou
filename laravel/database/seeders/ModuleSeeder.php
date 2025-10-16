<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('modules')->insert([
            [
                'name' => 'URL Shortener',
                'description' => 'Raccourcir et gérer des liens'
            ],
            [
                'name' => 'Wallet',
                'description' => 'Gestion du solde et transferts'
            ],
            [
                'name' => 'Marketplace + Stock Manager',
                'description' => 'Gestion de produits et commandes'
            ],

            [
                'name' => 'Time Tracker',
                'description' => 'Suivi des sessions et durées',
            ],

            [
                'name' => 'Investment Tracker',
                'description' => 'Gestion du portefeuille d’investissement',
            ]
        ]);

    }
}
