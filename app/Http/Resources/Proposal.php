<?php

namespace App\Http\Resources;

use App\Http\Resources\Arrangement;
use App\Http\Resources\Delivery;
use App\Http\Resources\Discount;
use App\Http\Resources\Setup;
use App\Http\Resources\Vendor;
use Illuminate\Http\Resources\Json\Resource;

class Proposal extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'version' => $this->version,
            'arrangements' => Arrangement::collection($this->arrangements),
            'deliveries' => Delivery::collection($this->deliveries),
            'discount' => Discount::collection($this->deliveries),
            'setups' => Setup::collection($this->setups),
            'vendors' => Vendor::collection($this->vendors),
        ];
    }
}
