<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    protected static function booted()
    {
        static::deleting(function ($mail) {
            if ($mail->file_path && Storage::disk('public')->exists($mail->file_path)) {
                Storage::disk('public')->delete($mail->file_path);
            }
        });
    }
}
