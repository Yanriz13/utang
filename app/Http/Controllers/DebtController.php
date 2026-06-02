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
        $debts = Debt::with('payments')->latest()->get();

        $monthlyIncome = MonthlyIncome::sum('income');

        $monthlyDebt = Debt::sum('monthly_payment');

        $remainingMoney = $monthlyIncome - $monthlyDebt;
$monthlyReports = MonthlyIncome::latest()
    ->get()
    ->map(function ($income) {

       $payments = \App\Models\DebtPayment::where(
    'payment_month',
    (string) $income->month
)->get();

        $totalPayment = $payments->sum('amount_paid');

        return [
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
        Debt::create([
            'title' => $request->title,
            'total_amount' => $request->total_amount,
            'total_month' => $request->total_month,
            'monthly_payment' =>
                $request->total_amount / $request->total_month,
            'start_date' => $request->start_date,
            'description' => $request->description,
        ]);

        return redirect('/')->with('success', 'Utang berhasil dibuat');
    }
    public function destroy($id)
{
    $debt = Debt::findOrFail($id);

    $debt->delete();

    return back()->with('success', 'Data utang berhasil dihapus');
}
public function pay(Request $request, $id)
{
    $debt = Debt::findOrFail($id);

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
    $debts = Debt::with('payments')->get();

    $monthlyIncome = MonthlyIncome::sum('income');
    $monthlyDebt = Debt::sum('monthly_payment');
    $remainingMoney = $monthlyIncome - $monthlyDebt;

    $monthlyReports = MonthlyIncome::all()->map(function ($item) use ($monthlyDebt) {
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
    $payment = DebtPayment::findOrFail($id);

    $payment->delete();

    return back()->with('success', 'Cicilan berhasil dihapus');
}
public function storeMultiple(Request $request)
{
    foreach ($request->debts as $item)
    {
        Debt::create([
            'title' => $item['title'],
            'total_amount' => $item['total_amount'],
            'monthly_payment' => $item['monthly_payment'],
            'status' => 'Belum Lunas'
        ]);
    }

    return redirect('/')->with('success', 'Berhasil tambah banyak utang');
}
public function bulkDelete(Request $request)
{
    if ($request->debt_ids)
    {
        Debt::whereIn('id', $request->debt_ids)->delete();
    }

    return back()->with('success', 'Utang terpilih berhasil dihapus');
}

public function bulkDeletePayments(Request $request)
{
    if ($request->payment_ids)
    {
        DebtPayment::whereIn('id', $request->payment_ids)->delete();
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