<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Job extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user', 'configuration', 'description', 'keywords', 'content', 'status','name','category_id'

    ];
    
    protected static function booted()
    {
        static::deleting(function ($job) 
        {
            Storage::disk(env('STORAGE_OPTION', 'public'))->deleteDirectory('jobs/'.$job->id);
        });
    }
}
