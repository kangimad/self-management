<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FinanceTransaction extends Model
{
    protected $table = 'finance_transactions';
    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    /**
     * Finance transaction belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Finance transaction belongs to a transaction type
     */
    public function financeTransactionType()
    {
        return $this->belongsTo(FinanceTransactionType::class, 'finance_transaction_type_id');
    }

    /**
     * Finance transaction belongs to a source (wallet/bank)
     */
    public function financeSource()
    {
        return $this->belongsTo(FinanceSource::class, 'source_id');
    }

    /**
     * Finance transaction belongs to a target source (for transfers)
     */
    public function targetFinanceSource()
    {
        return $this->belongsTo(FinanceSource::class, 'target_source_id');
    }

    /**
     * Finance transaction belongs to a category
     */
    public function financeCategory()
    {
        return $this->belongsTo(FinanceCategory::class, 'finance_category_id');
    }

    /**
     * Finance transaction has one balance record
     */
    public function financeBalance()
    {
        return $this->hasOne(FinanceBalance::class, 'finance_transaction_id');
    }

    /**
     * Scope for income transactions
     */
    public function scopeIncome($query)
    {
        return $query->whereHas('financeTransactionType', function ($q) {
            $q->where('name', 'Income');
        });
    }

    /**
     * Scope for expense transactions
     */
    public function scopeExpense($query)
    {
        return $query->whereHas('financeTransactionType', function ($q) {
            $q->where('name', 'Expense');
        });
    }

    /**
     * Scope for transfer transactions
     */
    public function scopeTransfer($query)
    {
        return $query->whereHas('financeTransactionType', function ($q) {
            $q->where('name', 'Transfer');
        });
    }

    /**
     * Get transaction type name
     */
    public function getTypeNameAttribute(): string
    {
        return $this->financeTransactionType->name ?? 'Unknown';
    }

    /**
     * Check if transaction is income
     */
    public function isIncome(): bool
    {
        return $this->type_name === 'Income';
    }

    /**
     * Check if transaction is expense
     */
    public function isExpense(): bool
    {
        return $this->type_name === 'Expense';
    }

    /**
     * Check if transaction is transfer
     */
    public function isTransfer(): bool
    {
        return $this->type_name === 'Transfer';
    }
}
