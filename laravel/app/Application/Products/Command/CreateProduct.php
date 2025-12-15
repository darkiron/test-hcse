<?php
declare(strict_types=1);

namespace App\Application\Products\Command;

use App\Application\Products\Dto\ProductInput;

final class CreateProduct
{
    public function __construct(
        public int $offerId,
        public ProductInput $input,
    ) {
    }
}
