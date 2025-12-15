<?php

namespace Database\Seeders;

use App\Models\Offer;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    public function run(): void
    {
        // Quelques offres publiées
        Offer::factory()->count(6)->published()->create();

        // Offres brouillons
        Offer::factory()->count(4)->create(); // état par défaut: draft

        // Offres masquées
        Offer::factory()->count(2)->hidden()->create();

        // Cas limite: une offre publiée sans produit
        Offer::factory()->published()->create([
            'name' => 'Offre sans produit',
            'slug' => 'offre-sans-produit-'.uniqid(),
        ]);
    }
}
