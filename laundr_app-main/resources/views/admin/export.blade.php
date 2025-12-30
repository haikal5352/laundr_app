<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; font-family: Arial, sans-serif; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .total-row { background-color: #ffff00; font-weight: bold; }
    </style>
</head>
<body>
    <center>
        <h2>LAPORAN KEUANGAN LAUNDRY U9</h2>
        <p>Dicetak pada Tanggal: {{ date('d F Y') }}</p>
    </center>
    <br>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Transaksi</th>
                <th>Nama Pelanggan</th>
                <th>Layanan</th>
                <th>Berat/Qty</th>
                <th>Total Harga</th>
                <th>Metode Pembayaran</th>
                <th>Status Cucian</th>
            </tr>
        </thead>
        <tbody>
            @php $totalPemasukan = 0; @endphp
            @foreach($transactions as $index => $t)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $t->customer_name }}</td>
                    <td>{{ $t->service->name ?? '-' }}</td>
                    <td>{{ $t->qty }} {{ $t->service->unit ?? '' }}</td>
                    <td>Rp {{ number_format($t->total_price, 0, ',', '.') }}</td>
                    <td>{{ strtoupper($t->payment_method) }}</td>
                    <td>
                        @if($t->status == 'pending') Pending
                        @elseif($t->status == 'process') Sedang Dicuci
                        @elseif($t->status == 'done') Selesai
                        @elseif($t->status == 'taken') Diambil
                        @else {{ $t->status }}
                        @endif
                    </td>
                </tr>
                @php $totalPemasukan += $t->total_price; @endphp
            @endforeach
            
            <tr class="total-row">
                <td colspan="5" style="text-align: right;">TOTAL PEMASUKAN BERSIH</td>
                <td colspan="3">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>