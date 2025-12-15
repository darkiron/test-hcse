<?php
declare(strict_types=1);

namespace App\Application\Products\Command;

use App\Domain\Products\Repositories\ProductWriteRepository;

final class UpdateProductHandler
{
    public function __construct(private readonly ProductWriteRepository $write)
    {
    }

    public function __invoke(UpdateProduct $cmd): void
    {
        $this->write->update($cmd->offerId, $cmd->productId, $cmd->input);
    }
}
