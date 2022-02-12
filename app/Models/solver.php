<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class solver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'args'
    ];
}
