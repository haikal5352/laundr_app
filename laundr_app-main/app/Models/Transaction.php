<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'service_id',
        'user_id',
        'qty',
        'total_price',
        'type',
        'address',
        'status',
        // ... kolom lain ...
        'payment_method',
        'snap_token',      // <--- Tambahkan ini
        'payment_status',  // <--- Tambahkan ini
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
