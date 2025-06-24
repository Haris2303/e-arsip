<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutgoingMail extends Model
{
    protected $fillable = [
        'mail_number',
        'mail_date',
        'recipient',
        'agenda_number',
        'subject',
        'priority',
        'notes',
        'file_path',
        'status',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
