<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FinanceSource extends Model
{
    protected $table = 'finance_sources';
    protected $guarded = ['id'];

    /**
     * Finance source belongs to a source type
     */
    public function financeSourceType()
    {
        return $this->belongsTo(FinanceSourceType::class, 'finance_source_type_id');
    }

    /**
     * Legacy method for backward compatibility
     */
    public function sourceType()
    {
        return $this->belongsTo(FinanceSourceType::class, 'source_type_id');
    }

    /**
     * Finance source belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Finance source has many balances
     */
    public function financeBalances()
    {
        return $this->hasMany(FinanceBalance::class, 'finance_source_id');
    }

    /**
     * Finance source has many transactions (as source)
     */
    public function financeTransactions()
    {
        return $this->hasMany(FinanceTransaction::class, 'source_id');
    }

    /**
     * Finance source has many transactions (as target)
     */
    public function targetTransactions()
    {
        return $this->hasMany(FinanceTransaction::class, 'target_source_id');
    }

    /**
     * Get current balance for this source
     */
    public function getCurrentBalance(): float
    {
        return $this->financeBalances()->latest()->first()->amount ?? 0;
    }

    /**
     * Get latest balance record
     */
    public function latestBalance()
    {
        return $this->hasOne(FinanceBalance::class, 'finance_source_id')->latest();
    }
}
