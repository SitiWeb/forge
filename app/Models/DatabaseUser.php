<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatabaseUser extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'created_at', 'server_id', 'table_user_id', 'password','status'];
    public function databases()
    {
        return $this->belongsToMany(Database::class, 'databaseusers_databases',  'database_user_id','database_id');
    }
}
