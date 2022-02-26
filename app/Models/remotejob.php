<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\job;

class remotejob extends Model
{
    use HasFactory;

    public function job()
    {
        $data = $this->hasOne(job::class, 'id', 'job_id');
        return $data;
    }
}
