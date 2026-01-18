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
        'payment_method',
        'snap_token',
        'payment_status',
        'items_data',
        'estimated_done_at',
        'voucher_code',
        'discount_amount',
    ];

    protected $casts = [
        'items_data' => 'array',
        'estimated_done_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}

