<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; font-family: Arial, sans-serif; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .total-row { background-color: #ffff00; font-weight: bold; }
    </style>
</head>
<body>
    <center>
        <h2>LAPORAN KEUANGAN LAUNDRY U9</h2>
        <p>Dicetak pada Tanggal: <?php echo e(date('d F Y')); ?></p>
    </center>
    <br>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Transaksi</th>
                <th>Nama Pelanggan</th>
                <th>Layanan</th>
                <th>Berat/Qty</th>
                <th>Total Harga</th>
                <th>Metode Pembayaran</th>
                <th>Status Cucian</th>
            </tr>
        </thead>
        <tbody>
            <?php $totalPemasukan = 0; ?>
            <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($t->created_at->format('d/m/Y H:i')); ?></td>
                    <td><?php echo e($t->customer_name); ?></td>
                    <td><?php echo e($t->service->name ?? '-'); ?></td>
                    <?php
                        $items = json_decode($t->items_data, true);
                        $isMulti = is_array($items) && count($items) > 1;
                        $unit = $isMulti ? 'Item' : ($t->service->unit ?? '');
                    ?>
                    <td><?php echo e($t->qty); ?> <?php echo e($unit); ?></td>
                    <td>Rp <?php echo e(number_format($t->total_price, 0, ',', '.')); ?></td>
                    <td><?php echo e(strtoupper($t->payment_method)); ?></td>
                    <td>
                        <?php if($t->status == 'pending'): ?> Pending
                        <?php elseif($t->status == 'process'): ?> Sedang Dicuci
                        <?php elseif($t->status == 'done'): ?> Selesai
                        <?php elseif($t->status == 'taken'): ?> Diambil
                        <?php else: ?> <?php echo e($t->status); ?>

                        <?php endif; ?>
                    </td>
                </tr>
                <?php $totalPemasukan += $t->total_price; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <tr class="total-row">
                <td colspan="5" style="text-align: right;">TOTAL PEMASUKAN BERSIH</td>
                <td colspan="3">Rp <?php echo e(number_format($totalPemasukan, 0, ',', '.')); ?></td>
            </tr>
        </tbody>
    </table>
</body>
</html><?php /**PATH C:\Users\achma\Documents\PROYEK 2\laundr_app\resources\views/admin/export.blade.php ENDPATH**/ ?>