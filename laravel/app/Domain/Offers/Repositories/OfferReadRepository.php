<?php
declare(strict_types=1);

namespace App\Domain\Offers\Repositories;

use App\Application\Offers\Dto\OfferDto;

interface OfferReadRepository
{
    /**
     * Returns published offers with their products when applicable.
     *
     * @return iterable<OfferDto>
     */
    public function getPublished(): iterable;
}
