<?php
declare(strict_types=1);

namespace App\Application\Products\Command;

use App\Application\Products\Dto\ProductInput;
use App\Domain\Products\Repositories\ProductWriteRepository;

final class CreateProductHandler
{
    public function __construct(private readonly ProductWriteRepository $write)
    {
    }

    public function __invoke(CreateProduct $cmd): int
    {
        return $this->write->create($cmd->offerId, $cmd->input);
    }
}
