<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name', 'price', 'date', 'location', 'quota']; //properti yg diwajibkan 

    public function orders() {
        return $this->hasMany(TicketOrder::class);
    }
}
