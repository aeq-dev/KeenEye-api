<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'price' => $this->moneyFormat($this->price),
            'discount_rate' => $this->discount_rate,
            'category_id' => $this->category_id,
            'image' => $this->getFirstMediaUrl('image') ?: asset('images/product.png'),
        ];
    }

    public function moneyFormat($amount)
    {
        return '$' . number_format($amount, 2);
    }
}
