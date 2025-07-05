<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_number',
        'total_amount',
        'shipping_address',
        'payment_method',
        'payment_proof',
        'shipping_receipt_number',
        'status',
        'uuid',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->uuid)) {
                $order->uuid = Str::uuid()->toString();
            }
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
