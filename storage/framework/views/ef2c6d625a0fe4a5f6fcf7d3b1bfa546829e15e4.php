

<?php $__env->startSection('title', 'Voucher & Promo - Laundry U9'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-b from-slate-50 to-white py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <span class="text-blue-600 font-bold tracking-widest uppercase text-sm">🎁 Promo Spesial</span>
            <h1 class="text-4xl font-black text-slate-900 mt-2">Voucher & Diskon</h1>
            <p class="text-slate-500 mt-2">Gunakan kode voucher saat checkout untuk mendapatkan potongan harga!</p>
        </div>

        <!-- Voucher Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $vouchers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voucher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-slate-100 hover:shadow-xl transition duration-300"
                 x-data="{ copied: false }">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 text-white relative">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                    <p class="text-white/80 text-xs font-bold uppercase tracking-wider">Kode Voucher</p>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-2xl font-black tracking-wider"><?php echo e($voucher->code); ?></span>
                        <button @click="navigator.clipboard.writeText('<?php echo e($voucher->code); ?>'); copied = true; setTimeout(() => copied = false, 2000)"
                                class="bg-white/20 hover:bg-white/30 text-white px-3 py-1 rounded-lg text-sm font-bold transition flex items-center gap-1">
                            <span x-show="!copied">📋 Salin</span>
                            <span x-show="copied">✅ Tersalin!</span>
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="px-6 py-5">
                    <!-- Discount Info -->
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-2xl">
                            <?php if($voucher->discount_type == 'percent'): ?>
                                %
                            <?php else: ?>
                                💰
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="text-slate-400 text-xs font-medium">Diskon</p>
                            <p class="text-xl font-black text-slate-900">
                                <?php if($voucher->discount_type == 'percent'): ?>
                                    <?php echo e($voucher->discount_value); ?>%
                                    <?php if($voucher->max_discount): ?>
                                        <span class="text-sm font-normal text-slate-400">(Max Rp <?php echo e(number_format($voucher->max_discount, 0, ',', '.')); ?>)</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    Rp <?php echo e(number_format($voucher->discount_value, 0, ',', '.')); ?>

                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="space-y-2 text-sm">
                        <?php if($voucher->min_order): ?>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Minimum Order</span>
                            <span class="font-bold text-slate-700">Rp <?php echo e(number_format($voucher->min_order, 0, ',', '.')); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Berlaku Hingga</span>
                            <span class="font-bold text-slate-700"><?php echo e($voucher->valid_until->format('d M Y')); ?></span>
                        </div>
                        <?php if($voucher->quota): ?>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Sisa Kuota</span>
                            <span class="font-bold <?php echo e(($voucher->quota - $voucher->used_count) <= 5 ? 'text-red-500' : 'text-green-600'); ?>">
                                <?php echo e($voucher->quota - $voucher->used_count); ?> tersisa
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-slate-50 px-6 py-3 border-t border-slate-100">
                    <a href="<?php echo e(route('home')); ?>#order-form" class="text-blue-600 font-bold text-sm hover:underline flex items-center gap-1">
                        Gunakan Sekarang →
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full text-center py-16 bg-white rounded-2xl border-2 border-dashed border-slate-200">
                <div class="text-6xl mb-4">🎟️</div>
                <h3 class="text-lg font-bold text-slate-700">Belum Ada Voucher Aktif</h3>
                <p class="text-slate-400 mt-1">Nantikan promo menarik dari kami!</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-12">
            <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center gap-2 bg-slate-900 text-white font-bold px-8 py-4 rounded-xl hover:bg-slate-800 transition shadow-lg">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\achma\Documents\PROYEK 2\laundr_app\resources\views/vouchers/index.blade.php ENDPATH**/ ?>