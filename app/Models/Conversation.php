<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'receiver-id',
        'sender-id',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
