<?php
declare(strict_types=1);

namespace App\Application\Products\Query;

final class ListOfferProducts
{
    public function __construct(public int $offerId)
    {
    }
}
