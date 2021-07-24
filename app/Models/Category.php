<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Demo;
use Illuminate\Support\Facades\DB;


class Category extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];


    /**
     * Get the demos for the this category.
     */
    public function demos()
    {
        return $this->hasMany(Demo::class);
    }


    protected static function booted()
    {
        static::deleting(function ($category) {

            DB::transaction(function () use ($category){
                $demos = $category->demos;
                foreach ($demos as $demo){
                    $demo -> delete();
                }
            });
            
        });
    }
}
