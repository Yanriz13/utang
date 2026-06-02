<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyIncome extends Model
{
    protected $fillable = [
        'month',
        'income'
    ];
}