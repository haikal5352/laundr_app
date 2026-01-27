<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300">
                <div class="p-6 bg-white border-b border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">
                        Halo, {{ Auth::user()->name }}! 👋
                    </h3>
                    <p class="text-gray-500 mt-1">Mau nyuci apa hari ini?</p>
                </div>
                
                <div class="flex gap-3">
                    <a href="{{ route('addresses.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 px-6 rounded-xl shadow hover:shadow-md transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center gap-2">
                        📍 Kelola Alamat
                    </a>
                    <a href="{{ route('home') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-indigo-500/30 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Buat Pesanan Baru
                    </a>
                </div>
            </div>

            <div class="px-1 mb-4">
                <h3 class="text-xl font-bold text-gray-800">Riwayat Pesanan</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($transactions as $transaction)
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl hover:scale-[1.02] transition-all duration-300 border border-gray-100 overflow-hidden relative transform">
                        
                        <div class="p-5 pb-0 flex justify-between items-start">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">ID: #{{ $transaction->id }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            
                            @if($transaction->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">🕒 Pending</span>
                            @elseif($transaction->status == 'process')
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">🧼 Dicuci</span>
                                @if($transaction->estimated_done_at)
                                <div class="absolute top-12 right-5 text-right">
                                    <p class="text-[10px] text-gray-400">Estimasi Selesai:</p>
                                    <p class="text-xs font-bold text-blue-600">{{ $transaction->estimated_done_at->format('d M, H:i') }}</p>
                                </div>
                                @endif
                            @elseif($transaction->status == 'done')
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">✅ Selesai</span>
                            @elseif($transaction->status == 'taken')
                                <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">👋 Diambil</span>
                            @elseif($transaction->status == 'cancelled')
                                <span class="bg-red-100 text-red-600 text-xs font-bold px-3 py-1 rounded-full">❌ Batal</span>
                            @endif
                        </div>

                        <div class="p-5">
                            <h4 class="text-lg font-bold text-gray-800 line-clamp-1">{{ $transaction->service->name ?? 'Layanan Dihapus' }}</h4>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $transaction->type == 'pickup_delivery' ? '🚛 Antar Jemput' : '🏢 Drop Off (Datang Sendiri)' }}
                            </p>
                            
                            <div class="mt-4 flex items-end justify-between">
                                <div>
                                    <p class="text-xs text-gray-400">Total Harga</p>
                                    <p class="text-xl font-black text-indigo-600">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-400">Berat/Qty</p>
                                    @php
                                        $items = json_decode($transaction->items_data, true);
                                        $isMulti = is_array($items) && count($items) > 1;
                                        $unit = $isMulti ? 'Item' : ($transaction->service->unit ?? '');
                                    @endphp
                                    <p class="font-semibold text-gray-700">{{ $transaction->qty }} {{ $unit }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 flex justify-between items-center">
                            <div>
                                @if($transaction->payment_status == '1')
                                    @if($transaction->payment_method == 'cash')
                                        <span class="text-xs font-bold text-orange-500">Tunai (Bayar di Outlet)</span>
                                    @else
                                        <span class="text-xs font-bold text-red-500">Belum Bayar</span>
                                    @endif
                                @elseif($transaction->payment_status == '2')
                                    <span class="text-xs font-bold text-green-600">Lunas</span>
                                @else
                                    <span class="text-xs font-bold text-gray-400">Kadaluarsa</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-3">
                                @if($transaction->status == 'taken' && !$transaction->review)
                                    <a href="{{ route('tracking', ['id' => $transaction->id]) }}" class="text-sm font-bold text-yellow-500 hover:text-yellow-600 flex items-center gap-1">
                                        ⭐ Beri Rating
                                    </a>
                                @endif
                                
                                <a href="{{ route('tracking', ['id' => $transaction->id]) }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 group">
                                    Lacak / Detail
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white rounded-2xl shadow-sm border border-dashed border-gray-300">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada pesanan</h3>
                        <p class="text-gray-500 mt-1">Yuk buat pesanan laundry pertamamu!</p>
                        <a href="{{ route('home') }}" class="mt-4 inline-block px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Order Sekarang
                        </a>
                    </div>
                @endforelse
            </div>
            
            <div class="mb-10"></div> 
        </div>
    </div>
</x-app-layout>