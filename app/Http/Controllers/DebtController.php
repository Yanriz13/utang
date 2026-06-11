<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use App\Models\Debt;
use App\Models\MonthlyIncome;
use Illuminate\Http\Request;
use App\Models\DebtPayment;

class DebtController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $debts = Debt::with('payments')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $monthlyIncome = MonthlyIncome::where('user_id', $userId)->sum('income');

        $monthlyDebt = Debt::where('user_id', $userId)->sum('monthly_payment');

        $remainingMoney = $monthlyIncome - $monthlyDebt;
$monthlyReports = MonthlyIncome::where('user_id', $userId)
    ->latest()
    ->get()
    ->map(function ($income) {

       $payments = DebtPayment::where('payment_month', (string) $income->month)
        ->whereHas('debt', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->get();

        $totalPayment = $payments->sum('amount_paid');

        return [
            'id' => $income->id,
            'month' => $income->month,
            'income' => $income->income,
            'payment' => $totalPayment,
            'remaining' => $income->income - $totalPayment,
            'total_transaction' => $payments->count(),
        ];
    });
        return view('debts.index', compact(
          'debts',
    'monthlyIncome',
    'monthlyDebt',
    'remainingMoney',
    'monthlyReports'
        ));
    }

    public function create()
    {
        return view('debts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'numeric', 'min:1'],
            'total_month' => ['required', 'integer', 'min:1'],
            'start_date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        Debt::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'total_amount' => $validated['total_amount'],
            'total_month' => $validated['total_month'],
            'monthly_payment' =>
                $validated['total_amount'] / $validated['total_month'],
            'start_date' => $validated['start_date'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect('/')->with('success', 'Utang berhasil dibuat');
    }
    public function destroy($id)
{
        $debt = Debt::where('user_id', auth()->id())->findOrFail($id);

    $debt->delete();

    return back()->with('success', 'Data utang berhasil dihapus');
}
public function pay(Request $request, $id)
{
    $debt = Debt::where('user_id', auth()->id())->findOrFail($id);

    foreach ($request->payments as $payment)
    {
        DebtPayment::create([
            'debt_id' => $debt->id,
            'month_number' => $payment['month_number'],
            'payment_month' => $payment['payment_month'],
            'amount_paid' => $debt->monthly_payment,
        ]);
    }

    return back()->with('success', 'Cicilan berhasil ditambahkan');
}
public function exportExcel()
{
    $userId = auth()->id();

    $debts = Debt::with('payments')->where('user_id', $userId)->get();

    $monthlyIncome = MonthlyIncome::where('user_id', $userId)->sum('income');
    $monthlyDebt = Debt::where('user_id', $userId)->sum('monthly_payment');
    $remainingMoney = $monthlyIncome - $monthlyDebt;

    $monthlyReports = MonthlyIncome::where('user_id', $userId)
    ->get()
    ->map(function ($item) use ($monthlyDebt) {
        return [
            'month' => $item->month,
            'income' => $item->income,
            'payment' => $monthlyDebt,
            'remaining' => $item->income - $monthlyDebt,
        ];
    });

    return response()->view('export.excel', compact(
        'debts',
        'monthlyIncome',
        'monthlyDebt',
        'remainingMoney',
        'monthlyReports'
    ))
    ->header('Content-Type', 'application/vnd.ms-excel')
    ->header('Content-Disposition', 'attachment; filename="laporan-keuangan.xls"');
}
public function deletePayment($id)
{
    $payment = DebtPayment::whereHas('debt', function ($query) {
        $query->where('user_id', auth()->id());
    })->findOrFail($id);

    $payment->delete();

    return back()->with('success', 'Cicilan berhasil dihapus');
}
public function storeMultiple(Request $request)
{
    foreach ($request->debts as $item)
    {
        Debt::create([
            'user_id' => auth()->id(),
            'title' => $item['title'],
            'total_amount' => $item['total_amount'],
            'monthly_payment' => $item['monthly_payment'],
            'status' => 'belum_lunas'
        ]);
    }

    return redirect('/')->with('success', 'Berhasil tambah banyak utang');
}
public function bulkDelete(Request $request)
{
    if ($request->debt_ids)
    {
        Debt::where('user_id', auth()->id())
            ->whereIn('id', $request->debt_ids)
            ->delete();
    }

    return back()->with('success', 'Utang terpilih berhasil dihapus');
}

public function bulkDeletePayments(Request $request)
{
    if ($request->payment_ids)
    {
        DebtPayment::whereHas('debt', function ($query) {
            $query->where('user_id', auth()->id());
        })->whereIn('id', $request->payment_ids)->delete();
    }

    return back()->with('success', 'Cicilan terpilih berhasil dihapus');
}
public function downloadTemplate()
{
    $filename = 'template-utang.csv';

    $headers = [
        'Content-Type' => 'text/csv',
    ];

    $callback = function () {

        $file = fopen('php://output', 'w');

        fputcsv($file, [
            'title',
            'total_amount',
            'monthly_payment',
            'status'
        ]);

        fputcsv($file, [
            'Motor',
            '12000000',
            '1000000',
            'belum lunas'
        ]);

        fputcsv($file, [
            'Laptop',
            '8000000',
            '500000',
            'belum lunas'
        ]);

        fclose($file);
    };

    return Response::stream($callback, 200, [
        'Content-Disposition' => "attachment; filename={$filename}",
        'Content-Type' => 'text/csv',
    ]);
}
public function importExcel(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:csv,txt'
    ]);

    $file = fopen($request->file('file')->getRealPath(), 'r');

    $header = fgetcsv($file);

    while (($row = fgetcsv($file)) !== false)
    {
        Debt::create([
            'user_id' => auth()->id(),
            'title' => $row[0],
            'total_amount' => $row[1],
            'monthly_payment' => $row[2],
            'status' => $row[3],
        ]);
    }

    fclose($file);

    return back()->with('success', 'Data berhasil diimport');
}
}