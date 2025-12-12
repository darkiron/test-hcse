<?php
declare(strict_types=1);

namespace App\Application\Offers\Dto;

final class OfferDto
{
    /** @var array<int, array<string, mixed>> */
    public array $products;

    /**
     * @param array<int, array<string, mixed>> $products
     */
    public function __construct(
        public int $id,
        public string $title,
        array $products = []
    ) {
        $this->products = $products;
    }
}
