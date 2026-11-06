<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'package_id',
        'billing_month',
        'period_start',
        'period_end',
        'due_date',
        'amount',
        'status',
        'paid_at',
        'payment_method',
        'payment_reference',
        'payment_proof',
        'proof_uploaded_at',
        'rejection_reason',
        'received_by_user_id',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'due_date' => 'date',
            'paid_at' => 'datetime',
            'proof_uploaded_at' => 'datetime',
            'amount' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by_user_id');
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp'.number_format($this->amount, 0, ',', '.');
    }

    public function getStatusBadgeAttribute(): array
    {
        if ($this->status === 'unpaid' && !empty($this->payment_proof)) {
            return ['label' => 'Menunggu Verifikasi', 'class' => 'bg-amber-500/10 text-amber-400 border-amber-500/30'];
        }

        return match ($this->status) {
            'paid' => ['label' => 'Berhasil (Lunas)', 'class' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30'],
            'unpaid' => ['label' => 'Menunggu Pembayaran', 'class' => 'bg-rose-500/10 text-rose-400 border-rose-500/30'],
            'cancelled' => ['label' => 'Ditolak / Dibatalkan', 'class' => 'bg-slate-500/10 text-slate-400 border-slate-500/30'],
            default => ['label' => ucfirst($this->status), 'class' => 'bg-slate-500/10 text-slate-400 border-slate-500/30'],
        };
    }

    /**
     * Mark invoice as paid and automatically activate the customer internet service
     */
    public function markAsPaid(string $paymentMethod = 'Tunai ke Mitra', ?string $reference = null, ?int $receivedByUserId = null): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $paymentMethod,
            'payment_reference' => $reference,
            'received_by_user_id' => $receivedByUserId,
        ]);

        // If customer was pending or isolated, activate internet service
        if ($this->customer && in_array($this->customer->status, ['isolated', 'pending'])) {
            $this->customer->update([
                'status' => 'active',
                'installation_date' => $this->customer->installation_date ?? now(),
            ]);
        }
    }
}
