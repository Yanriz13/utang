<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            background: #4f46e5;
            color: white;
            font-weight: bold;
            text-align: center;
            border: 1px solid #ddd;
            padding: 10px;
        }

        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
        }

        .success {
            color: green;
            font-weight: bold;
        }

        .danger {
            color: red;
            font-weight: bold;
        }

        .section {
            background: #f3f4f6;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <table>
        <tr>
            <td colspan="4" class="title">
                LAPORAN KEUANGAN & UTANG
            </td>
        </tr>
    </table>

    <br><br>

    {{-- SUMMARY --}}
    <table>

        <tr>
            <th>Total Pemasukan</th>
            <th>Total Cicilan / Bulan</th>
            <th>Sisa Uang</th>
        </tr>

        <tr>
            <td>
                Rp {{ number_format($monthlyIncome) }}
            </td>

            <td class="danger">
                Rp {{ number_format($monthlyDebt) }}
            </td>

            <td class="success">
                Rp {{ number_format($remainingMoney) }}
            </td>
        </tr>

    </table>

    <br><br>

    {{-- REKAP BULANAN --}}
    <table>

        <tr>
            <td colspan="4" class="section">
                REKAP GAJI BULANAN
            </td>
        </tr>

        <tr>
            <th>Bulan</th>
            <th>Gaji</th>
            <th>Total Bayar</th>
            <th>Sisa</th>
        </tr>

        @foreach($monthlyReports as $report)

        <tr>

            <td>
                {{ $report['month'] }}
            </td>

            <td>
                Rp {{ number_format($report['income']) }}
            </td>

            <td class="danger">
                Rp {{ number_format($report['payment']) }}
            </td>

            <td class="success">
                Rp {{ number_format($report['remaining']) }}
            </td>

        </tr>

        @endforeach

    </table>

    <br><br>

    {{-- DATA UTANG --}}
    <table>

        <tr>
            <td colspan="6" class="section">
                DATA UTANG
            </td>
        </tr>

        <tr>
            <th>Nama Utang</th>
            <th>Total Utang</th>
            <th>Cicilan</th>
            <th>Sisa</th>
            <th>Status</th>
            <th>Riwayat Pembayaran</th>
        </tr>

        @foreach($debts as $debt)

        <tr>

            <td>
                {{ $debt->title }}
            </td>

            <td>
                Rp {{ number_format($debt->total_amount) }}
            </td>

            <td>
                Rp {{ number_format($debt->monthly_payment) }}
            </td>

            <td class="danger">
                Rp {{ number_format($debt->remainingDebt()) }}
            </td>

            <td>
                {{ $debt->status }}
            </td>

            <td>

                @foreach($debt->payments as $pay)

                    Cicilan Ke {{ $pay->month_number }}
                    ({{ $pay->payment_month }})
                    :
                    Rp {{ number_format($pay->amount_paid) }}

                    <br>

                @endforeach

            </td>

        </tr>

        @endforeach

    </table>

</body>
</html>