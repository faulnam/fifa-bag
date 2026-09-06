<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemoActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'record_type',
        'record_id',
        'action',
        'original_data',
        'file_paths',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'original_data' => 'array',
            'file_paths' => 'array',
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '<=', now());
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('expires_at', '>', now());
    }
}
