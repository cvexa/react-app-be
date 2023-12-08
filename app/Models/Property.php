<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $talbe = 'properties';
    protected $guarded = [];

    public function creator()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
}
