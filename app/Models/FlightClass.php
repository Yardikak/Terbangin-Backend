<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlightClass extends Model
{
    use HasFactory;
    protected $primaryKey = 'flight_class_id';

    protected $fillable = [
        'flight_id',
        'class',
        'seat_capacity',
        'available_seats',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'flight_class_id');
    }

    public function updateAvailableSeats()
    {
        $seatsBooked = $this->tickets->sum('quantity');
        $this->available_seats = max(0, $this->seat_capacity - $seatsBooked);
        $this->save();
    }
}
