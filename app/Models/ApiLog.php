<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_name',
        'request_payload',
        'response_code',
        'response_body',
        'origin_ip',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
