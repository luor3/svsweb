<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Remotejob;
use App\Models\sshservers;

class Job extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user', 'configuration', 'description', 'keywords', 'content', 'status','name','category_id','sshserver_id'
    ];
    
    protected static function booted()
    {
        static::deleting(function ($job) 
        {
            Storage::disk(env('STORAGE_OPTION', 'public'))->deleteDirectory('jobs/'.$job->id);
        });
    }

    public function remotejob()
    {
        $data = $this->hasOne(Remotejob::class, 'job_id', 'id');
        return $data;
    }

    public function sshserver()
    {
        $data = $this->belongsToMany(sshservers::class, 'jobs_sshservers','job_id', 'sshserver_id');
        return $data;
    }

    public static function create(array $attributes = [])
    {
        $sshserver_id = $attributes['sshserver_id'];
        unset($attributes['sshserver_id']);
        $model = static::query()->create($attributes);
        $ssh_server = new jobs_sshservers();
        $ssh_server->job_id = $model->id;
        $ssh_server->sshserver_id = $sshserver_id;
        $model->save();
        $ssh_server->save();
        return $model;
    }
}
