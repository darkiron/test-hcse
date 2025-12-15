<?php

namespace App\Http\Controllers\Api;

use App\Application\Products\Command\CreateProduct;
use App\Application\Products\Command\CreateProductHandler;
use App\Application\Products\Command\DeleteProduct;
use App\Application\Products\Command\DeleteProductHandler;
use App\Application\Products\Command\UpdateProduct;
use App\Application\Products\Command\UpdateProductHandler;
use App\Application\Products\Dto\ProductInput;
use App\Application\Products\Query\ListOfferProducts;
use App\Application\Products\Query\ListOfferProductsHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProductRequest;
use App\Http\Requests\Api\UpdateProductRequest;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        private readonly ListOfferProductsHandler $listHandler,
        private readonly CreateProductHandler $createHandler,
        private readonly UpdateProductHandler $updateHandler,
        private readonly DeleteProductHandler $deleteHandler,
    ) {
    }

    public function index(int $offer): JsonResponse
    {
        $dtos = ($this->listHandler)(new ListOfferProducts($offer));

        $payload = [];
        foreach ($dtos as $dto) {
            $payload[] = [
                'id' => $dto->id,
                'offer_id' => $dto->offerId,
                'name' => $dto->name,
                'sku' => $dto->sku,
                'image' => $dto->image,
                'price' => $dto->price,
                'state' => $dto->state,
            ];
        }

        return response()->json($payload);
    }

    public function store(int $offer, StoreProductRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $input = new ProductInput(
            $validated['name'],
            $validated['sku'],
            $validated['image'] ?? null,
            (float)$validated['price'],
            $validated['state']
        );
        $id = ($this->createHandler)(new CreateProduct($offer, $input));
        return response()->json(['id' => $id], 201);
    }

    public function update(int $offer, int $product, UpdateProductRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $input = new ProductInput(
            $validated['name'],
            $validated['sku'],
            $validated['image'] ?? null,
            (float)$validated['price'],
            $validated['state']
        );
        ($this->updateHandler)(new UpdateProduct($offer, $product, $input));
        return response()->json(['updated' => true]);
    }

    public function destroy(int $offer, int $product): JsonResponse
    {
        ($this->deleteHandler)(new DeleteProduct($offer, $product));
        return response()->json(null, 204);
    }
}
