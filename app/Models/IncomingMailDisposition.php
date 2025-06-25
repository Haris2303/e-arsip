<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingMailDisposition extends Model
{
    protected $fillable = [
        'incoming_mail_id',
        'field_id',
        'instructions',
        'notes',
    ];

    protected $casts = [
        'instructions' => 'array',
    ];

    public function incomingMail()
    {
        return $this->belongsTo(IncomingMail::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
