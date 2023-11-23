<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cron extends Model
{
    use HasFactory;
    protected $table = 'cron_jobs';
    protected $fillable = [
        'status', 
        'cron', 
        'frequency', 
        'user',
        'command',
        'job_id',
        'server_id',
    ];
}
