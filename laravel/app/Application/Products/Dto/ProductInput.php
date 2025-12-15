<?php
declare(strict_types=1);

namespace App\Application\Products\Dto;

final class ProductInput
{
    public function __construct(
        public string $name,
        public string $sku,
        public ?string $imagePath,
        public float $price,
        public string $state,
    ) {
    }
}
