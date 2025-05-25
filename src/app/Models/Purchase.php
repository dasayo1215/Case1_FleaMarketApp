<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'product_id',
        'purchase_price',
        'payment_method_id',
        'postal_code',
        'address',
        'building',
        'completed_at',
    ];

    // 日時としてキャストするカラム
    protected $casts = [
        'completed_at' => 'datetime',
    ];
}
