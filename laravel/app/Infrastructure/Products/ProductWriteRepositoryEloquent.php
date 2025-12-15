<?php
declare(strict_types=1);

namespace App\Infrastructure\Products;

use App\Application\Products\Dto\ProductInput;
use App\Domain\Products\Repositories\ProductWriteRepository;

final class ProductWriteRepositoryEloquent implements ProductWriteRepository
{
    public function create(int $offerId, ProductInput $input): int
    {
        if (!class_exists('App\\Models\\Product')) {
            throw new \RuntimeException('Model Product manquant');
        }

        /** @var \App\Models\Product $p */
        $p = new \App\Models\Product();
        $p->offer_id = $offerId;
        $p->name = $input->name;
        $p->sku = $input->sku;
        $p->image = $input->imagePath;
        $p->price = $input->price;
        $p->state = $input->state;
        $p->save();

        return (int) $p->id;
    }

    public function update(int $offerId, int $productId, ProductInput $input): void
    {
        $p = \App\Models\Product::query()
            ->where('offer_id', $offerId)
            ->where('id', $productId)
            ->firstOrFail();

        $p->name = $input->name;
        $p->sku = $input->sku;
        if ($input->imagePath !== null) {
            $p->image = $input->imagePath;
        }
        $p->price = $input->price;
        $p->state = $input->state;
        $p->save();
    }

    public function delete(int $offerId, int $productId): void
    {
        \App\Models\Product::query()
            ->where('offer_id', $offerId)
            ->where('id', $productId)
            ->firstOrFail()
            ->delete();
    }
}
