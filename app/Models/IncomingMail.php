<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class IncomingMail extends Model
{
    protected $fillable = [
        'user_id',
        'mail_number',
        'mail_date',
        'received_date',
        'sender',
        'agenda_number',
        'subject',
        'priority',
        'notes',
        'file_path',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
