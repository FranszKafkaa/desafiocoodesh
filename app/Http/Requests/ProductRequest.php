<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'string|in:published,draft',
            'url' => 'url',
            'creator' => 'string',
            'created_t' => 'integer',
            'last_modified_t' => 'integer',
            'product_name' => 'string',
            'quantity' => 'string',
            'brands' => 'string',
            'categories' => 'string',
            'labels' => 'nullable|string',
            'cities' => 'nullable|string',
            'purchase_places' => 'nullable|string',
            'stores' => 'nullable|string',
            'ingredients_text' => 'nullable|string',
            'traces' => 'nullable|string',
            'serving_size' => 'nullable|string',
            'serving_quantity' => 'nullable|numeric',
            'nutriscore_score' => 'nullable|integer',
            'nutriscore_grade' => 'nullable|string|in:a,b,c,d,e',
            'main_category' => 'nullable|string',
            'image_url' => 'nullable|url',
        ];
    }
}
