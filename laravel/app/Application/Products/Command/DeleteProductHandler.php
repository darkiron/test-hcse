<?php
declare(strict_types=1);

namespace App\Application\Products\Command;

use App\Domain\Products\Repositories\ProductWriteRepository;

final class DeleteProductHandler
{
    public function __construct(private readonly ProductWriteRepository $write)
    {
    }

    public function __invoke(DeleteProduct $cmd): void
    {
        $this->write->delete($cmd->offerId, $cmd->productId);
    }
}
