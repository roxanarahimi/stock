<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\DateController;

class InfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => (string)$this->id,
            "depot_code" => $this->depot_code,
            "depot_name" => $this->depot_name,
            "product_code" => $this->product_code,
            "product_name" => $this->product_name,
            "unit" => $this->unit,
            "stock_control_factor" => $this->stock_control_factor,
            "physical_stock" => $this->physical_stock,
            "counted_stock" => $this->counted_stock,
            "wastage_stock" => $this->wastage_stock,
            "conflict" => $this->conflict,

            "created_at" => explode(' ',(new DateController)->toPersian($this->created_at))[0],
            "updated_at" => explode(' ',(new DateController)->toPersian($this->updated_at))[0],
        ];
    }
}
