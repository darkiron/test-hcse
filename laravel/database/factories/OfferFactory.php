<?php

namespace Database\Factories;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Offer>
 */
class OfferFactory extends Factory
{
    protected $model = Offer::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->sentence(3);
        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(6),
            'description' => $this->faker->optional()->paragraph(),
            'image' => $this->faker->imageUrl(640, 480, 'business', true, 'offer'),
            'state' => 'draft',
        ];
    }

    public function published(): self
    {
        return $this->state(fn () => ['state' => 'published']);
    }

    public function hidden(): self
    {
        return $this->state(fn () => ['state' => 'hidden']);
    }
}
