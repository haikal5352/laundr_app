<?php if (isset($component)) { $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\AppLayout::class, []); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Ulasan Pelanggan')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

<div class="py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <span class="text-yellow-500 font-bold tracking-widest uppercase text-sm">⭐ Ulasan Pelanggan</span>
            <h1 class="text-4xl font-black text-slate-900 mt-2">Apa Kata Mereka?</h1>
            <p class="text-slate-500 mt-2">Testimoni asli dari pelanggan setia Laundry U9</p>
        </div>

        <!-- Write Review Button -->
        <?php if(auth()->guard()->check()): ?>
        <div class="text-center mb-8">
            <button onclick="document.getElementById('writeReviewSection').scrollIntoView({behavior: 'smooth'})" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition">
                ✍️ Tulis Ulasan
            </button>
        </div>
        <?php endif; ?>

        <!-- Reviews Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-slate-100 hover:shadow-xl transition">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-lg">
                        <?php echo e(substr($review->user->name ?? 'G', 0, 1)); ?>

                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900"><?php echo e($review->user->name ?? 'Pelanggan'); ?></h4>
                        <p class="text-xs text-slate-400"><?php echo e($review->created_at->diffForHumans()); ?></p>
                    </div>
                </div>
                <div class="flex gap-1 text-yellow-400 mb-3">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <span class="text-xl"><?php echo e($i <= $review->rating ? '★' : '☆'); ?></span>
                    <?php endfor; ?>
                </div>
                <p class="text-slate-600 leading-relaxed">"<?php echo e($review->comment ?? 'Pelayanan sangat memuaskan!'); ?>"</p>
                <?php if($review->transaction): ?>
                <p class="text-xs text-slate-400 mt-3">
                    Layanan: <?php echo e($review->transaction->service->name ?? 'Laundry'); ?>

                </p>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full text-center py-16 bg-white rounded-2xl border-2 border-dashed border-slate-200">
                <div class="text-6xl mb-4">💬</div>
                <h3 class="text-lg font-bold text-slate-700">Belum Ada Ulasan</h3>
                <p class="text-slate-400 mt-1">Jadilah yang pertama memberikan ulasan!</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if($reviews->hasPages()): ?>
        <div class="mb-12">
            <?php echo e($reviews->links()); ?>

        </div>
        <?php endif; ?>

        <!-- Write Review Section -->
        <?php if(auth()->guard()->check()): ?>
        <div id="writeReviewSection" class="bg-white rounded-2xl shadow-lg p-8 border border-slate-100">
            <h3 class="text-2xl font-black text-slate-900 mb-6">✍️ Tulis Ulasan Anda</h3>
            
            <?php if(session('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php if($eligibleTransactions->count() > 0): ?>
            <form action="<?php echo e(route('review.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="space-y-6">
                    <!-- Select Transaction -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Pesanan yang Ingin Direview</label>
                        <select name="transaction_id" required class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Pesanan --</option>
                            <?php $__currentLoopData = $eligibleTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($tx->id); ?>">
                                    #<?php echo e($tx->id); ?> - <?php echo e($tx->service->name ?? 'Laundry'); ?> (<?php echo e($tx->created_at->format('d M Y')); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Rating Stars -->
                    <div x-data="{ rating: 5 }">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Rating</label>
                        <div class="flex gap-2">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                            <button type="button" @click="rating = <?php echo e($i); ?>" 
                                    :class="rating >= <?php echo e($i); ?> ? 'text-yellow-400' : 'text-gray-300'"
                                    class="text-4xl hover:scale-110 transition">
                                ★
                            </button>
                            <?php endfor; ?>
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
            <?php else: ?>
            <div class="text-center py-8 bg-slate-50 rounded-xl">
                <div class="text-4xl mb-3">📦</div>
                <p class="text-slate-600 font-medium">Anda belum memiliki pesanan yang selesai untuk direview.</p>
                <p class="text-slate-400 text-sm mt-1">Selesaikan pesanan terlebih dahulu untuk bisa memberikan ulasan.</p>
                <a href="<?php echo e(route('home')); ?>" class="inline-block mt-4 bg-blue-600 text-white font-bold px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Buat Pesanan
                </a>
            </div>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-slate-100 text-center">
            <div class="text-4xl mb-3">🔐</div>
            <p class="text-slate-600 font-medium mb-4">Login untuk memberikan ulasan</p>
            <a href="<?php echo e(route('login')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition inline-block">
                Login Sekarang
            </a>
        </div>
        <?php endif; ?>

        <!-- Back Button -->
        <div class="text-center mt-12">
            <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 font-medium">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\Users\achma\Documents\PROYEK 2\laundr_app\resources\views/reviews/index.blade.php ENDPATH**/ ?>