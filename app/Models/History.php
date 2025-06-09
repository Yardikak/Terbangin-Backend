<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class History extends Model
{
    /** @use HasFactory<\Database\Factories\HistoryFactory> */
    use HasFactory;

    // Menentukan kolom primary key
    protected $primaryKey = 'history_id';

    // Menentukan kolom yang bisa diisi (mass assignment)
    protected $fillable = [
        'user_id', 'ticket_id', 'payment_id', 'flight_date', 
        'transaction_id', 'amount', 'status'
    ];

    // Menonaktifkan timestamps jika tidak ada kolom created_at dan updated_at
    public $timestamps = false;

    // Relasi dengan User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi dengan Ticket
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    // Relasi dengan Payment
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
