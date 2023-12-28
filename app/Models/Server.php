<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'forge_id','name', 'size', 'region', 'ip_address', 'private_ip_address', 'php_version', 'server_created_at',
    ];

    public function sites()
    {
        return $this->hasMany(Site::class, 'server_id', 'forge_id');
    }
    public function databases()
    {
        return $this->hasMany(Database::class, 'server_id', 'forge_id');
    }
    public function jobs()
    {
        return $this->hasMany(Cron::class, 'server_id', 'forge_id');
    }
}
