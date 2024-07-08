<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'governmentId', 
        'email', 
        'debtAmount', 
        'debtDueDate', 
        'debtID', 
        'status'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_INVOICE_CREATED = 'invoice_created';
    const STATUS_EMAIL_SENT = 'email_sent';
    const STATUS_DONE = 'done';

    public static function getStatusDisplayName($status)
    {
        $statuses = [
            self::STATUS_PENDING => 'Pendente',
            self::STATUS_INVOICE_CREATED => 'Boleto Gerado',
            self::STATUS_EMAIL_SENT => 'Email Enviado',
            self::STATUS_DONE => 'Finalizado',
        ];

        return $statuses[$status] ?? $status;
    }

    public function getStatusDisplayAttribute()
    {
        return self::getStatusDisplayName($this->status);
    }
}