<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => (string) $this->id,
            'name'                  => $this->name,
            'slug'                  => $this->slug,
            'description'           => $this->description,
            'price'                 => $this->price,
            'image'                 => $this->image_path,
            'price_with_currency'   => $this->price_with_currency,
            'is_active'             => $this->is_active,
            'created_at'            => $this->created_at_format,
            'categories'            => $this->whenLoaded('categories', CategoryResource::collection($this->categories)),
        ];
    }
}
