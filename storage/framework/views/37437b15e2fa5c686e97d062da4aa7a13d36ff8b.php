<?php if (isset($component)) { $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\AppLayout::class, []); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Kelola Voucher')); ?>

            </h2>
            <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah Voucher
            </button>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <?php if(session('success')): ?>
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                     class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 transition-opacity">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Diskon</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Min. Order</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Kuota</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Berlaku</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $vouchers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voucher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-mono font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded"><?php echo e($voucher->code); ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if($voucher->discount_type == 'percent'): ?>
                                            <span class="text-green-600 font-bold"><?php echo e($voucher->discount_value); ?>%</span>
                                            <?php if($voucher->max_discount): ?>
                                                <span class="text-gray-400 text-xs">(Max <?php echo e(number_format($voucher->max_discount)); ?>)</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-green-600 font-bold">Rp <?php echo e(number_format($voucher->discount_value)); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <?php echo e($voucher->min_order ? 'Rp ' . number_format($voucher->min_order) : '-'); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <?php if($voucher->quota): ?>
                                            <span class="<?php echo e($voucher->used_count >= $voucher->quota ? 'text-red-500' : 'text-gray-600'); ?>">
                                                <?php echo e($voucher->used_count); ?>/<?php echo e($voucher->quota); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-400">Unlimited</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <?php echo e($voucher->valid_from->format('d/m/Y')); ?> - <?php echo e($voucher->valid_until->format('d/m/Y')); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if($voucher->is_active && $voucher->valid_until >= now()): ?>
                                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">Aktif</span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-500">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex gap-2">
                                            <button onclick="editVoucher(<?php echo e($voucher->id); ?>, '<?php echo e($voucher->code); ?>', '<?php echo e($voucher->discount_type); ?>', <?php echo e($voucher->discount_value); ?>, <?php echo e($voucher->min_order ?? 0); ?>, <?php echo e($voucher->max_discount ?? 0); ?>, <?php echo e($voucher->quota ?? 'null'); ?>, '<?php echo e($voucher->valid_from->format('Y-m-d')); ?>', '<?php echo e($voucher->valid_until->format('Y-m-d')); ?>', <?php echo e($voucher->is_active ? 'true' : 'false'); ?>)"
                                                    class="text-blue-600 hover:text-blue-800 font-medium">Edit</button>
                                            <form action="<?php echo e(route('admin.vouchers.destroy', $voucher->id)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" onclick="return confirm('Hapus voucher ini?')" 
                                                        class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <div class="text-4xl mb-2">🎟️</div>
                                        Belum ada voucher. Klik "Tambah Voucher" untuk membuat.
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

    <!-- Modal Create/Edit -->
    <div id="voucherModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Tambah Voucher</h3>
            </div>
            <form id="voucherForm" method="POST" action="<?php echo e(route('admin.vouchers.store')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Kode Voucher</label>
                        <input type="text" name="code" id="vCode" required 
                               class="w-full border rounded-lg px-4 py-2 uppercase" placeholder="HEMAT10">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Tipe Diskon</label>
                            <select name="discount_type" id="vDiscountType" class="w-full border rounded-lg px-4 py-2">
                                <option value="percent">Persentase (%)</option>
                                <option value="fixed">Nominal (Rp)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nilai Diskon</label>
                            <input type="number" name="discount_value" id="vDiscountValue" required 
                                   class="w-full border rounded-lg px-4 py-2" placeholder="10">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Min. Order (Rp)</label>
                            <input type="number" name="min_order" id="vMinOrder" 
                                   class="w-full border rounded-lg px-4 py-2" placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Max Diskon (Rp)</label>
                            <input type="number" name="max_discount" id="vMaxDiscount" 
                                   class="w-full border rounded-lg px-4 py-2" placeholder="0">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Kuota (kosongkan = unlimited)</label>
                        <input type="number" name="quota" id="vQuota" 
                               class="w-full border rounded-lg px-4 py-2" placeholder="100">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Berlaku Dari</label>
                            <input type="date" name="valid_from" id="vValidFrom" required 
                                   class="w-full border rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Berlaku Sampai</label>
                            <input type="date" name="valid_until" id="vValidUntil" required 
                                   class="w-full border rounded-lg px-4 py-2">
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="vIsActive" value="1" checked 
                               class="rounded border-gray-300">
                        <label for="vIsActive" class="text-sm font-medium text-gray-700">Aktifkan voucher</label>
                    </div>
                </div>
                <div class="p-6 border-t bg-gray-50 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 text-gray-600 font-medium hover:bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('voucherModal').classList.remove('hidden');
            document.getElementById('voucherModal').classList.add('flex');
            document.getElementById('modalTitle').textContent = 'Tambah Voucher';
            document.getElementById('voucherForm').action = '<?php echo e(route("admin.vouchers.store")); ?>';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('voucherForm').reset();
            document.getElementById('vIsActive').checked = true;
        }

        function closeModal() {
            document.getElementById('voucherModal').classList.add('hidden');
            document.getElementById('voucherModal').classList.remove('flex');
        }

        function editVoucher(id, code, discountType, discountValue, minOrder, maxDiscount, quota, validFrom, validUntil, isActive) {
            openModal();
            document.getElementById('modalTitle').textContent = 'Edit Voucher';
            document.getElementById('voucherForm').action = `/admin/vouchers/${id}`;
            document.getElementById('formMethod').value = 'PUT';
            
            document.getElementById('vCode').value = code;
            document.getElementById('vDiscountType').value = discountType;
            document.getElementById('vDiscountValue').value = discountValue;
            document.getElementById('vMinOrder').value = minOrder || '';
            document.getElementById('vMaxDiscount').value = maxDiscount || '';
            document.getElementById('vQuota').value = quota || '';
            document.getElementById('vValidFrom').value = validFrom;
            document.getElementById('vValidUntil').value = validUntil;
            document.getElementById('vIsActive').checked = isActive;
        }

        // Close modal on outside click
        document.getElementById('voucherModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\Users\achma\Documents\PROYEK 2\laundr_app\resources\views/admin/vouchers.blade.php ENDPATH**/ ?>