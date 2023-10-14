<?php

namespace Modules\Authentication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Authentication extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return Authentication::new();
    }
}
