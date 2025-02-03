<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormRecord extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function form()
    {
        return $this->belongsTo(Form::class,'form_id','id');
    }
    public function sku()
    {
        return $this->belongsTo(Info::class,'info_id','id');
    }
}
