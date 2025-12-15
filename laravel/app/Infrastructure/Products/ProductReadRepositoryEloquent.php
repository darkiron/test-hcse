<?php
declare(strict_types=1);

namespace App\Infrastructure\Products;

use App\Application\Products\Dto\ProductDto;
use App\Domain\Products\Repositories\ProductReadRepository;

final class ProductReadRepositoryEloquent implements ProductReadRepository
{
    /**
     * @return iterable<ProductDto>
     */
    public function listByOfferId(int $offerId): iterable
    {
        if (!class_exists('App\\Models\\Offer') || !class_exists('App\\Models\\Product')) {
            return [];
        }

        /** @var \Illuminate\Database\Eloquent\Builder $q */
        $q = \App\Models\Product::query()->where('offer_id', $offerId);
        $products = $q->orderByDesc('id')->get();

        $out = [];
        foreach ($products as $p) {
            $out[] = new ProductDto(
                (int)($p->id ?? 0),
                (int)($p->offer_id ?? $offerId),
                (string)($p->name ?? ''),
                (string)($p->sku ?? ''),
                isset($p->image) ? (string)$p->image : null,
                (float)($p->price ?? 0.0),
                (string)($p->state ?? '')
            );
        }

        return $out;
    }

    public function findInOffer(int $offerId, int $productId): ?ProductDto
    {
        if (!class_exists('App\\Models\\Product')) {
            return null;
        }

        $p = \App\Models\Product::query()
            ->where('offer_id', $offerId)
            ->where('id', $productId)
            ->first();

        if (!$p) {
            return null;
        }

        return new ProductDto(
            (int)($p->id ?? 0),
            (int)($p->offer_id ?? $offerId),
            (string)($p->name ?? ''),
            (string)($p->sku ?? ''),
            isset($p->image) ? (string)$p->image : null,
            (float)($p->price ?? 0.0),
            (string)($p->state ?? '')
        );
    }
}
