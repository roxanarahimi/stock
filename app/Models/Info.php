<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function logs()
    {
        return $this->hasMany(InfoQuantityLog::class,'info_id','id')->orderByDesc('created_at')->take(100);
    }
}
