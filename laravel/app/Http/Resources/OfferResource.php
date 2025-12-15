<?php

namespace App\Http\Resources;

use App\Application\Offers\Dto\OfferDto;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin OfferDto */
class OfferResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        /** @var OfferDto $dto */
        $dto = $this->resource;

        return [
            'id' => $dto->id,
            'name' => $dto->title, // le DTO stocke le nom dans "title" pour compat compat
            'state' => $dto->state,
            'products' => ProductResource::collection(collect($dto->products)),
        ];
    }
}
