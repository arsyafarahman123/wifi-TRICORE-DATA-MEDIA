<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'name',
        'phone',
        'email',
        'identity_number',
        'address',
        'district',
        'subdistrict',
        'postal_code',
        'package_id',
        'status',
        'installation_date',
        'odp_code',
        'ip_address',
        'registered_by',
        'partner_id',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'installation_date' => 'date',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class)->latest();
    }

    public function latestInvoice()
    {
        return $this->hasOne(Invoice::class)->latestOfMany();
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'active' => ['label' => 'Aktif', 'class' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30'],
            'pending' => ['label' => 'Menunggu Pemasangan', 'class' => 'bg-amber-500/10 text-amber-400 border-amber-500/30'],
            'isolated' => ['label' => 'Terisolir', 'class' => 'bg-rose-500/10 text-rose-400 border-rose-500/30'],
            'cancelled' => ['label' => 'Berhenti Berlangganan', 'class' => 'bg-slate-500/10 text-slate-400 border-slate-500/30'],
            default => ['label' => ucfirst($this->status), 'class' => 'bg-slate-500/10 text-slate-400 border-slate-500/30'],
        };
    }
}
