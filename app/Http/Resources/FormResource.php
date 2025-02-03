<?php

namespace App\Http\Resources;

use App\Http\Controllers\DateController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->End != null){
            $end = explode(' ',(new DateController)->toPersian($this->End))[0].' '.explode(' ',(new DateController)->toPersian($this->End))[1];
        }else{
            $end = '';
        }
        return [
            "id" => $this->id,
            "StoreCode" => $this->StoreCode,

            "Start" => explode(' ',(new DateController)->toPersian($this->Start))[0].' '.explode(' ',(new DateController)->toPersian($this->Start))[1],
            "End" => $end,

            'Records' => FormRecordResource::collection($this->records)

        ];
    }
}
