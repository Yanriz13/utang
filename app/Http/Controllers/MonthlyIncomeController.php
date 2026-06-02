<?php

namespace App\Http\Controllers;

use App\Models\MonthlyIncome;
use Illuminate\Http\Request;

class MonthlyIncomeController extends Controller
{
    public function index()
    {
        $incomes = MonthlyIncome::latest()->get();

        return view('income.index', compact('incomes'));
    }

    public function create()
    {
        return view('income.create');
    }

    public function store(Request $request)
    {
        MonthlyIncome::create([
            'month' => $request->month,
            'income' => $request->income,
        ]);

        return back()->with('success', 'Pemasukan berhasil ditambahkan');
    }
}