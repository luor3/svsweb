<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Demo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'category_id', 'input_property_json', 'input_file_json', 'output_file_json'
    ];


    protected static function booted()
    {
        static::deleting(function ($demo) 
        {
            Storage::disk('public')->deleteDirectory('demos/'.$demo->id);
        });
    }
}
