<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // Tambahkan ini biar nanti gampang kalau mau Input Data Layanan Baru (Create)
    // $guarded = ['id'] artinya semua kolom boleh diisi kecuali ID.
    protected $guarded = ['id']; 

    // Tambahkan relasi kebalikan (Optional tapi bagus)
    // Artinya: Satu jenis Service bisa punya banyak Transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}