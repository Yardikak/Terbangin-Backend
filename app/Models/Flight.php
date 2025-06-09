<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Flight extends Model
{
    /** @use HasFactory<\Database\Factories\FlightFactory> */
    use HasFactory;
    
    protected $primaryKey = 'flight_id';
    
    protected $fillable = [
        'airline_name', 'flight_number', 'departure', 'arrival', 'destination', 'from', 'price', 'status'
    ];
    protected $table = 'flights';

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'flight_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(History::class);
    }
}
