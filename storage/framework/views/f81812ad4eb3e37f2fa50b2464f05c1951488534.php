<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name', 'Laundry U9')); ?> - Lacak Pesanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo e(config('midtrans.client_key')); ?>"></script>
    <style>
        body { font-family: 'Quicksand', sans-serif; }
    </style>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('countdown', (expiry) => ({
                expiry: new Date(expiry).getTime(),
                remaining: null,
                hours: '00',
                minutes: '00',
                seconds: '00',
                
                init() {
                    this.tick();
                    setInterval(() => { this.tick() }, 1000);
                },
                
                tick() {
                    const now = new Date().getTime();
                    this.remaining = this.expiry - now;
                    
                    if (this.remaining < 0) {
                        this.hours = '00';
                        this.minutes = '00';
                        this.seconds = '00';
                        return;
                    }
                    
                    const h = Math.floor((this.remaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const m = Math.floor((this.remaining % (1000 * 60 * 60)) / (1000 * 60));
                    const s = Math.floor((this.remaining % (1000 * 60)) / 1000);
                    
                    this.hours = h.toString().padStart(2, '0');
                    this.minutes = m.toString().padStart(2, '0');
                    this.seconds = s.toString().padStart(2, '0');
                }
            }));
        });
    </script>
</head>

<body class="antialiased text-slate-800 bg-slate-50 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-lg border-b border-slate-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center gap-2">
                    <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-blue-200">
                            U9
                        </div>
                        <div>
                            <span class="text-xl font-bold text-slate-900 tracking-tight block leading-none">Laundry</span>
                            <span class="text-xs font-semibold text-blue-600 tracking-widest uppercase">U9</span>
                        </div>
                    </a>
                </div>
                <div class="flex items-center space-x-8">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('dashboard')); ?>" class="px-6 py-2.5 rounded-full bg-slate-900 text-white font-medium hover:bg-slate-800 transition shadow-xl shadow-slate-200">Dashboard</a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="px-6 py-2.5 rounded-full bg-slate-900 text-white font-medium hover:bg-slate-800 transition shadow-xl shadow-slate-200">Masuk Akun</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <?php if(session('success')): ?>
                <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-3 border border-green-200">
                    ✅ <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 flex items-center gap-3 border border-red-200">
                    ❌ <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php if($transaction): ?>
                <div class="bg-white rounded-[2rem] shadow-xl p-8 lg:p-10 border border-slate-100">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-black text-slate-900 mb-2">Detail Pesanan #<?php echo e($transaction->id); ?></h2>
                        <p class="text-slate-500"><?php echo e($transaction->created_at->format('d M Y, H:i')); ?></p>
                    </div>

                    <!-- Status Badge -->
                    <div class="text-center mb-8">
                        <?php if($transaction->status == 'pending'): ?>
                            <span class="inline-block bg-yellow-100 text-yellow-700 text-sm font-bold px-4 py-2 rounded-full">🕒 Menunggu Proses</span>
                        <?php elseif($transaction->status == 'process'): ?>
                            <span class="inline-block bg-blue-100 text-blue-700 text-sm font-bold px-4 py-2 rounded-full">🧼 Sedang Dicuci</span>
                        <?php elseif($transaction->status == 'done'): ?>
                            <span class="inline-block bg-green-100 text-green-700 text-sm font-bold px-4 py-2 rounded-full">✅ Selesai</span>
                        <?php elseif($transaction->status == 'taken'): ?>
                            <span class="inline-block bg-gray-100 text-gray-600 text-sm font-bold px-4 py-2 rounded-full">👋 Sudah Diambil</span>
                        <?php elseif($transaction->status == 'cancelled'): ?>
                            <span class="inline-block bg-red-100 text-red-600 text-sm font-bold px-4 py-2 rounded-full">❌ Dibatalkan</span>
                        <?php endif; ?>
                    </div>

                    <!-- Order Info -->
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center py-3 border-b border-slate-100">
                            <span class="text-slate-500">Nama Pelanggan</span>
                            <span class="font-bold text-slate-800"><?php echo e($transaction->customer_name); ?></span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-slate-100">
                            <span class="text-slate-500">No. WhatsApp</span>
                            <span class="font-bold text-slate-800"><?php echo e($transaction->customer_phone); ?></span>
                        </div>
                        <?php if($transaction->items_data): ?>
                            <?php
                                $items = is_string($transaction->items_data) ? json_decode($transaction->items_data, true) : $transaction->items_data;
                            ?>
                            <!-- Tampilan Multiple Items -->
                            <div class="py-3 border-b border-slate-100">
                                <span class="text-slate-500 block mb-2">Daftar Layanan</span>
                                <div class="space-y-2">
                                    <?php if(is_array($items)): ?>
                                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex justify-between items-center text-sm bg-slate-50 p-2 rounded-lg">
                                                <span class="font-bold text-slate-700"><?php echo e($item['service_name']); ?> <span class="text-slate-400 font-normal">x <?php echo e($item['qty']); ?></span></span>
                                                <span class="font-bold text-slate-900">Rp <?php echo e(number_format($item['subtotal'], 0, ',', '.')); ?></span>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Tampilan Single Item (Lama) -->
                            <div class="flex justify-between items-center py-3 border-b border-slate-100">
                                <span class="text-slate-500">Layanan</span>
                                <span class="font-bold text-slate-800"><?php echo e($transaction->service->name ?? 'N/A'); ?></span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-slate-100">
                                <span class="text-slate-500">Jumlah/Berat</span>
                                <span class="font-bold text-slate-800"><?php echo e($transaction->qty); ?> <?php echo e($transaction->service->unit ?? ''); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="flex justify-between items-center py-3 border-b border-slate-100">
                            <span class="text-slate-500">Tipe Pengantaran</span>
                            <span class="font-bold text-slate-800"><?php echo e($transaction->type == 'pickup_delivery' ? '🚚 Antar Jemput' : '🏢 Datang ke Outlet'); ?></span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-slate-100">
                            <span class="text-slate-500">Metode Pembayaran</span>
                            <span class="font-bold text-slate-800"><?php echo e(ucfirst($transaction->payment_method)); ?></span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-slate-100">
                            <span class="text-slate-500">Status Pembayaran</span>
                            <?php if($transaction->payment_status == '1'): ?>
                                <span class="font-bold text-red-600">Belum Bayar</span>
                            <?php elseif($transaction->payment_status == '2'): ?>
                                <span class="font-bold text-green-600">Lunas ✅</span>
                            <?php else: ?>
                                <span class="font-bold text-gray-400">Kadaluarsa</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Estimasi Waktu Selesai -->
                    <?php if($transaction->estimated_done_at && in_array($transaction->status, ['pending', 'process'])): ?>
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6" x-data="countdown('<?php echo e($transaction->estimated_done_at->toISOString()); ?>')" x-init="init()">
                            <p class="text-amber-800 font-bold text-center mb-2">⏱️ Estimasi Selesai</p>
                            <div class="flex justify-center gap-4 text-center">
                                <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                                    <span class="text-2xl font-black text-amber-600" x-text="hours">00</span>
                                    <p class="text-xs text-amber-600">Jam</p>
                                </div>
                                <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                                    <span class="text-2xl font-black text-amber-600" x-text="minutes">00</span>
                                    <p class="text-xs text-amber-600">Menit</p>
                                </div>
                                <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                                    <span class="text-2xl font-black text-amber-600" x-text="seconds">00</span>
                                    <p class="text-xs text-amber-600">Detik</p>
                                </div>
                            </div>
                            <p class="text-xs text-amber-600 text-center mt-2"><?php echo e($transaction->estimated_done_at->format('d M Y, H:i')); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Total -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-6 text-white text-center mb-8">
                        <p class="text-blue-100 text-sm mb-1">Total Pembayaran</p>
                        <p class="text-3xl font-black">Rp <?php echo e(number_format($transaction->total_price, 0, ',', '.')); ?></p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <?php if($transaction->payment_method == 'cash' && $transaction->payment_status == '1'): ?>
                            <!-- Cash Payment Info -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center">
                                <p class="text-yellow-800 font-bold">💰 Pembayaran Tunai</p>
                                <p class="text-yellow-700 text-sm mt-1">Silakan bayar langsung di outlet saat mengambil cucian.</p>
                            </div>
                        <?php elseif($transaction->payment_status == '1' && $transaction->snap_token && $transaction->status != 'cancelled'): ?>
                            <button onclick="payNow()" class="w-full bg-green-600 text-white font-bold text-lg py-4 rounded-xl hover:bg-green-700 transition shadow-lg">
                                💳 Bayar Sekarang
                            </button>
                        <?php endif; ?>

                        <?php if($transaction->payment_status == '2'): ?>
                            <a href="<?php echo e(route('transaction.print', $transaction->id)); ?>" class="block w-full bg-blue-600 text-white font-bold text-lg py-4 rounded-xl hover:bg-blue-700 transition shadow-lg text-center">
                                📄 Download Invoice
                            </a>
                        <?php endif; ?>

                        <?php if($transaction->status == 'pending' && $transaction->payment_status == '1'): ?>
                            <form action="<?php echo e(route('transaction.cancel', $transaction->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" onclick="return confirm('Yakin ingin membatalkan pesanan?')" class="w-full bg-red-100 text-red-600 font-bold text-lg py-4 rounded-xl hover:bg-red-200 transition">
                                    ❌ Batalkan Pesanan
                                </button>
                            </form>
                        <?php endif; ?>

                        <a href="<?php echo e(route('home')); ?>" class="block w-full bg-slate-100 text-slate-700 font-bold text-lg py-4 rounded-xl hover:bg-slate-200 transition text-center">
                            ← Kembali ke Beranda
                        </a>
                    </div>

                    <!-- Review Form (Only for taken orders) -->
                    <?php if($transaction->status == 'taken' && auth()->check() && $transaction->user_id == auth()->id()): ?>
                        <?php
                            $existingReview = $transaction->review;
                        ?>
                        
                        <?php if(!$existingReview): ?>
                            <div class="mt-8 bg-gradient-to-br from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-6">
                                <h3 class="text-lg font-bold text-slate-800 mb-4 text-center">⭐ Berikan Rating</h3>
                                <form action="<?php echo e(route('review.store')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="transaction_id" value="<?php echo e($transaction->id); ?>">
                                    
                                    <!-- Star Rating -->
                                    <div class="flex justify-center gap-2 mb-4" x-data="{ rating: 0 }">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <button type="button" 
                                                    @click="rating = <?php echo e($i); ?>"
                                                    class="text-4xl transition transform hover:scale-110"
                                                    :class="rating >= <?php echo e($i); ?> ? 'text-yellow-400' : 'text-gray-300'">
                                                ★
                                            </button>
                                        <?php endfor; ?>
                                        <input type="hidden" name="rating" x-model="rating" required>
                                    </div>
                                    
                                    <!-- Comment -->
                                    <textarea name="comment" rows="3" 
                                        class="w-full bg-white border border-yellow-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-yellow-400 transition mb-4"
                                        placeholder="Ceritakan pengalaman Anda... (opsional)"></textarea>
                                    
                                    <button type="submit" class="w-full bg-yellow-500 text-white font-bold py-3 rounded-xl hover:bg-yellow-600 transition">
                                        Kirim Review
                                    </button>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="mt-8 bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                                <p class="text-green-700 font-bold">✅ Terima kasih sudah memberikan review!</p>
                                <div class="flex justify-center gap-1 mt-2">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <span class="text-2xl <?php echo e($i <= $existingReview->rating ? 'text-yellow-400' : 'text-gray-300'); ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                <!-- Search Form -->
                <div class="bg-white rounded-[2rem] shadow-xl p-8 lg:p-10 border border-slate-100">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-black text-slate-900 mb-2">Lacak Pesanan</h2>
                        <p class="text-slate-500">Masukkan ID pesanan untuk melihat status</p>
                    </div>

                    <form action="<?php echo e(route('tracking')); ?>" method="GET" class="space-y-4">
                        <input type="number" name="id" placeholder="Masukkan ID Pesanan (contoh: 123)" required
                            class="w-full bg-slate-50 border-0 rounded-xl px-5 py-4 font-medium focus:ring-2 focus:ring-blue-600 transition text-center text-lg">
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold text-lg py-4 rounded-xl hover:bg-blue-700 transition shadow-lg">
                            🔍 Cari Pesanan
                        </button>
                    </form>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 py-8 text-center mt-auto">
        <p class="text-slate-400 text-sm">&copy; <?php echo e(date('Y')); ?> Laundry U9 Indonesia.</p>
    </footer>

    <?php if($transaction && $transaction->snap_token): ?>
    <form id="paidForm" action="<?php echo e(route('transaction.paid', $transaction->id)); ?>" method="POST" style="display:none;">
        <?php echo csrf_field(); ?>
    </form>
    <script>
        function payNow() {
            snap.pay('<?php echo e($transaction->snap_token); ?>', {
                onSuccess: function(result) {
                    // Submit form untuk update status pembayaran
                    document.getElementById('paidForm').submit();
                },
                onPending: function(result) {
                    alert('Menunggu pembayaran... Silakan selesaikan pembayaran.');
                },
                onError: function(result) {
                    alert('Pembayaran gagal! Silakan coba lagi.');
                },
                onClose: function() {
                    console.log('Popup ditutup');
                }
            });
        }
    </script>
    <?php endif; ?>

</body>
</html><?php /**PATH C:\laragon\www\laundr_app-main\resources\views/tracking.blade.php ENDPATH**/ ?>