<?php

namespace Tests\Feature\Api;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OffersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Migrate and seed using our new seeders
        $this->artisan('migrate', ['--force' => true]);
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    public function test_index_returns_only_published_offers_with_only_published_products(): void
    {
        // Pré-conditions rapides: vérifier qu'on a bien des offres dans divers états
        $this->assertGreaterThan(0, Offer::count());

        $response = $this->getJson('/api/offers');

        $response->assertOk();

        $data = $response->json();
        $this->assertIsArray($data);

        foreach ($data as $offer) {
            // Chaque offre retournée doit être publiée
            $this->assertEquals('published', $offer['state']);

            // Et ses produits doivent être publiés (si présents)
            if (isset($offer['products']) && is_array($offer['products'])) {
                foreach ($offer['products'] as $product) {
                    $this->assertEquals('published', $product['state']);
                }
            }
        }
    }
}
