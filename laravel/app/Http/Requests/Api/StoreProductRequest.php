<?php

namespace App\Http\Requests\Api;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // À sécuriser plus tard (auth/policies)
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku'],
            // Pour l’API on accepte une chaîne (chemin) plutôt qu’un upload direct
            'image' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'state' => ['required', 'in:' . implode(',', array_keys(Product::$states))],
        ];
    }
}
