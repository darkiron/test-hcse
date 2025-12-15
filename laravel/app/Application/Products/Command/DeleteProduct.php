<?php
declare(strict_types=1);

namespace App\Application\Products\Command;

final class DeleteProduct
{
    public function __construct(
        public int $offerId,
        public int $productId,
    ) {
    }
}
