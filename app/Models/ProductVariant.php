<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'color_name',
        'color_hex',
        'size',
        'stock',
        'price_override',
        'is_active',
    ];

    protected $casts = [
        'stock' => 'integer',
        'price_override' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'variant_id');
    }

    public function getEffectivePriceAttribute(): string
    {
        return $this->price_override ?? $this->product->base_price;
    }

    public function getStockQuantityAttribute(): int
    {
        return (int) $this->stock;
    }
}
