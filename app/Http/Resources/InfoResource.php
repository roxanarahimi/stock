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
            "StoreCode" => $this->depot_code,
            "StoreName" => $this->depot_name,
            "PartCode" => $this->product_code,
            "PartName" => $this->PartName,
            "Unit" => $this->Unit,
            "Factor" => $this->Factor,
            "Quantity" => $this->Quantity,
            "Counted" => $this->Counted,
            "Wastage" => $this->Wastage,
            "Conflict" => $this->Conflict,

            "created_at" => explode(' ',(new DateController)->toPersian($this->created_at))[0],
            "updated_at" => explode(' ',(new DateController)->toPersian($this->updated_at))[0],
        ];
    }
}
