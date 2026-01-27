<?php if (isset($component)) { $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\AppLayout::class, []); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Admin Dashboard')); ?>

            </h2>

            <a href="<?php echo e(route('admin.vouchers.index')); ?>" class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Kelola Voucher
            </a>

            <a href="<?php echo e(route('admin.export')); ?>" class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Laporan Excel
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-lg shadow-blue-100 rounded-2xl p-6 border-b-4 border-blue-600">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-blue-50 text-blue-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                            <p class="text-2xl font-black text-slate-800"><?php echo e($totalTransactions); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg shadow-green-100 rounded-2xl p-6 border-b-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-green-50 text-green-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pendapatan</p>
                            <p class="text-2xl font-black text-slate-800">Rp <?php echo e(number_format($income, 0, ',', '.')); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg shadow-orange-100 rounded-2xl p-6 border-b-4 border-orange-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-orange-50 text-orange-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pending</p>
                            <p class="text-2xl font-black text-slate-800"><?php echo e($pendingOrders); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg shadow-indigo-100 rounded-2xl p-6 border-b-4 border-indigo-600">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pelanggan</p>
                            <p class="text-2xl font-black text-slate-800"><?php echo e($totalCustomers); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <div class="mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Manajemen Pesanan</h3>
                    </div>

                    <?php if(session('success')): ?>
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded text-sm transition-opacity duration-500 mb-4">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Layanan</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status (Klik untuk Ubah)</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-gray-900"><?php echo e($transaction->customer_name); ?></div>
                                        <div class="text-xs text-gray-500">📞 <?php echo e($transaction->customer_phone); ?></div>
                                        <?php if($transaction->user): ?>
                                            <div class="text-xs text-blue-600">✉️ <?php echo e($transaction->user->email ?? '-'); ?></div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-100 text-blue-800 mt-1">
                                                Member
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600 mt-1">
                                                Guest
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <?php
                                            $items = is_string($transaction->items_data) ? json_decode($transaction->items_data, true) : $transaction->items_data;
                                        ?>
                                        <?php if($items && is_array($items) && count($items) > 0): ?>
                                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="text-sm"><?php echo e($item['service_name']); ?> <span class="text-gray-400">x<?php echo e($item['qty']); ?></span></div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <div class="text-xs text-gray-400"><?php echo e($transaction->type == 'pickup_delivery' ? '(Antar Jemput)' : '(Drop Off)'); ?></div>
                                        <?php else: ?>
                                            <?php echo e($transaction->service->name ?? '-'); ?>

                                            <div class="text-xs text-gray-400"><?php echo e($transaction->type == 'pickup_delivery' ? '(Antar Jemput)' : '(Drop Off)'); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">
                                        Rp <?php echo e(number_format($transaction->total_price, 0, ',', '.')); ?>

                                        <?php if($items && is_array($items) && count($items) > 1): ?>
                                            <div class="text-xs text-gray-400 font-normal"><?php echo e(count($items)); ?> layanan</div>
                                        <?php else: ?>
                                            <?php
                                                $items = json_decode($transaction->items_data, true);
                                                $isMulti = is_array($items) && count($items) > 1;
                                                $unit = $isMulti ? 'Item' : ($transaction->service->unit ?? '');
                                            ?>
                                            <div class="text-xs text-gray-400 font-normal"><?php echo e($transaction->qty); ?> <?php echo e($unit); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="<?php echo e(route('admin.transaction.update', $transaction->id)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            
                                            <select name="status" onchange="this.form.submit()" 
                                                class="text-xs font-bold rounded-full px-3 py-1 border-0 cursor-pointer focus:ring-2 focus:ring-blue-500 shadow-sm w-full
                                                <?php echo e($transaction->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                                <?php echo e($transaction->status == 'process' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                                <?php echo e($transaction->status == 'done' ? 'bg-green-100 text-green-800' : ''); ?>

                                                <?php echo e($transaction->status == 'taken' ? 'bg-gray-200 text-gray-800' : ''); ?>">
                                                
                                                <option value="pending" <?php echo e($transaction->status == 'pending' ? 'selected' : ''); ?>>🕒 Pending</option>
                                                <option value="process" <?php echo e($transaction->status == 'process' ? 'selected' : ''); ?>>🧼 Sedang Dicuci</option>
                                                <option value="done" <?php echo e($transaction->status == 'done' ? 'selected' : ''); ?>>✅ Selesai</option>
                                                <option value="taken" <?php echo e($transaction->status == 'taken' ? 'selected' : ''); ?>>👋 Sudah Diambil</option>
                                            </select>
                                        </form>

                                        <!-- Payment Status (For Cash) -->
                                        <div class="mt-2">
                                            <?php if($transaction->payment_status == '2'): ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-green-100 text-green-700">
                                                    ✅ Lunas
                                                </span>
                                            <?php elseif($transaction->payment_status == '1'): ?>
                                                <?php if($transaction->payment_method == 'cash'): ?>
                                                    <form action="<?php echo e(route('admin.transaction.payment', $transaction->id)); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PATCH'); ?>
                                                        <input type="hidden" name="payment_status" value="2">
                                                        <button type="submit" onclick="return confirm('Konfirmasi pembayaran tunai?')" 
                                                                class="text-xs bg-gray-800 text-white px-2 py-1 rounded hover:bg-gray-700 transition w-full mt-1">
                                                            💰 Tandai Lunas
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-red-100 text-red-700">
                                                        ⏳ Belum Bayar
                                                    </span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-xs text-gray-400">Kadaluarsa/Batal</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo e($transaction->created_at->format('d M Y H:i')); ?>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada pesanan masuk.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?><?php /**PATH C:\Users\achma\Documents\PROYEK 2\laundr_app\resources\views/admin/index.blade.php ENDPATH**/ ?>