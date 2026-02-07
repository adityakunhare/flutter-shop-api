<?php

namespace App\Http\Resources\Api\V1;

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
            'type' => 'product',
            'id' => $this->id,
            'attributes' => [
               'title' => $this->title,
               'imageURL' => url('storage/'.$this->image),
               'price' => $this->price,
               'sizes' => $this->whenLoaded('sizes',fn() => $this->sizes->pluck('number')),
               'colors' => $this->whenLoaded('colors',fn() => $this->colors->pluck('name')),
               'category' => $this->whenLoaded('category')?->name,
               'brand' => $this->whenLoaded('brand')?->name, 
            ],
        ];
    }
}
