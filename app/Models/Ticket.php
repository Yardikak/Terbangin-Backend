<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $primaryKey = 'ticket_id';

    protected $fillable = [
        'flight_class_id', 'flight_id', 'purchase_date', 'e_ticket',
    ];

    public function flightClass(): BelongsTo
    {
        return $this->belongsTo(FlightClass::class, 'flight_class_id');
    }

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class, 'flight_id');
    }

    protected static function booted()
    {
        static::created(function ($ticket) {
            $ticket->flightClass->updateAvailableSeats();
        });

        static::deleted(function ($ticket) {
            $ticket->flightClass->updateAvailableSeats();
        });
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
