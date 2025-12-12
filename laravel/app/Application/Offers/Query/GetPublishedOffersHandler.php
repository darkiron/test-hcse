<?php
declare(strict_types=1);

namespace App\Application\Offers\Query;

use App\Application\Offers\Dto\OfferDto;
use App\Domain\Offers\Repositories\OfferReadRepository;

final class GetPublishedOffersHandler
{
    public function __construct(private readonly OfferReadRepository $offers)
    {
    }

    /**
     * @return iterable<OfferDto>
     */
    public function __invoke(GetPublishedOffers $query): iterable
    {
        return $this->offers->getPublished();
    }
}
