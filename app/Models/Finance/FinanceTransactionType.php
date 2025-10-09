<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class FinanceTransactionType extends Model
{
    protected $table = 'finance_transaction_types';
    protected $guarded = ['id'];

    /**
     * Finance transaction type has many transactions
     */
    public function financeTransactions()
    {
        return $this->hasMany(FinanceTransaction::class, 'finance_transaction_type_id');
    }

    /**
     * Check if this is income type
     */
    public function isIncome(): bool
    {
        return strtolower($this->name) === 'income';
    }

    /**
     * Check if this is expense type
     */
    public function isExpense(): bool
    {
        return strtolower($this->name) === 'expense';
    }

    /**
     * Check if this is transfer type
     */
    public function isTransfer(): bool
    {
        return strtolower($this->name) === 'transfer';
    }
}
