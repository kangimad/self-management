<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FinanceBalance extends Model
{
    protected $table = 'finance_balances';
    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'previous_amount' => 'decimal:2',
        'balance_date' => 'datetime',
    ];

    /**
     * Finance balance belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Finance balance belongs to a transaction
     */
    public function financeTransaction()
    {
        return $this->belongsTo(FinanceTransaction::class, 'finance_transaction_id');
    }

    /**
     * Finance balance belongs to a source
     */
    public function financeSource()
    {
        return $this->belongsTo(FinanceSource::class, 'finance_source_id');
    }

    /**
     * Get the change amount (difference from previous balance)
     */
    public function getChangeAmountAttribute(): float
    {
        return $this->amount - ($this->previous_amount ?? 0);
    }

    /**
     * Check if balance increased
     */
    public function isIncrease(): bool
    {
        return $this->change_amount > 0;
    }

    /**
     * Check if balance decreased
     */
    public function isDecrease(): bool
    {
        return $this->change_amount < 0;
    }

    /**
     * Get the absolute change amount
     */
    public function getAbsoluteChangeAttribute(): float
    {
        return abs($this->change_amount);
    }

    /**
     * Scope for getting latest balance for each source
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope for getting balances for a specific source
     */
    public function scopeForSource($query, $sourceId)
    {
        return $query->where('finance_source_id', $sourceId);
    }

    /**
     * Scope for getting balances within date range
     */
    public function scopeWithinDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('balance_date', [$startDate, $endDate]);
    }
}
