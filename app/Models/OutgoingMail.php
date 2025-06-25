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
        'expected_actions',
        'file_path',
        'status',
        'user_id',
        'department_id',
    ];

    protected $casts = [
        'expected_actions' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
