<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;


class test extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $dates = ['deleted_at'];




    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
