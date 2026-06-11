<?php

namespace App\Http\Controllers;

use App\Models\MonthlyIncome;
use Illuminate\Http\Request;

class MonthlyIncomeController extends Controller
{
    public function index()
    {
        $incomes = MonthlyIncome::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('income.index', compact('incomes'));
    }

    public function create()
    {
        return view('income.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'month' => ['required', 'string', 'max:20'],
            'income' => ['required', 'numeric', 'min:0'],
        ]);

        MonthlyIncome::create([
            'user_id' => auth()->id(),
            'month' => $validated['month'],
            'income' => $validated['income'],
        ]);

        return back()->with('success', 'Pemasukan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'month' => ['required', 'string', 'max:20'],
            'income' => ['required', 'numeric', 'min:0'],
        ]);

        $income = MonthlyIncome::where('user_id', auth()->id())->findOrFail($id);

        $income->update([
            'month' => $validated['month'],
            'income' => $validated['income'],
        ]);

        return back()->with('success', 'Pemasukan berhasil diupdate');
    }

    public function destroy($id)
    {
        $income = MonthlyIncome::where('user_id', auth()->id())->findOrFail($id);
        $income->delete();

        return back()->with('success', 'Pemasukan berhasil dihapus');
    }
}