<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'ticket_id', 'quantity', 'total_price', 'promo_id', 'payment_status'
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function promo(): BelongsTo
    {
        return $this->belongsTo(Promo::class, 'promo_id');
    }
}
