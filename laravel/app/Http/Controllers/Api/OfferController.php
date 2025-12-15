<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Application\Offers\Query\GetPublishedOffers;
use App\Application\Offers\Query\GetPublishedOffersHandler;
use App\Http\Resources\OfferResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Offer;

class OfferController extends Controller
{
    public function __construct(private readonly GetPublishedOffersHandler $getPublishedOffers)
    {
    }

    public function index(): JsonResponse
    {
        $dtos = ($this->getPublishedOffers)(new GetPublishedOffers());

        // Retourner un tableau JSON simple (compatibilité avec le test existant)
        $payload = [];
        foreach ($dtos as $dto) {
            $products = [];
            foreach ($dto->products as $p) {
                $products[] = [
                    'id' => $p['id'] ?? null,
                    'name' => $p['name'] ?? '',
                    'state' => $p['state'] ?? '',
                ];
            }

            $payload[] = [
                'id' => $dto->id,
                'name' => $dto->title,
                'state' => $dto->state,
                'products' => $products,
            ];
        }

        return response()->json($payload);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'state' => ['required', 'in:draft,published,hidden'],
        ]);

        $slugBase = Str::slug($data['name']);
        $slug = $slugBase;
        $i = 1;
        while (Offer::query()->where('slug', $slug)->exists()) {
            $slug = $slugBase.'-'.(++$i);
        }

        $offer = Offer::create([
            'name' => $data['name'],
            'slug' => $slug,
            'state' => $data['state'],
        ]);

        return response()->json([
            'id' => $offer->id,
            'name' => $offer->name,
            'state' => $offer->state,
            'slug' => $offer->slug,
        ], 201);
    }
}
