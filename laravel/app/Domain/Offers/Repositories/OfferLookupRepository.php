<?php
declare(strict_types=1);

namespace App\Domain\Offers\Repositories;

interface OfferLookupRepository
{
    /**
     * @return array{id:int,name:string,slug?:string,state?:string}|null
     */
    public function find(int $id): ?array;
}
