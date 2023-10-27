<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Directadmin extends Model
{
    use HasFactory;
    public $timestamps = false; // Exclude the "updated_at" column
    protected $fillable = ['host', 'port', 'username', 'password' ,'user_id'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
