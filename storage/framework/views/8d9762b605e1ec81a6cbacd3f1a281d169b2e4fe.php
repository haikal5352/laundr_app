<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name', 'Laundry U9')); ?> - Premium Laundry Service</title>
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
        @keyframes  float-y {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        @keyframes  rotate-slow {
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
     <?php if(session('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative m-4" role="alert">
        <strong class="font-bold">Gagal!</strong>
        <span class="block sm:inline"><?php echo e(session('error')); ?></span>
    </div>
<?php endif; ?>
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
                <div class="flex items-center space-x-4">
                    <?php if(auth()->guard()->check()): ?>
                        <span class="text-slate-600 text-sm font-medium hidden sm:block">
                            👋 Halo, <?php echo e(auth()->user()->name); ?>

                        </span>
                        <a href="<?php echo e(route('dashboard')); ?>"
                            class="px-6 py-2.5 rounded-full bg-slate-900 text-white font-medium hover:bg-slate-800 transition shadow-xl shadow-slate-200">Dashboard</a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>"
                            class="px-6 py-2.5 rounded-full bg-slate-900 text-white font-medium hover:bg-slate-800 transition shadow-xl shadow-slate-200">Masuk
                            Akun</a>
                    <?php endif; ?>
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

    <!-- Promo Voucher Banner -->
    <?php
        $activeVouchers = \App\Models\Voucher::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->where(function($q) {
                $q->whereNull('quota')->orWhereColumn('used_count', '<', 'quota');
            })
            ->take(2)
            ->get();
    ?>
    
    <?php if($activeVouchers->count() > 0): ?>
    <div class="py-8 bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-600 relative overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute -top-10 -left-10 w-40 h-40 bg-white rounded-full blur-3xl"></div>
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-yellow-300 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left">
                    <p class="text-white/80 text-sm font-bold uppercase tracking-widest mb-1">🎉 Promo Spesial</p>
                    <h3 class="text-2xl md:text-3xl font-black text-white">Gunakan Voucher & Hemat!</h3>
                </div>
                <div class="flex flex-wrap justify-center gap-4">
                    <?php $__currentLoopData = $activeVouchers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voucher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 border border-white/30 min-w-[180px]" 
                         x-data="{ copied: false }">
                        <p class="text-white/70 text-xs font-medium mb-1">
                            <?php if($voucher->discount_type == 'percent'): ?>
                                Diskon <?php echo e($voucher->discount_value); ?>%
                                <?php if($voucher->max_discount): ?> (Max Rp <?php echo e(number_format($voucher->max_discount/1000)); ?>rb) <?php endif; ?>
                            <?php else: ?>
                                Potongan Rp <?php echo e(number_format($voucher->discount_value/1000)); ?>rb
                            <?php endif; ?>
                        </p>
                        <div class="flex items-center gap-2">
                            <code class="bg-white text-blue-600 font-black text-lg px-3 py-1 rounded-lg tracking-wider">
                                <?php echo e($voucher->code); ?>

                            </code>
                            <button @click="navigator.clipboard.writeText('<?php echo e($voucher->code); ?>'); copied = true; setTimeout(() => copied = false, 2000)"
                                    class="text-white hover:text-yellow-300 transition" title="Salin Kode">
                                <span x-show="!copied">📋</span>
                                <span x-show="copied">✅</span>
                            </button>
                        </div>
                        <?php if($voucher->min_order): ?>
                        <p class="text-white/60 text-xs mt-1">Min. order Rp <?php echo e(number_format($voucher->min_order/1000)); ?>rb</p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <a href="<?php echo e(route('vouchers.index')); ?>" class="bg-white text-blue-600 font-bold px-6 py-3 rounded-xl hover:bg-yellow-300 hover:text-slate-900 transition shadow-lg">
                    Lihat Semua Voucher →
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Services Section -->
    <div class="py-24 bg-white relative" x-data="{ }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-3xl lg:text-4xl font-black text-slate-900 mb-4">Layanan Lengkap</h2>
                <p class="text-slate-500 max-w-2xl mx-auto">Klik layanan di bawah untuk langsung menambahkan ke pesanan Anda.</p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $colors = ['bg-blue-50 border-blue-100', 'bg-orange-50 border-orange-100', 'bg-purple-50 border-purple-100', 'bg-green-50 border-green-100'];
                        $delayClass = $index % 2 == 0 ? 'animate-float' : 'animate-float-delayed';
                        $colorClass = $colors[$index % 4];
                        $name = strtolower($service->name);
                    ?>
                    <div
                        @click="$dispatch('add-service', { id: <?php echo e($service->id); ?>, name: '<?php echo e(addslashes($service->name)); ?>', price: <?php echo e($service->price); ?> }); document.getElementById('reservasi').scrollIntoView({ behavior: 'smooth' })"
                        class="group bg-white rounded-[2rem] p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-slate-100 <?php echo e($delayClass); ?> cursor-pointer relative overflow-hidden">
                        <!-- Click indicator -->
                        <div class="absolute top-3 right-3 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                            + Tambah
                        </div>
                        <div
                            class="w-24 h-24 mx-auto mb-6 flex items-center justify-center text-6xl transition-transform duration-500 group-hover:scale-110 group-hover:rotate-12">
                            <?php if(Str::contains($name, 'kiloan')): ?> 🧺
                            <?php elseif(Str::contains($name, 'setrika')): ?> ♨️
                            <?php elseif(Str::contains($name, 'sepatu')): ?> 👟
                            <?php elseif(Str::contains($name, 'tas')): ?> 🎒
                            <?php elseif(Str::contains($name, 'karpet')): ?> 🏰
                            <?php elseif(Str::contains($name, 'gorden')): ?> 🪟
                            <?php elseif(Str::contains($name, 'stroller')): ?> 🛒
                            <?php elseif(Str::contains($name, 'boneka')): ?> 🧸
                            <?php elseif(Str::contains($name, 'bed cover')): ?> 🛏️
                            <?php elseif(Str::contains($name, 'sprei')): ?> 🧣
                            <?php elseif(Str::contains($name, 'bantal')): ?> 🛌
                            <?php else: ?> 👕 <?php endif; ?>
                        </div>
                        <div class="text-center relative z-10">
                            <h4 class="text-xl font-bold text-slate-800 mb-2 truncate" title="<?php echo e($service->name); ?>">
                                <?php echo e($service->name); ?>

                            </h4>
                            <div class="inline-block bg-slate-50 rounded-full px-3 py-1 mb-2">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Mulai Dari</p>
                            </div>
                            <p class="text-blue-600 font-black text-2xl">Rp
                                <?php echo e(number_format($service->price / 1000, 0)); ?><span
                                    class="text-sm text-slate-400 font-medium">rb</span>
                            </p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    </div>

    <!-- Order Form Section -->
    <div id="reservasi" class="py-24 bg-slate-50 relative overflow-hidden"
        x-data="{
            type: 'dropoff',
            items: [],
            services: <?php echo \Illuminate\Support\Js::from($services->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'price' => $s->price]))->toHtml() ?>,
            selectedService: '',
            
            addService(id, name, price) {
                // Check if service already exists
                const existing = this.items.find(item => item.id === id);
                if (existing) {
                    existing.qty++;
                } else {
                    this.items.push({ id, name, price, qty: 1 });
                }
            },
            
            removeItem(index) {
                this.items.splice(index, 1);
            },
            
            updateQty(index, qty) {
                if (qty < 1) qty = 1;
                this.items[index].qty = qty;
            },
            
            get grandTotal() {
                let total = this.items.reduce((sum, item) => sum + (item.price * item.qty), 0);
                if (this.voucherApplied) {
                    total -= this.discountAmount;
                }
                return total < 0 ? 0 : total;
            },
            
            // Voucher Data
            voucherCode: '',
            voucherApplied: false,
            discountAmount: 0,
            voucherMessage: '',
            voucherLoading: false,

            async checkVoucher() {
                if (!this.voucherCode) return;
                this.voucherLoading = true;
                this.voucherMessage = '';

                try {
                    const response = await fetch('<?php echo e(route('voucher.check')); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                        },
                        body: JSON.stringify({
                            code: this.voucherCode,
                            total: this.items.reduce((sum, item) => sum + (item.price * item.qty), 0)
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.valid) {
                        this.voucherApplied = true;
                        this.discountAmount = data.discount;
                        this.voucherMessage = `✅ ${data.message} (Hemat: ${this.formatRupiah(data.discount)})`;
                    } else {
                        this.voucherApplied = false;
                        this.discountAmount = 0;
                        this.voucherMessage = `❌ ${data.message}`;
                    }
                } catch (error) {
                    console.error('Error checking voucher:', error);
                    this.voucherMessage = '❌ Terjadi kesalahan saat cek voucher';
                } finally {
                    this.voucherLoading = false;
                }
            },
            
            removeVoucher() {
                this.voucherCode = '';
                this.voucherApplied = false;
                this.discountAmount = 0;
                this.voucherMessage = '';
            },
            
            addFromSelect() {
                if (!this.selectedService) return;
                const service = this.services.find(s => s.id == this.selectedService);
                if (service) {
                    this.addService(service.id, service.name, service.price);
                    this.selectedService = '';
                }
            },
            
            handleAddressSelect(event) {
                const select = event.target;
                const option = select.options[select.selectedIndex];
                
                if (option.value === 'new') {
                    // Allow manual input - clear hidden inputs
                    if (this.$refs.addressInput) this.$refs.addressInput.value = '';
                    if (this.$refs.phoneInput) this.$refs.phoneInput.value = '';
                } else if (option.value) {
                    // Fill hidden inputs with selected address data
                    const address = option.getAttribute('data-address');
                    const phone = option.getAttribute('data-phone');
                    
                    if (this.$refs.addressInput) this.$refs.addressInput.value = address;
                    if (this.$refs.phoneInput) this.$refs.phoneInput.value = phone;
                    
                    // Also update visible phone if customer_phone input uses this
                    // Find the customer_phone input and update it
                    const phoneInput = document.querySelector('input[name=customer_phone]');
                    if (phoneInput && phone) {
                        phoneInput.value = phone;
                    }
                }
            },
            
            formatRupiah(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }
        }"
        @add-service.window="addService($event.detail.id, $event.detail.name, $event.detail.price)"
    >
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white rounded-[2.5rem] shadow-2xl p-10 lg:p-14 border border-slate-100">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-black text-slate-900 mb-3">Buat Pesanan Baru</h2>
                    <p class="text-slate-500">Pilih layanan dari ikon di atas atau tambahkan manual di bawah.</p>
                </div>

                <?php if(session('success')): ?>
                    <div
                        class="bg-green-50 text-green-700 p-4 rounded-xl mb-8 flex items-center gap-3 border border-green-200">
                        ✅ <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('transaction.store')); ?>" method="POST" x-ref="orderForm">
                    <?php echo csrf_field(); ?>

                    <!-- Hidden input untuk items sebagai JSON -->
                    <input type="hidden" name="items_json" :value="JSON.stringify(items.map(i => ({service_id: i.id, qty: i.qty})))">

                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700">Nama Pelanggan</label>
                                <input type="text" name="customer_name" required
                                    class="w-full bg-slate-50 border-0 rounded-xl px-5 py-4 font-medium focus:ring-2 focus:ring-blue-600 transition"
                                    placeholder="Nama Anda" value="<?php echo e(auth()->check() ? auth()->user()->name : ''); ?>">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700">Nomor WhatsApp</label>
                                <?php if(auth()->guard()->check()): ?>
                                    <?php
                                        $defaultAddress = auth()->user()->addresses()->where('is_default', true)->first();
                                        $defaultPhone = $defaultAddress ? $defaultAddress->phone : '';
                                    ?>
                                    <?php if($defaultPhone): ?>
                                        <div x-data="{ useDefault: true, phoneValue: '<?php echo e($defaultPhone); ?>' }">
                                            <div class="flex items-center gap-2">
                                                <input type="text" name="customer_phone" required
                                                    x-model="phoneValue"
                                                    :readonly="useDefault"
                                                    :class="useDefault ? 'bg-slate-100 text-slate-700' : 'bg-slate-50 focus:ring-2 focus:ring-blue-600'"
                                                    class="flex-1 border-0 rounded-xl px-5 py-4 font-medium transition"
                                                    placeholder="08...">
                                                <button type="button" @click="useDefault = !useDefault; if(!useDefault) phoneValue = ''" 
                                                    class="text-blue-600 text-sm hover:underline whitespace-nowrap">
                                                    <span x-show="useDefault">Ganti Nomor</span>
                                                    <span x-show="!useDefault">← Pakai Default</span>
                                                </button>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <input type="text" name="customer_phone" required
                                            class="w-full bg-slate-50 border-0 rounded-xl px-5 py-4 font-medium focus:ring-2 focus:ring-blue-600 transition"
                                            placeholder="08...">
                                        <p class="text-xs text-slate-400 mt-1">
                                            💡 <a href="<?php echo e(route('addresses.index')); ?>" target="_blank" class="text-blue-600 hover:underline">Simpan alamat</a> agar nomor terisi otomatis.
                                        </p>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <input type="text" name="customer_phone" required
                                        class="w-full bg-slate-50 border-0 rounded-xl px-5 py-4 font-medium focus:ring-2 focus:ring-blue-600 transition"
                                        placeholder="08...">
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Add Service Section -->
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Tambah Layanan</label>
                            <div class="flex gap-3">
                                <select x-model="selectedService"
                                    class="flex-1 bg-slate-50 border-0 rounded-xl px-5 py-4 font-medium focus:ring-2 focus:ring-blue-600 transition appearance-none cursor-pointer">
                                    <option value="">-- Pilih Layanan --</option>
                                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($service->id); ?>"><?php echo e($service->name); ?> - Rp <?php echo e(number_format($service->price)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <button type="button" @click="addFromSelect()"
                                    class="px-6 py-4 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Tambah
                                </button>
                            </div>
                        </div>

                        <!-- Voucher Section -->
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Kode Voucher</label>
                            <div class="flex gap-3">
                                <input type="text" x-model="voucherCode" 
                                    :disabled="voucherApplied"
                                    placeholder="Masukkan kode voucher"
                                    class="flex-1 bg-slate-50 border-0 rounded-xl px-5 py-4 font-medium focus:ring-2 focus:ring-blue-600 transition uppercase">
                                <button type="button" 
                                    @click="voucherApplied ? removeVoucher() : checkVoucher()"
                                    :class="voucherApplied ? 'bg-red-500 hover:bg-red-600' : 'bg-slate-800 hover:bg-slate-700'"
                                    class="px-6 py-4 text-white font-bold rounded-xl transition flex items-center gap-2 min-w-[100px] justify-center"
                                    :disabled="!voucherCode && !voucherApplied">
                                    <span x-show="voucherLoading" class="animate-spin text-lg">↻</span>
                                    <span x-show="!voucherLoading && !voucherApplied">Gunakan</span>
                                    <span x-show="!voucherLoading && voucherApplied">Hapus</span>
                                </button>
                            </div>
                            <p x-show="voucherMessage" x-text="voucherMessage" class="text-sm font-bold" :class="voucherApplied ? 'text-green-600' : 'text-red-500'"></p>
                            
                            <!-- Hidden Inputs for Voucher -->
                            <input type="hidden" name="voucher_code" :value="voucherApplied ? voucherCode : ''">
                            <input type="hidden" name="discount_amount" :value="discountAmount">
                        </div>

                        <!-- Cart Items -->
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Daftar Layanan Dipilih</label>
                            
                            <!-- Empty State -->
                            <div x-show="items.length === 0" class="bg-slate-50 rounded-xl p-8 text-center border-2 border-dashed border-slate-200">
                                <div class="text-4xl mb-3">🧺</div>
                                <p class="text-slate-500 font-medium">Belum ada layanan dipilih</p>
                                <p class="text-slate-400 text-sm">Klik ikon layanan di atas atau pilih dari dropdown</p>
                            </div>

                            <!-- Items List -->
                            <div x-show="items.length > 0" class="space-y-3">
                                <template x-for="(item, index) in items" :key="index">
                                    <div class="bg-slate-50 rounded-xl p-4 flex items-center gap-4 border border-slate-100">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-slate-800" x-text="item.name"></h4>
                                            <p class="text-sm text-slate-500">Rp <span x-text="formatRupiah(item.price)"></span> / unit</p>
                                        </div>
                                        
                                        <!-- Quantity Controls -->
                                        <div class="flex items-center gap-2">
                                            <button type="button" @click="updateQty(index, item.qty - 1)"
                                                class="w-8 h-8 rounded-lg bg-slate-200 text-slate-600 font-bold hover:bg-slate-300 transition flex items-center justify-center">
                                                −
                                            </button>
                                            <input type="number" :value="item.qty" @input="updateQty(index, parseInt($event.target.value) || 1)"
                                                class="w-16 text-center bg-white border border-slate-200 rounded-lg py-2 font-bold text-slate-800" min="1">
                                            <button type="button" @click="updateQty(index, item.qty + 1)"
                                                class="w-8 h-8 rounded-lg bg-slate-200 text-slate-600 font-bold hover:bg-slate-300 transition flex items-center justify-center">
                                                +
                                            </button>
                                        </div>
                                        
                                        <!-- Subtotal -->
                                        <div class="text-right min-w-[100px]">
                                            <p class="text-xs text-slate-400">Subtotal</p>
                                            <p class="font-black text-blue-600">Rp <span x-text="formatRupiah(item.price * item.qty)"></span></p>
                                        </div>
                                        
                                        <!-- Remove Button -->
                                        <button type="button" @click="removeItem(index)"
                                            class="w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </template>

                                <!-- Grand Total -->
                                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-4 flex items-center justify-between text-white">
                                    <div>
                                        <p class="text-blue-100 text-sm font-medium">Total Pesanan</p>
                                        <p class="text-xs text-blue-200" x-text="items.length + ' layanan'"></p>
                                    </div>
                                    <p class="text-2xl font-black">Rp <span x-text="formatRupiah(grandTotal)"></span></p>
                                </div>
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
                            <label class="text-sm font-bold text-slate-700">Alamat Pengantaran</label>
                            
                            <?php if(auth()->guard()->check()): ?>
                                <?php if($addresses->count() > 0): ?>
                                    <!-- Dropdown Alamat Tersimpan -->
                                    <select x-ref="addressSelect" 
                                            @change="handleAddressSelect($event)" 
                                            class="w-full bg-slate-50 border-0 rounded-xl px-5 py-4 font-medium focus:ring-2 focus:ring-blue-600 transition">
                                        <option value="">-- Pilih Alamat Tersimpan --</option>
                                        <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($addr->id); ?>" 
                                                    data-phone="<?php echo e($addr->phone); ?>" 
                                                    data-address="<?php echo e($addr->address); ?>"
                                                    data-recipient="<?php echo e($addr->recipient); ?>">
                                                <?php echo e($addr->label); ?> - <?php echo e($addr->recipient); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <option value="new">+ Gunakan Alamat Baru</option>
                                    </select>
                                    
                                    <!-- Hidden inputs for selected address -->
                                    <input type="hidden" name="address" x-ref="addressInput">
                                    <input type="hidden" x-ref="phoneInput">
                                    
                                    <!-- Link ke manage addresses -->
                                    <a href="<?php echo e(route('addresses.index')); ?>" target="_blank" class="text-xs text-blue-600 hover:underline">
                                        📍 Kelola Alamat Saya
                                    </a>
                                <?php else: ?>
                                    <!-- User belum punya alamat, tampilkan form manual + link -->
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 mb-2">
                                        <p class="text-xs text-yellow-800">
                                            💡 Anda belum punya alamat tersimpan. 
                                            <a href="<?php echo e(route('addresses.index')); ?>" target="_blank" class="font-bold underline">Tambah alamat</a> 
                                            untuk kemudahan di pesanan berikutnya.
                                        </p>
                                    </div>
                                    <textarea name="address" rows="3"
                                        class="w-full bg-slate-50 border-0 rounded-xl px-5 py-4 font-medium focus:ring-2 focus:ring-blue-600 transition"
                                        placeholder="Tulis alamat lengkap penjemputan..."></textarea>
                                <?php endif; ?>
                            <?php else: ?>
                                <!-- Guest user - manual input -->
                                <textarea name="address" rows="3"
                                    class="w-full bg-slate-50 border-0 rounded-xl px-5 py-4 font-medium focus:ring-2 focus:ring-blue-600 transition"
                                    placeholder="Tulis alamat lengkap penjemputan..."></textarea>
                            <?php endif; ?>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Metode Pembayaran</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method" value="cash" class="peer sr-only" checked>
                                    <div class="rounded-xl border-2 border-slate-100 p-4 text-center hover:bg-slate-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                        <div class="text-3xl mb-2">💵</div>
                                        <div class="text-sm font-bold text-slate-700">Tunai</div>
                                        <div class="text-xs text-slate-400">Bayar di tempat</div>
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method" value="epayment" class="peer sr-only">
                                    <div class="rounded-xl border-2 border-slate-100 p-4 text-center hover:bg-slate-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                        <div class="text-3xl mb-2">📱</div>
                                        <div class="text-sm font-bold text-slate-700">E-Payment</div>
                                        <div class="text-xs text-slate-400">QRIS, Transfer, dll</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <button type="submit" :disabled="items.length === 0"
                            :class="items.length === 0 ? 'bg-slate-300 cursor-not-allowed' : 'bg-slate-900 hover:bg-slate-800 transform hover:-translate-y-1'"
                            class="w-full text-white font-bold text-lg py-5 rounded-xl transition shadow-xl hover:shadow-2xl">
                            <span x-show="items.length === 0">Pilih Layanan Terlebih Dahulu</span>
                            <span x-show="items.length > 0">Konfirmasi Pesanan (<span x-text="items.length"></span> Layanan)</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Testimonial Section -->
    <div class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <span class="text-blue-600 font-bold tracking-widest uppercase text-sm">Review Pelanggan</span>
                <h2 class="text-3xl lg:text-4xl font-black text-slate-900 mt-2">Kata Mereka Tentang Kami 💬</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                    $reviews = \App\Models\Review::with('user')->latest()->take(3)->get();
                ?>

                <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 hover:shadow-xl transition duration-300">
                        <div class="flex items-center gap-1 text-yellow-400 mb-4">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <span class="text-xl"><?php echo e($i <= $review->rating ? '★' : '☆'); ?></span>
                            <?php endfor; ?>
                        </div>
                        <p class="text-slate-600 mb-6 leading-relaxed">"<?php echo e($review->comment ?? 'Pelayanan sangat memuaskan!'); ?>"</p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-lg">
                                <?php echo e(substr($review->user->name ?? 'Guest', 0, 1)); ?>

                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900"><?php echo e($review->user->name ?? 'Pelanggan'); ?></h4>
                                <p class="text-xs text-slate-400"><?php echo e($review->created_at->diffForHumans()); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <!-- Dummy Reviews for initial display -->
                    <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100">
                        <div class="flex items-center gap-1 text-yellow-400 mb-4">★★★★★</div>
                        <p class="text-slate-600 mb-6 leading-relaxed">"Hasil cuci bersih wangi, pelayanan ramah, dan tepat waktu. Sangat recommended!"</p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold text-lg">A</div>
                            <div>
                                <h4 class="font-bold text-slate-900">Andi Saputra</h4>
                                <p class="text-xs text-slate-400">2 hari yang lalu</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 py-12 text-center">
        <div class="max-w-7xl mx-auto px-4">
            <h4 class="text-2xl font-black text-slate-900 mb-2">Laundry<span class="text-blue-600">U9</span></h4>
            <p class="text-slate-400 font-medium">Mitra terbaik kebersihan pakaian keluarga Anda.</p>
            <p class="text-slate-300 text-sm mt-8">&copy; <?php echo e(date('Y')); ?> Laundry U9 Indonesia.</p>
        </div>
    </footer>

</body>

</html><?php /**PATH C:\Users\achma\Documents\PROYEK 2\laundr_app\resources\views/laundry/index.blade.php ENDPATH**/ ?>