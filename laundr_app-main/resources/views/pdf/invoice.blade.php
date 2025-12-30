<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $transaction->id }}</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; }
        .total { text-align: right; font-weight: bold; margin-top: 20px; }
        .paid { color: green; border: 2px solid green; padding: 5px 10px; display: inline-block; transform: rotate(-10deg); float: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAUNDRY U9</h1>
        <p>Jalan Kenangan No. 99, Bandung</p>
    </div>
    
    <div class="paid">LUNAS</div>

    <p><strong>Order ID:</strong> #{{ $transaction->id }}</p>
    <p><strong>Pelanggan:</strong> {{ $transaction->customer_name }}</p>
    <p><strong>Tanggal:</strong> {{ $transaction->created_at->format('d M Y') }}</p>

    <table class="table">
        <thead>
            <tr>
                <th>Layanan</th>
                <th>Harga/Unit</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $transaction->service->name }}</td>
                <td>Rp {{ number_format($transaction->service->price) }}</td>
                <td>{{ $transaction->qty }}</td>
                <td>Rp {{ number_format($transaction->total_price) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        <p>Total Bayar: Rp {{ number_format($transaction->total_price) }}</p>
    </div>
</body>
</html>