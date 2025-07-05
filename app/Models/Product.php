<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'uuid',
    ];

    /**
     * The "booted" method of the model.
     * Otomatis membuat UUID dan Slug saat produk baru dibuat.
     */
    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            $product->uuid = Str::uuid()->toString();
            $product->slug = Str::slug($product->name);
        });

        static::updating(function (Product $product) {
            $product->slug = Str::slug($product->name);
        });
    }

    /**
     * Mendefinisikan relasi ke model Category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
