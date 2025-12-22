<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-[2rem] border border-slate-100 mb-8 relative">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-cyan-400"></div>
                <div class="p-8 sm:p-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <h1 class="text-3xl font-black text-slate-800 mb-2">Halo, {{ auth()->user()->name }}! 👋</h1>
                        <p class="text-slate-500">Selamat datang di dashboard akun Anda.</p>
                    </div>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.index') }}"
                           class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-white uppercase tracking-wider hover:bg-indigo-700 hover:-translate-y-1 transition-all duration-300 shadow-lg shadow-indigo-200" style="background-color: #4f46e5; color: white;">
                           🚀 Admin Dashboard
                        </a>
                    @else
                        <a href="{{ route('home') }}#reservasi"
                           class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-xl font-bold text-white uppercase tracking-wider hover:bg-blue-700 hover:-translate-y-1 transition-all duration-300 shadow-lg shadow-blue-200" style="background-color: #2563eb; color: white;">
                           <span class="mr-2 text-xl">🧺</span> Buat Pesanan Baru
                        </a>
                    @endif
                </div>
            </div>

            <!-- Transaction History -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-[2rem] border border-slate-100">
                <div class="p-8 sm:p-10">
                    <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <span class="text-2xl">📜</span> Riwayat Pesanan
                    </h2>

                    @if(auth()->user()->transactions->isEmpty())
                        <div class="text-center py-12 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                            <p class="text-6xl mb-4 opacity-50">🧺</p>
                            <h3 class="text-lg font-bold text-slate-600 mb-1">Belum ada pesanan</h3>
                            <p class="text-slate-400">Yuk buat pesanan laundry pertama kamu!</p>
                        </div>
                    @else
                        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            @foreach(auth()->user()->transactions->sortByDesc('created_at') as $transaction)
                                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 group relative overflow-hidden">
                                    <div class="absolute top-0 right-0 p-4 opacity-10 font-black text-6xl text-slate-200 group-hover:scale-110 transition-transform">
                                        #{{ $transaction->id }}
                                    </div>
                                    
                                    <div class="relative z-10">
                                        <div class="flex justify-between items-start mb-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                                @if($transaction->status == 'pending') bg-yellow-100 text-yellow-700
                                                @elseif($transaction->status == 'pickup') bg-blue-100 text-blue-700
                                                @elseif($transaction->status == 'processing') bg-indigo-100 text-indigo-700
                                                @elseif($transaction->status == 'delivery') bg-purple-100 text-purple-700
                                                @elseif($transaction->status == 'completed') bg-green-100 text-green-700
                                                @elseif($transaction->status == 'cancelled') bg-red-100 text-red-700
                                                @endif">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                            <span class="text-xs font-bold text-slate-400">{{ $transaction->created_at->format('d M Y') }}</span>
                                        </div>

                                        <h3 class="text-lg font-bold text-slate-800 mb-1">{{ $transaction->service_type ?? 'Laundry Service' }}</h3>
                                        @if($transaction->weight)
                                            <p class="text-sm font-medium text-slate-500 mb-3">Berat: {{ $transaction->weight }}kg</p>
                                        @endif
                                        
                                        <div class="border-t border-slate-100 pt-3 mt-2 flex justify-between items-end">
                                            <div>
                                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Harga</p>
                                                <p class="text-xl font-black text-blue-600">Rp {{ number_format($transaction->total_price, 0) }}</p>
                                            </div>
                                            <a href="{{ route('tracking') }}?code={{ $transaction->transaction_code }}" 
                                               class="text-blue-500 hover:text-blue-700 text-sm font-bold flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                               Track <span class="text-lg">→</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>