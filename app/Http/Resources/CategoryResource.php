<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => (string) $this->id,
            'name'          => $this->name,
            'slug'          => $this->slug,
            'image'         => !empty($this->image) ? asset('storage/' . $this->image) : null,
            'is_active'     => $this->is_active,
            'created_at'    => $this->created_at_format,
        ];
    }
}
