<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'min_order',
        'max_discount',
        'quota',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    // Check if voucher is valid
    public function isValid($orderTotal = 0)
    {
        $now = Carbon::now();
        
        // Check active
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Voucher tidak aktif'];
        }
        
        // Check date validity (use startOfDay and endOfDay for tolerance)
        $validFrom = Carbon::parse($this->valid_from)->startOfDay();
        $validUntil = Carbon::parse($this->valid_until)->endOfDay();
        
        if ($now->lt($validFrom) || $now->gt($validUntil)) {
            return ['valid' => false, 'message' => 'Voucher sudah expired atau belum berlaku'];
        }
        
        // Check quota
        if ($this->quota !== null && $this->used_count >= $this->quota) {
            return ['valid' => false, 'message' => 'Kuota voucher sudah habis'];
        }
        
        // Check minimum order
        if ($this->min_order && $orderTotal < $this->min_order) {
            return ['valid' => false, 'message' => 'Minimum order Rp ' . number_format($this->min_order, 0, ',', '.')];
        }
        
        return ['valid' => true, 'message' => 'Voucher valid'];
    }

    // Calculate discount
    public function calculateDiscount($orderTotal)
    {
        if ($this->discount_type === 'percent') {
            $discount = ($this->discount_value / 100) * $orderTotal;
            // Apply max discount cap
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
        } else {
            $discount = $this->discount_value;
        }
        
        // Discount can't exceed order total
        return min($discount, $orderTotal);
    }
}
