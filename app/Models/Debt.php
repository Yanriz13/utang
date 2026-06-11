<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'total_amount',
        'total_month',
        'monthly_payment',
        'start_date',
        'description',
        'status'
    ];

    public function payments()
    {
        return $this->hasMany(DebtPayment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function totalPaid()
    {
        return $this->payments()->sum('amount_paid');
    }

    public function remainingDebt()
    {
        return $this->total_amount - $this->totalPaid();
    }
}