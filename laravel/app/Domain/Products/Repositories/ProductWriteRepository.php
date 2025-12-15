<?php
declare(strict_types=1);

namespace App\Domain\Products\Repositories;

use App\Application\Products\Dto\ProductInput;

interface ProductWriteRepository
{
    public function create(int $offerId, ProductInput $input): int;
    public function update(int $offerId, int $productId, ProductInput $input): void;
    public function delete(int $offerId, int $productId): void;
}
