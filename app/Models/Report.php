<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'district_id',
        'title',
        'description',
        'images', // Changed from 'image' to 'images' (JSON array)
        'latitude',
        'longitude',
        'address',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'images' => 'array', // Cast JSON to array
    ];

    /**
     * Status laporan yang dikenal sistem.
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESS = 'process';
    public const STATUS_DONE = 'done';
    public const STATUS_REJECTED = 'rejected';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ReportImage::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Filter berdasarkan kategori (nama).
     */
    public function scopeOfCategory(Builder $query, ?string $category): Builder
    {
        return $category
            ? $query->whereHas('category', fn ($q) => $q->where('name', $category))
            : $query;
    }

    /**
     * Filter berdasarkan status.
     */
    public function scopeOfStatus(Builder $query, ?string $status): Builder
    {
        return $status ? $query->where('status', $status) : $query;
    }

    /**
     * Pencarian bebas pada judul/deskripsi/alamat.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $term
            ? $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('address', 'like', "%{$term}%");
            })
            : $query;
    }
}
