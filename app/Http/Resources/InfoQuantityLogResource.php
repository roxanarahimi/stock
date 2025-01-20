<?php

namespace App\Http\Resources;

use App\Http\Controllers\DateController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InfoQuantityLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "Quantity" => $this->Quantity,
            "created_at" => explode(' ',(new DateController)->toPersian($this->created_at))[0],
        ];
    }
}
