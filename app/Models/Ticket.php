<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory;

    protected $primaryKey = 'ticket_id';

    protected $fillable = [
        'flight_id', 'status', 'purchase_date', 'e_ticket',
    ];

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class, 'flight_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
