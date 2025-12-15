<?php
declare(strict_types=1);

namespace App\Application\Products\Dto;

final class ProductDto
{
    public function __construct(
        public int $id,
        public int $offerId,
        public string $name,
        public string $sku,
        public ?string $image,
        public float $price,
        public string $state,
    ) {
    }
}
