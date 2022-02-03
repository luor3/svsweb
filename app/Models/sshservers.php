<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sshservers extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_name', 'host', 'port', 'username', 'password'
    ];
}
