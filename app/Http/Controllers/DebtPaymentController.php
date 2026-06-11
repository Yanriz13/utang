<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\DebtPayment;
use Illuminate\Http\Request;

class DebtPaymentController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'payment_month' => 'required',
            'month_number' => 'required',
        ]);

        $debt = Debt::where('user_id', auth()->id())->findOrFail($id);

        $exists = DebtPayment::where('debt_id', $id)
            ->where('month_number', $request->month_number)
            ->exists();

        if ($exists) {

            return back()->with(
                'success',
                'Cicilan bulan tersebut sudah dibayar'
            );
        }

      $payment = new DebtPayment();

$payment->debt_id = $id;
$payment->month_number = $request->month_number;
$payment->payment_month = $request->payment_month;
$payment->payment_date = now();
$payment->amount_paid = $debt->monthly_payment;

$payment->save();

// dd($payment);

        $paidCount = DebtPayment::where(
            'debt_id',
            $id
        )->count();

        if ($paidCount >= $debt->total_month) {

            $debt->update([
                'status' => 'lunas'
            ]);
        }

        return back()->with(
            'success',
            'Pembayaran berhasil'
        );
    }

    public function destroy($id)
    {
        $payment = DebtPayment::whereHas('debt', function ($query) {
            $query->where('user_id', auth()->id());
        })->findOrFail($id);

        $debt = $payment->debt;

        $payment->delete();

        if ($debt->payments()->count() < $debt->total_month) {

            $debt->update([
                'status' => 'belum_lunas'
            ]);
        }

        return back()->with(
            'success',
            'Pembayaran berhasil dihapus'
        );
    }
}