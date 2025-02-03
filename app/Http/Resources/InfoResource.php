<?php

namespace App\Http\Resources;

use App\Models\InfoQuantityLog;
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
            "id" => $this->id,
            "StoreCode" => $this->StoreCode,
            "StoreName" => $this->StoreName,
            "PartCode" => $this->PartCode,
            "PartName" => $this->PartName,
            "Unit" => $this->Unit,
            "Factor" => $this->Factor,
            "Quantity" => $this->Quantity,

            "created_at" => explode(' ',(new DateController)->toPersian($this->created_at))[0],
            "updated_at" => explode(' ',(new DateController)->toPersian($this->updated_at))[0],
        ];
    }
}
