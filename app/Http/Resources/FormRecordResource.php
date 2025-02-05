<?php

namespace App\Http\Resources;

use App\Http\Controllers\DateController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormRecordResource extends JsonResource
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
            "form_id" => $this->form_id,
            "info_id" => $this->info_id,

            "PartCode" => $this->sku?->PartCode,
            "PartName" => $this->sku?->PartName,
            "Factor" => $this->sku?->Factor,

            "Quantity" => $this->Quantity,
            "Counted" => $this->Counted,
            "Wastage" => $this->Wastage,
            "Conflict" => $this->Conflict,

            "created_at" => explode(' ',(new DateController)->toPersian($this->created_at))[0].' '.explode(' ',(new DateController)->toPersian($this->created_at))[1],

        ];
    }
}
