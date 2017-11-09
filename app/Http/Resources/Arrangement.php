<?php

namespace App\Http\Resources;

use App\Http\Resources\Discount;
use App\Http\Resources\Ingredient;
use Illuminate\Http\Resources\Json\Resource;

class Arrangement extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);

        return [
            'cost' => $this->cost,
            'description' => $this->description,
            'discounts' => Discount::collection($this->discounts),
            'ingredients' => Ingredient::collection($this->ingredients),
            'name' => $this->name,
            'override_price' => $this->override_price,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price
        ];
    }
}
