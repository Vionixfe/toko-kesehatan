<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Str;

    class Cart extends Model
    {
        use HasFactory;

        protected $fillable = [
            'user_id',
            'product_id',
            'quantity',
            'uuid',
        ];

        protected static function booted(): void
        {
            static::creating(function (Cart $cart) {
                if (empty($cart->uuid)) {
                    $cart->uuid = Str::uuid()->toString();
                }
            });
        }

        public function product()
        {
            return $this->belongsTo(Product::class);
        }
    }
    