<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = [
        'site_id',
        'server_id',
        'user_id',
        'name',
        'username',
        'type',
        'is_secured',
        'aliases',
        'php_version',
        'directory',
    ];

    public function server()
    {
        return $this->belongsTo(Server::class, 'server_id', 'forge_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function wordpressAdmins()
    {
        return $this->hasMany(WordpressAdmin::class, 'site_id', 'site_id');
    }
 
}
