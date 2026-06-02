<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DebtPayment extends Model
{
    protected $fillable = [
        'debt_id',
        'month_number',
        'payment_month',
        'payment_date',
        'amount_paid'
    ];

    public function debt()
    {
        return $this->belongsTo(Debt::class);
    }
}