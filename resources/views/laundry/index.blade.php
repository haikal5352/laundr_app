<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laundry U9') }} - Premium Laundry Service</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
        }

        font-family: 'Outfit',
        sans-serif;
        }

        /* Custom Keyframes */
        @keyframes float-y {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        @keyframes rotate-slow {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .animate-float {
            animation: float-y 4s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: float-y 5s ease-in-out infinite 1s;
        }

        .animate-rotate-slow {
            animation: rotate-slow 20s linear infinite;
        }
    </style>
</head>

<body
    class="antialiased text-slate-800 bg-slate-50 relative overflow-x-hidden selection:bg-blue-500 selection:text-white">

    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-lg border-b border-slate-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center gap-2">
                    <div
                        class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-blue-200">
                        U9
                    </div>
                    <div>
                        <span class="text-xl font-bold text-slate-900 tracking-tight block leading-none">Laundry</span>
                        <span class="text-xs font-semibold text-blue-600 tracking-widest uppercase">U9</span>
                    </div>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="{{ route('tracking') }}"
                        class="text-slate-500 hover:text-blue-600 font-semibold text-sm transition-colors">Cek
                        Status</a>

                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="px-6 py-2.5 rounded-full bg-slate-900 text-white font-medium hover:bg-slate-800 transition shadow-xl shadow-slate-200">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-6 py-2.5 rounded-full bg-slate-900 text-white font-medium hover:bg-slate-800 transition shadow-xl shadow-slate-200">Masuk
                            Akun</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-16 pb-32 lg:pt-24 overflow-hidden">
        <!-- Abstract Shapes -->
        <div
            class="absolute top-0 right-0 -mr-32 -mt-32 w-[500px] h-[500px] bg-blue-100/50 rounded-full blur-3xl opacity-60">
        </div>
        <div
            class="absolute bottom-0 left-0 -ml-32 -mb-32 w-[400px] h-[400px] bg-orange-100/50 rounded-full blur-3xl opacity-60">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <!-- Left Content -->
                <div class="lg:w-1/2 text-center lg:text-left">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-600 font-bold text-xs uppercase tracking-wide mb-6">
                        <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                        Laundry Terpercaya
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-black text-slate-900 leading-tight mb-8">
                        Cucian Bersih,<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Hidup
                            Lebih Santai.</span>
                    </h1>
                    <p class="text-lg text-slate-500 mb-10 max-w-lg mx-auto lg:mx-0 leading-relaxed font-medium">
                        Teknologi cuci modern dengan deterjen premium. Kami jemput, cuci, dan antar kembali pakaian
                        Anda dalam kondisi suci hama dan wangi.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#reservasi"
                            class="px-8 py-4 rounded-2xl bg-blue-600 text-white font-bold text-lg shadow-xl shadow-blue-200 hover:shadow-2xl hover:bg-blue-700 transition transform hover:-translate-y-1">
                            Mulai Laundry
                        </a>
                        <a href="{{ route('tracking') }}"
                            class="px-8 py-4 rounded-2xl bg-white text-slate-700 font-bold text-lg shadow-lg shadow-slate-100 hover:shadow-xl hover:bg-slate-50 border border-slate-100 transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Lacak Pesanan
                        </a>
                    </div>
                </div>

                <!-- Right Image -->
                <div class="lg:w-1/2 relative">
                    <div class="relative z-10 animate-float">
                        <img src="https://img.freepik.com/free-vector/laundry-room-with-washing-machine-basket_107791-1634.jpg?w=1060&t=st=1702905000~exp=1702905600~hmac=xyz"
                            class="rounded-[2.5rem] shadow-2xl skew-y-3 hover:skew-y-0 transition duration-700 w-full object-cover">
                    </div>

                    <!-- Floating Badge 1 -->
                    <div
                        class="absolute -top-6 -right-6 bg-white p-5 rounded-2xl shadow-xl z-20 animate-float-delayed max-w-xs">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-2xl">
                                ⚡
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900">Super Cepat</h3>
                                <p class="text-sm text-slate-500">Selesai dalam 3 Jam</p>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Badge 2 -->
                    <div
                        class="absolute -bottom-10 -left-6 bg-white p-5 rounded-2xl shadow-xl z-20 animate-float max-w-xs">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-2xl">
                                🏷️
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900">Harga Teman</h3>
                                <p class="text-sm text-slate-500">Mulai Rp 5.000/kg</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <div class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-3xl lg:text-4xl font-black text-slate-900 mb-4">Layanan Lengkap</h2>
                <p class="text-slate-500 max-w-2xl mx-auto">Apapun jenis pakaiannya, kami punya treatment khusus untuk
                    menjaga kualitas bahan tetap awet.</p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($services as $index => $service)
                    @php
                        $colors = ['bg-blue-50 border-blue-100', 'bg-orange-50 border-orange-100', 'bg-purple-50 border-purple-100', 'bg-green-50 border-green-100'];
                        $delayClass = $index % 2 == 0 ? 'animate-float' : 'animate-float-delayed';
                        $colorClass = $colors[$index % 4];
                        $name = strtolower($service->name);
                    @endphp
                    <div
                        class="group bg-white rounded-[2rem] p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-slate-100 {{ $delayClass }}">
                        <div
                            class="w-24 h-24 mx-auto mb-6 flex items-center justify-center text-6xl transition-transform duration-500 group-hover:scale-110 group-hover:rotate-12">
                            @if(Str::contains($name, 'kiloan')) 🧺
                            @elseif(Str::contains($name, 'setrika')) ♨️
                            @elseif(Str::contains($name, 'sepatu')) 👟
                            @elseif(Str::contains($name, 'tas')) 🎒
                            @elseif(Str::contains($name, 'karpet')) 🏰
                            @elseif(Str::contains($name, 'gorden')) 🪟
                            @elseif(Str::contains($name, 'stroller')) 🛒
                            @elseif(Str::contains($name, 'boneka')) 🧸
                            @elseif(Str::contains($name, 'bed cover')) 🛏️
                            @elseif(Str::contains($name, 'sprei')) 🧣
                            @elseif(Str::contains($name, 'bantal')) 🛌
                            @else 👕 @endif
                        </div>
                        <div class="text-center relative z-10">
                            <h4 class="text-xl font-bold text-slate-800 mb-2 truncate" title="{{ $service->name }}">
                                {{ $service->name }}
                            </h4>
                            <div class="inline-block bg-slate-50 rounded-full px-3 py-1 mb-2">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Mulai Dari</p>
                            </div>
                            <p class="text-blue-600 font-black text-2xl">Rp
                                {{ number_format($service->price / 1000, 0) }}<span
                                    class="text-sm text-slate-400 font-medium">rb</span>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    </div>

    <!-- Order Form Section -->
    <div id="reservasi" class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white rounded-[2.5rem] shadow-2xl p-10 lg:p-14 border border-slate-100">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-black text-slate-900 mb-3">Buat Pesanan Baru</h2>
                    <p class="text-slate-500">Isi form di bawah, kurir kami akan segera meluncur.</p>
                </div>

                @if(session('success'))
                    <div
                        class="bg-green-50 text-green-700 p-4 rounded-xl mb-8 flex items-center gap-3 border border-green-200">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('transaction.store') }}" method="POST" x-data="{ type: 'dropoff' }">
                    @csrf

                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700">Nama Pelanggan</label>
                                <input type="text" name="customer_name" required
                                    class="w-full bg-slate-50 border-0 rounded-xl px-5 py-4 font-medium focus:ring-2 focus:ring-blue-600 transition"
                                    placeholder="Nama Anda" value="{{ auth()->check() ? auth()->user()->name : '' }}">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700">Nomor WhatsApp</label>
                                <input type="text" name="customer_phone" required
                                    class="w-full bg-slate-50 border-0 rounded-xl px-5 py-4 font-medium focus:ring-2 focus:ring-blue-600 transition"
                                    placeholder="08..." value="{{ auth()->check() ? auth()->user()->phone : '' }}">
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700">Pilih Layanan</label>
                                <select name="service_id"
                                    class="w-full bg-slate-50 border-0 rounded-xl px-5 py-4 font-medium focus:ring-2 focus:ring-blue-600 transition appearance-none cursor-pointer">
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }} - Rp
                                            {{ number_format($service->price) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700">Berat / Jumlah</label>
                                <input type="number" name="qty" required
                                    class="w-full bg-slate-50 border-0 rounded-xl px-5 py-4 font-medium focus:ring-2 focus:ring-blue-600 transition"
                                    placeholder="Contoh: 3">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Metode Pengantaran</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="type" value="dropoff" x-model="type" class="peer sr-only">
                                    <div
                                        class="p-4 rounded-xl border-2 border-slate-100 hover:bg-slate-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition text-center">
                                        <div class="text-2xl mb-1">🏢</div>
                                        <div class="font-bold text-slate-700">Datang ke Outlet</div>
                                    </div>
                                </label>
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="type" value="pickup_delivery" x-model="type"
                                        class="peer sr-only">
                                    <div
                                        class="p-4 rounded-xl border-2 border-slate-100 hover:bg-slate-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition text-center">
                                        <div class="text-2xl mb-1">🚚</div>
                                        <div class="font-bold text-slate-700">Antar Jemput</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div x-show="type === 'pickup_delivery'" x-transition class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Alamat Lengkap</label>
                            <textarea name="address" rows="3"
                                class="w-full bg-slate-50 border-0 rounded-xl px-5 py-4 font-medium focus:ring-2 focus:ring-blue-600 transition"
                                placeholder="Tulis alamat lengkap penjemputan...">{{ auth()->check() ? auth()->user()->address : '' }}</textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-slate-900 text-white font-bold text-lg py-5 rounded-xl hover:bg-slate-800 transition shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            Konfirmasi Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 py-12 text-center">
        <div class="max-w-7xl mx-auto px-4">
            <h4 class="text-2xl font-black text-slate-900 mb-2">Laundry<span class="text-blue-600">U9</span></h4>
            <p class="text-slate-400 font-medium">Mitra terbaik kebersihan pakaian keluarga Anda.</p>
            <p class="text-slate-300 text-sm mt-8">&copy; {{ date('Y') }} Laundry U9 Indonesia.</p>
        </div>
    </footer>

</body>

</html>