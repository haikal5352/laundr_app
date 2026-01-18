<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Ulasan Pelanggan') }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <span class="text-yellow-500 font-bold tracking-widest uppercase text-sm">⭐ Ulasan Pelanggan</span>
            <h1 class="text-4xl font-black text-slate-900 mt-2">Apa Kata Mereka?</h1>
            <p class="text-slate-500 mt-2">Testimoni asli dari pelanggan setia Laundry U9</p>
        </div>

        <!-- Write Review Button -->
        @auth
        <div class="text-center mb-8">
            <button onclick="document.getElementById('writeReviewSection').scrollIntoView({behavior: 'smooth'})" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition">
                ✍️ Tulis Ulasan
            </button>
        </div>
        @endauth

        <!-- Reviews Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            @forelse($reviews as $review)
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-slate-100 hover:shadow-xl transition">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-lg">
                        {{ substr($review->user->name ?? 'G', 0, 1) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900">{{ $review->user->name ?? 'Pelanggan' }}</h4>
                        <p class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="flex gap-1 text-yellow-400 mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="text-xl">{{ $i <= $review->rating ? '★' : '☆' }}</span>
                    @endfor
                </div>
                <p class="text-slate-600 leading-relaxed">"{{ $review->comment ?? 'Pelayanan sangat memuaskan!' }}"</p>
                @if($review->transaction)
                <p class="text-xs text-slate-400 mt-3">
                    Layanan: {{ $review->transaction->service->name ?? 'Laundry' }}
                </p>
                @endif
            </div>
            @empty
            <div class="col-span-full text-center py-16 bg-white rounded-2xl border-2 border-dashed border-slate-200">
                <div class="text-6xl mb-4">💬</div>
                <h3 class="text-lg font-bold text-slate-700">Belum Ada Ulasan</h3>
                <p class="text-slate-400 mt-1">Jadilah yang pertama memberikan ulasan!</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($reviews->hasPages())
        <div class="mb-12">
            {{ $reviews->links() }}
        </div>
        @endif

        <!-- Write Review Section -->
        @auth
        <div id="writeReviewSection" class="bg-white rounded-2xl shadow-lg p-8 border border-slate-100">
            <h3 class="text-2xl font-black text-slate-900 mb-6">✍️ Tulis Ulasan Anda</h3>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

            @if($eligibleTransactions->count() > 0)
            <form action="{{ route('review.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <!-- Select Transaction -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Pesanan yang Ingin Direview</label>
                        <select name="transaction_id" required class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Pesanan --</option>
                            @foreach($eligibleTransactions as $tx)
                                <option value="{{ $tx->id }}">
                                    #{{ $tx->id }} - {{ $tx->service->name ?? 'Laundry' }} ({{ $tx->created_at->format('d M Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Rating Stars -->
                    <div x-data="{ rating: 5 }">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Rating</label>
                        <div class="flex gap-2">
                            @for($i = 1; $i <= 5; $i++)
                            <button type="button" @click="rating = {{ $i }}" 
                                    :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300'"
                                    class="text-4xl hover:scale-110 transition">
                                ★
                            </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" :value="rating">
                    </div>

                    <!-- Comment -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Komentar (Opsional)</label>
                        <textarea name="comment" rows="4" 
                                  class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500"
                                  placeholder="Bagikan pengalaman Anda..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg transition">
                        Kirim Ulasan
                    </button>
                </div>
            </form>
            @else
            <div class="text-center py-8 bg-slate-50 rounded-xl">
                <div class="text-4xl mb-3">📦</div>
                <p class="text-slate-600 font-medium">Anda belum memiliki pesanan yang selesai untuk direview.</p>
                <p class="text-slate-400 text-sm mt-1">Selesaikan pesanan terlebih dahulu untuk bisa memberikan ulasan.</p>
                <a href="{{ route('home') }}" class="inline-block mt-4 bg-blue-600 text-white font-bold px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Buat Pesanan
                </a>
            </div>
            @endif
        </div>
        @else
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-slate-100 text-center">
            <div class="text-4xl mb-3">🔐</div>
            <p class="text-slate-600 font-medium mb-4">Login untuk memberikan ulasan</p>
            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition inline-block">
                Login Sekarang
            </a>
        </div>
        @endauth

        <!-- Back Button -->
        <div class="text-center mt-12">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 font-medium">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
</x-app-layout>
