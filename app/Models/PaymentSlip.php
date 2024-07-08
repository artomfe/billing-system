<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'billing_id',
        'url',
        'bar_code',
    ];

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }
}
