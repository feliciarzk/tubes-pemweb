<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUSES = [
        'menunggu_pickup'     => 'Menunggu pickup',
        'menunggu_konfirmasi' => 'Menunggu konfirmasi admin',
        'menunggu_pembayaran' => 'Menunggu pembayaran',
        'diproses'            => 'Diproses',
        'diantar'             => 'Diantar',
        'selesai'             => 'Selesai',
    ];

    protected $fillable = [
        'code', 'user_id', 'driver_id', 'service', 'delivery_type', 'address_type',
        'address', 'pickup_date', 'pickup_time', 'notes', 'weight', 'price',
        'status', 'payment_method', 'paid_at',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'paid_at'     => 'datetime',
        'weight'      => 'decimal:2',
    ];

    public function user()   { return $this->belongsTo(User::class); }
    public function driver() { return $this->belongsTo(User::class, 'driver_id'); }

    public function getServiceNameAttribute(): string
    {
        return config("swash.services.{$this->service}.name", $this->service);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getDeliveryLabelAttribute(): string
    {
        return $this->delivery_type === 'antar_jemput' ? 'Antar jemput' : 'Antar saja';
    }

    public function getStatusStepAttribute(): int
    {
        return array_search($this->status, array_keys(self::STATUSES), true) ?: 0;
    }

    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
