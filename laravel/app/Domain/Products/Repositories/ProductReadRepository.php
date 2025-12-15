<?php
declare(strict_types=1);

namespace App\Domain\Products\Repositories;

use App\Application\Products\Dto\ProductDto;

interface ProductReadRepository
{
    /**
     * @return iterable<ProductDto>
     */
    public function listByOfferId(int $offerId): iterable;

    public function findInOffer(int $offerId, int $productId): ?ProductDto;
}
