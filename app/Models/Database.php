<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Database extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'table_id', 'user_id', 'table_user_id', 'server_id', 'status'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function table_user()
    {
        return $this->belongsTo(DatabaseUser::class, 'table_user_id', 'table_user_id');
    }
    public function users()
    {
        return $this->belongsToMany(DatabaseUser::class, 'databaseusers_databases','database_id', 'table_id');
    }
}
