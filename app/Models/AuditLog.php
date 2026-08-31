<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'action',
        'module',
        'object_id',
        'details',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
