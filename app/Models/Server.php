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
}
