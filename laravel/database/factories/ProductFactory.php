<?php

namespace Database\Factories;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        return [
            'offer_id' => Offer::factory(),
            'name' => Str::title($name),
            'sku' => strtoupper(Str::random(3)) . '-' . $this->faker->unique()->numerify('#####'),
            'image' => $this->faker->optional()->imageUrl(640, 640, 'technics', true, 'product'),
            'price' => $this->faker->randomFloat(2, 0, 9999.99),
            'state' => 'draft',
        ];
    }

    public function published(): self
    {
        return $this->state(fn () => ['state' => 'published']);
    }

    public function invisible(): self
    {
        return $this->state(fn () => ['state' => 'invisible']);
    }
}
