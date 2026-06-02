@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-5 px-4 py-6">

    {{-- TOP BAR --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-lg font-semibold text-slate-800">Dashboard</h1>
            <p class="text-sm text-slate-500">Rekap keuangan bulanan</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="/export-excel"
               class="inline-flex items-center gap-2 bg-teal-700 hover:bg-teal-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                <i class="ti ti-table-export text-base"></i>
                Export Excel
            </a>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-1">Total Pemasukan</p>
            <p class="text-2xl font-semibold text-slate-800">Rp {{ number_format($monthlyIncome) }}</p>
        </div>
        <div class="bg-red-50 border border-red-100 rounded-xl p-4">
            <p class="text-xs font-medium text-red-400 uppercase tracking-wide mb-1">Total Cicilan / Bulan</p>
            <p class="text-2xl font-semibold text-red-600">Rp {{ number_format($monthlyDebt) }}</p>
        </div>
        <div class="bg-green-50 border border-green-100 rounded-xl p-4">
            <p class="text-xs font-medium text-green-500 uppercase tracking-wide mb-1">Sisa Uang</p>
            <p class="text-2xl font-semibold text-green-700">Rp {{ number_format($remainingMoney) }}</p>
        </div>
    </div>

    {{-- INPUT PEMASUKAN --}}
    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-slate-800">Input Pemasukan Bulanan</p>
                <p class="text-xs text-slate-500 mt-0.5">Tambahkan total penghasilan per bulan</p>
            </div>
            <form action="/monthly-income" method="POST"
                  class="flex flex-wrap gap-2 items-center"
                  onsubmit="syncRupiahFields(this)">
                @csrf
                <input type="month" name="month" required
                    class="border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">

                {{-- Input tampilan (rupiah) --}}
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400 pointer-events-none">Rp</span>
                    <input type="text"
                        data-rupiah
                        placeholder="0"
                        class="border border-slate-300 rounded-lg pl-8 pr-3 py-1.5 text-sm w-48
                               focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                {{-- Hidden field yang dikirim ke server --}}
                <input type="hidden" name="income">

                <button type="submit"
                    class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white
                           text-sm font-medium px-4 py-1.5 rounded-lg transition">
                    <i class="ti ti-device-floppy text-base"></i>
                    Simpan
                </button>
            </form>
        </div>
    </div>

    {{-- REKAP GAJI BULANAN --}}
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <p class="text-sm font-semibold text-slate-800">Rekap Gaji Bulanan</p>
            <p class="text-xs text-slate-500 mt-0.5">Sisa uang setelah pembayaran utang</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left text-xs font-medium text-slate-500 uppercase tracking-wide px-5 py-3">Bulan</th>
                        <th class="text-right text-xs font-medium text-slate-500 uppercase tracking-wide px-5 py-3">Gaji</th>
                        <th class="text-right text-xs font-medium text-slate-500 uppercase tracking-wide px-5 py-3">Total Bayar</th>
                        <th class="text-right text-xs font-medium text-slate-500 uppercase tracking-wide px-5 py-3">Sisa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($monthlyReports as $report)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $report['month'] }}</td>
                        <td class="px-5 py-3 text-right text-slate-600">Rp {{ number_format($report['income']) }}</td>
                        <td class="px-5 py-3 text-right font-medium text-red-500">Rp {{ number_format($report['payment']) }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-green-700">Rp {{ number_format($report['remaining']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- DAFTAR UTANG --}}
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold text-slate-800">Daftar Utang</p>
            <p class="text-xs text-slate-500 mt-0.5">{{ $debts->count() }} utang terdaftar</p>
        </div>
        <a href="/debts/create"
           class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white
                  text-sm font-medium px-4 py-2 rounded-lg transition">
            <i class="ti ti-plus text-base"></i>
            Tambah Utang
        </a>
    </div>

    {{-- TABEL UTANG --}}
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left text-xs font-medium text-slate-500 uppercase tracking-wide px-5 py-3">Nama Utang</th>
                        <th class="text-right text-xs font-medium text-slate-500 uppercase tracking-wide px-5 py-3">Total</th>
                        <th class="text-right text-xs font-medium text-slate-500 uppercase tracking-wide px-5 py-3">Cicilan/Bln</th>
                        <th class="text-right text-xs font-medium text-slate-500 uppercase tracking-wide px-5 py-3">Sisa</th>
                        <th class="text-center text-xs font-medium text-slate-500 uppercase tracking-wide px-5 py-3">Status</th>
                        <th class="text-center text-xs font-medium text-slate-500 uppercase tracking-wide px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($debts as $debt)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $debt->title }}</td>
                        <td class="px-5 py-3 text-right text-slate-600">Rp {{ number_format($debt->total_amount) }}</td>
                        <td class="px-5 py-3 text-right text-slate-600">Rp {{ number_format($debt->monthly_payment) }}</td>
                        <td class="px-5 py-3 text-right font-medium text-slate-800">Rp {{ number_format($debt->remainingDebt()) }}</td>
                        <td class="px-5 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $debt->status === 'lunas'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-blue-100 text-blue-700' }}">
                                {{ $debt->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button"
                                    onclick="toggleForm('form-{{ $debt->id }}')"
                                    class="inline-flex items-center gap-1 bg-green-50 hover:bg-green-100 text-green-700
                                           border border-green-200 text-xs font-medium px-2.5 py-1.5 rounded-lg transition">
                                    <i class="ti ti-cash text-sm"></i> Bayar
                                </button>
                                <form action="/debts/{{ $debt->id }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus utang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 bg-red-50 hover:bg-red-100 text-red-600
                                               border border-red-200 text-xs font-medium px-2.5 py-1.5 rounded-lg transition">
                                        <i class="ti ti-trash text-sm"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- FORM BAYAR (inline, collapsible) --}}
                    <tr id="form-{{ $debt->id }}" class="hidden bg-indigo-50">
                        <td colspan="6" class="px-5 py-3">
                            <form action="/debts/{{ $debt->id }}/pay" method="POST"
                                  class="flex flex-wrap items-center gap-3"
                                  onsubmit="syncRupiahFields(this)">
                                @csrf
                                <span class="text-xs font-medium text-indigo-700">
                                    Bayar cicilan — <strong>{{ $debt->title }}</strong>
                                </span>
                                <input type="month" name="payment_month" required
                                    class="border border-slate-300 rounded-lg px-3 py-1.5 text-sm
                                           focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white">
                                <input type="number" name="month_number" placeholder="Cicilan ke-" required min="1"
                                    class="border border-slate-300 rounded-lg px-3 py-1.5 text-sm w-32
                                           focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white">



                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700
                                           text-white text-sm font-medium px-4 py-1.5 rounded-lg transition">
                                    <i class="ti ti-check text-base"></i> Konfirmasi Bayar
                                </button>
                                <button type="button" onclick="toggleForm('form-{{ $debt->id }}')"
                                    class="text-xs text-slate-500 hover:text-slate-700 underline">
                                    Batal
                                </button>
                            </form>
                        </td>
                    </tr>

                    {{-- CICILAN YANG SUDAH DIBAYAR --}}
                    @if($debt->payments->count())
                    <tr class="bg-slate-50">
                        <td colspan="6" class="px-5 py-3">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">
                                Cicilan yang sudah dibayar
                            </p>
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="text-slate-400">
                                            <th class="text-left font-medium pb-1.5 pr-4">Cicilan ke-</th>
                                            <th class="text-left font-medium pb-1.5 pr-4">Bulan</th>
                                            <th class="text-right font-medium pb-1.5 pr-4">Jumlah Dibayar</th>
                                            <th class="text-center font-medium pb-1.5">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200">
                                        @foreach($debt->payments as $pay)
                                        <tr>
                                            <td class="py-1.5 pr-4 font-medium text-slate-700">{{ $pay->month_number }}</td>
                                            <td class="py-1.5 pr-4 text-slate-500">{{ $pay->payment_month }}</td>
                                            <td class="py-1.5 pr-4 text-right font-semibold text-green-700">
                                                Rp {{ number_format($pay->amount_paid) }}
                                            </td>
                                            <td class="py-1.5 text-center">
                                                <form action="/payments/{{ $pay->id }}" method="POST"
                                                      onsubmit="return confirm('Hapus cicilan ini?')"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center w-6 h-6 rounded-md
                                                               text-slate-400 hover:bg-red-100 hover:text-red-500
                                                               border border-slate-200 transition">
                                                        <i class="ti ti-trash text-xs"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    @endif

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
/**
 * Format angka ke string ribuan: 1500000 → "1.500.000"
 */
function formatRibuan(val) {
    const angka = val.replace(/\D/g, '');
    if (!angka) return '';
    return parseInt(angka, 10).toLocaleString('id-ID');
}

/**
 * Ambil nilai murni (angka) dari string berformat: "1.500.000" → "1500000"
 */
function getRawAngka(val) {
    return val.replace(/\D/g, '');
}

/**
 * Pasang event listener ke semua input [data-rupiah]
 * Setiap input tampilan dipasangkan ke hidden field berikutnya (next sibling input[type=hidden])
 */
function initRupiahInputs() {
    document.querySelectorAll('input[data-rupiah]').forEach(function (input) {
        // Format saat user mengetik
        input.addEventListener('input', function () {
            const raw    = getRawAngka(this.value);
            const cursor = this.selectionStart;
            const before = this.value.substring(0, cursor).replace(/\D/g, '').length;

            this.value = raw ? parseInt(raw, 10).toLocaleString('id-ID') : '';

            // Kembalikan posisi kursor secara proporsional
            let count = 0;
            let newPos = 0;
            for (let i = 0; i < this.value.length; i++) {
                if (/\d/.test(this.value[i])) count++;
                if (count === before) { newPos = i + 1; break; }
            }
            this.setSelectionRange(newPos, newPos);
        });

        // Tolak karakter non-angka saat paste
        input.addEventListener('paste', function (e) {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text');
            const angka = text.replace(/\D/g, '');
            if (angka) {
                this.value = parseInt(angka, 10).toLocaleString('id-ID');
                this.dispatchEvent(new Event('input'));
            }
        });

        // Tolak huruf, hanya izinkan: angka, backspace, delete, arrow, tab
        input.addEventListener('keydown', function (e) {
            const allow = [
                'Backspace','Delete','ArrowLeft','ArrowRight',
                'ArrowUp','ArrowDown','Tab','Home','End'
            ];
            if (!allow.includes(e.key) && !/^\d$/.test(e.key)) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Sebelum form di-submit, sync nilai murni dari [data-rupiah]
 * ke hidden field yang namanya sudah didefinisikan (name="income" / name="amount_paid")
 *
 * Cara kerja: cari semua [data-rupiah] dalam form, lalu cari hidden field
 * yang merupakan sibling berikutnya setelah wrapper div-nya.
 */
function syncRupiahFields(form) {
    form.querySelectorAll('input[data-rupiah]').forEach(function (display) {
        const raw    = getRawAngka(display.value);
        // Hidden field ada tepat setelah wrapper div (parent dari display input)
        const wrapper = display.closest('div[class*="relative"]') || display.parentElement;
        const hidden  = wrapper.nextElementSibling;
        if (hidden && hidden.type === 'hidden') {
            hidden.value = raw;
        }
    });
    return true; // biarkan form lanjut submit
}

// Init saat DOM siap
document.addEventListener('DOMContentLoaded', initRupiahInputs);
// Init ulang jika ada konten dinamis (toggle form bayar)
document.addEventListener('click', function (e) {
    if (e.target.matches('[onclick*="toggleForm"]') || e.target.closest('[onclick*="toggleForm"]')) {
        setTimeout(initRupiahInputs, 50);
    }
});

function toggleForm(id) {
    const row = document.getElementById(id);
    row.classList.toggle('hidden');
    // Re-init supaya input rupiah di dalam form yang baru muncul langsung aktif
    setTimeout(initRupiahInputs, 10);
}
</script>
@endsection