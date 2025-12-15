<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Pour reproductibilité des démos/tests manuels
        try { fake()->seed(1337); } catch (\Throwable) {}

        // Pour chaque offre, créer un ensemble de produits
        Offer::query()->get()->each(function (Offer $offer) {
            // Volume standard
            Product::factory()
                ->count(3)
                ->published()
                ->create(['offer_id' => $offer->id]);

            Product::factory()
                ->count(2)
                ->create(['offer_id' => $offer->id]); // draft (par défaut)

            Product::factory()
                ->count(1)
                ->invisible()
                ->create(['offer_id' => $offer->id]);

            // Cas volumineux sur la première offre publiée
            if ($offer->state === 'published' && $offer->id === Offer::min('id')) {
                Product::factory()->count(20)->published()->create(['offer_id' => $offer->id]);
            }
        });
    }
}
