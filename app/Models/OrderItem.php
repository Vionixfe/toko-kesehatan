<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class OrderItem extends Model
    {
        use HasFactory;

        protected $fillable = [
            'order_id',
            'product_id',
            'quantity',
            'price',
        ];

        /**
         * Mendefinisikan relasi ke model Product.
         */
        public function product()
        {
            return $this->belongsTo(Product::class);
        }

        /**
         * Mendefinisikan relasi ke model Order.
         */
        public function order()
        {
            return $this->belongsTo(Order::class);
        }
    }
    