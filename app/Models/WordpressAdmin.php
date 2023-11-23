<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WordpressAdmin extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'wordpress_user_id',
        'username',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id', 'site_id');
    }
}
