<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracking Order - {{ config('app.name') }}</title>
    
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-900 font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0 pb-10">
        <div class="w-full max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            
            <div class="mb-6 text-center">
                <h1 class="text-2xl font-bold text-indigo-600">Lacak Pesanan</h1>
                <p class="text-gray-600 text-sm mt-2">Masukkan ID Transaksi untuk cek status & pembayaran.</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('tracking') }}" method="GET" class="mb-6">
                <div class="flex gap-2">
                    <input type="text" name="id" placeholder="Contoh ID: 1"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition"
                        value="{{ request('id') }}">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-bold">Cek</button>
                    <a href="{{ route('home') }}"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition font-bold flex items-center">Kembali</a>
                </div>
            </form>

            @if(isset($transaction))
                <div class="border-t border-gray-200 pt-6 animate-fade-in-up">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-bold text-lg text-gray-800">Order #{{ $transaction->id }}</span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                {{ $transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $transaction->status === 'process' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $transaction->status === 'done' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $transaction->status === 'taken' ? 'bg-gray-100 text-gray-800' : '' }}
                            ">
                            @if($transaction->status == 'pending') 🕒 Pending
                            @elseif($transaction->status == 'process') 🧼 Dicuci
                            @elseif($transaction->status == 'done') ✅ Selesai
                            @else 👋 Diambil @endif
                        </span>
                    </div>

                    <div class="space-y-3 text-sm border-b border-gray-100 pb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Pelanggan</span>
                            <span class="font-bold text-gray-800">{{ $transaction->customer_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Layanan</span>
                            <span class="font-bold text-gray-800">{{ $transaction->service->name }} ({{ $transaction->qty }} Unit)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tipe</span>
                            <span class="font-medium bg-gray-100 px-2 rounded text-gray-600 text-xs py-0.5">
                                {{ $transaction->type == 'pickup_delivery' ? 'Antar Jemput 🚚' : 'Datang Sendiri 🏢' }}
                            </span>
                        </div>
                        @if($transaction->address)
                            <div class="flex flex-col items-end mt-1">
                                <span class="text-xs text-gray-400 mb-1">Alamat Penjemputan:</span>
                                <span class="font-medium text-right text-gray-700 bg-gray-50 p-2 rounded w-full">{{ $transaction->address }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between border-t border-dashed pt-3 mt-2 items-center">
                            <span class="text-gray-600 font-bold">Total Tagihan</span>
                            <span class="font-black text-xl text-indigo-600">Rp {{ number_format($transaction->total_price) }}</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        
                        @if($transaction->payment_method != 'cash' && $transaction->status == 'pending')
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                                <h3 class="text-xs font-bold text-blue-800 mb-3 text-center uppercase tracking-wide">Info Pembayaran</h3>
                                
                                @if($transaction->payment_method == 'qris')
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600">Silakan klik tombol bayar di bawah untuk Scan QRIS</p>
                                    </div>
                                @elseif($transaction->payment_method == 'transfer')
                                     <div class="text-center">
                                        <p class="text-sm text-gray-600">Silakan klik tombol bayar di bawah untuk Virtual Account</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="pt-2">
                            @if($transaction->payment_status == '1')
                                <div class="text-center">
                                    <button type="button" id="pay-button" class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2">
                                        <span>Bayar Sekarang</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </button>
                                @if($transaction->status == 'pending' && $transaction->payment_status == '1')
    <form action="{{ route('transaction.cancel', $transaction->id) }}" method="POST" onsubmit="return confirm('Yakin mau batalkan pesanan ini?');" class="mt-2">
        @csrf
        <button type="submit" class="w-full bg-red-100 text-red-600 font-bold py-2 px-4 rounded-lg hover:bg-red-200 transition text-sm">
            ❌ Batalkan Pesanan
        </button>
    </form>
@endif
                                    <p class="text-[10px] text-gray-400 mt-3">Mendukung GoPay, ShopeePay, BCA VA, dll.</p>
                                </div>

                            @elseif($transaction->payment_status == '2')
                                <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2 text-green-600 text-xl">
                                        ✓
                                    </div>
                                    <p class="text-green-800 font-bold text-lg">Pembayaran Lunas</p>
                                     <a href="{{ route('transaction.print', $transaction->id) }}" class="mt-4 inline-block bg-gray-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-900 transition flex items-center justify-center gap-2">
    📄 Download Invoice PDF
</a>
                                    <p class="text-xs text-green-600">Terima kasih telah melakukan pembayaran.</p>
                                </div>
                            @else
                                <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
                                    <p class="text-red-600 font-bold">Pembayaran Kadaluarsa</p>
                                    <p class="text-xs text-red-500">Silakan buat pesanan ulang.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @elseif(request('id'))
                <div class="text-center py-8">
                    <div class="text-6xl mb-4">🔍</div>
                    <p class="text-gray-800 font-bold">Pesanan tidak ditemukan.</p>
                    <p class="text-sm text-gray-500 mt-1">Mohon periksa kembali ID Transaksi Anda.</p>
                </div>
            @endif
        </div>
        
        <p class="text-center text-gray-400 text-xs mt-8">&copy; {{ date('Y') }} Laundry U9 System</p>
    </div>

    @if(isset($transaction))
    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        
        if (payButton) {
            payButton.addEventListener('click', function (e) {
                e.preventDefault(); 
                
                var snapToken = '{{ $transaction->snap_token }}';
                
                if (!snapToken) {
                    alert("⚠️ Error: Token pembayaran kosong! Silakan hubungi admin.");
                    return;
                }

                window.snap.pay(snapToken, {
                    onSuccess: function (result) {
                        alert("✅ Pembayaran Berhasil!");
                        location.reload(); 
                    },
                    onPending: function (result) {
                        alert("⏳ Menunggu pembayaran Anda!");
                    },
                    onError: function (result) {
                        alert("❌ Pembayaran Gagal!");
                    },
                    onClose: function () {
                    }
                });
            });
        }
    </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // // Cek apakah ada Session Success
    // @if(session('success'))
    //     Swal.fire({
    //         icon: 'success',
    //         title: 'Berhasil!',
    //         text: "{{ session('success') }}",
    //         confirmButtonColor: '#4f46e5'
    //     });
    // @endif

    // Cek apakah ada Session Error
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#ef4444'
        });
    @endif
</script>
</body>
</html>