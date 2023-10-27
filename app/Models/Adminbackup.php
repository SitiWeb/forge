<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adminbackup extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'backup_id', 'start', 'time', 'archive', 'barchive', 'repository_id'];
    public function repository()
    {
        return $this->belongsTo(Repository::class, 'repository_id','id');
    }
}
