<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function records()
    {
        return $this->hasMany(FormRecord::class,'form_id','id')->orderByDesc('id');
    }
}
