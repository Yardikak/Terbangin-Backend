<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    /** @use HasFactory<\Database\Factories\PromoFactory> */
    use HasFactory;

    protected $primaryKey = 'promo_id';

    protected $fillable = [
        'promo_code', 'description', 'discount', 'valid_until'
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'promo_id');
    }
}
