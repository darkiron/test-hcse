<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reproductibilité basique pour les démos/tests manuels
        try { fake()->seed(1337); } catch (\Throwable) {}

        // Conserver un utilisateur de test si besoin
        $this->call([AdminUserSeeder::class]);

        // Données métier (offres + produits)
        $this->call([
            OfferSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
