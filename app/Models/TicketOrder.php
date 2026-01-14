<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketOrder extends Model
{
    protected $fillable = ['user_id', 'event_id', 'quantity', 'order_date', 'status'];

    public function event() {
        return $this->belongsTo(Event::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
