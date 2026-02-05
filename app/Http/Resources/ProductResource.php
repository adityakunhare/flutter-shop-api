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
           'title' => $this->title,
           'imageURL' => url($this->image),
           'price' => $this->price,
           'sizes' => $this->whenLoaded('sizes')?->pluck('number'),
           'colors' => $this->whenLoaded('colors')?->pluck('name'),
           'category' => $this->whenLoaded('category')?->name,
           'brand' => $this->whenLoaded('brand')?->name, 
        ];
    }
}
