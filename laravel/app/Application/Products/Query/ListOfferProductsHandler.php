<?php
declare(strict_types=1);

namespace App\Application\Products\Query;

use App\Application\Products\Dto\ProductDto;
use App\Domain\Products\Repositories\ProductReadRepository;

final class ListOfferProductsHandler
{
    public function __construct(private readonly ProductReadRepository $products)
    {
    }

    /**
     * @return iterable<ProductDto>
     */
    public function __invoke(ListOfferProducts $query): iterable
    {
        return $this->products->listByOfferId($query->offerId);
    }
}
