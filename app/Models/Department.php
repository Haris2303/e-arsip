<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'description'];

    public function incoming_mails()
    {
        return $this->hasMany(IncomingMail::class);
    }

    public function outgoing_mails()
    {
        return $this->hasMany(OutgoingMail::class);
    }
}
