<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SSHkey extends Model
{
    protected $table = 'ssh_keys';
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
