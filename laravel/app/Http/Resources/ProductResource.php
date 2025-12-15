<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin array */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        // La ressource reçoit un tableau associatif issu du DTO (pas un modèle)
        return [
            'id' => $this['id'] ?? null,
            'name' => $this['name'] ?? '',
            'state' => $this['state'] ?? '',
        ];
    }
}
