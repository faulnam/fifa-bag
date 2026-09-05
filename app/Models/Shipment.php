<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'biteship_order_id',
        'courier_company',
        'courier_type',
        'tracking_id',
        'waybill_id',
        'status',
        'rate_snapshot',
        'pickup_scheduled_at',
    ];

    protected $casts = [
        'rate_snapshot' => 'array',
        'pickup_scheduled_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function trackings(): HasMany
    {
        return $this->hasMany(ShipmentTracking::class)->orderBy('occurred_at', 'desc');
    }
}
