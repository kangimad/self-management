<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FinanceCategory extends Model
{
    protected $table = 'finance_categories';
    protected $guarded = ['id'];

    /**
     * Finance category belongs to a category type
     */
    public function financeCategoryType()
    {
        return $this->belongsTo(FinanceCategoryType::class, 'finance_category_type_id');
    }

    /**
     * Legacy method for backward compatibility
     */
    public function categoryType()
    {
        return $this->belongsTo(FinanceCategoryType::class, 'category_type_id');
    }

    /**
     * Finance category belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Finance category has many allocations
     */
    public function financeAllocations()
    {
        return $this->hasMany(FinanceAllocation::class, 'finance_category_id');
    }

    /**
     * Finance category has many transactions
     */
    public function financeTransactions()
    {
        return $this->hasMany(FinanceTransaction::class, 'finance_category_id');
    }

    /**
     * Get total allocated amount for this category
     */
    public function getTotalAllocatedAmount(): float
    {
        return $this->financeAllocations()->sum('amount');
    }

    /**
     * Get total spent amount for this category
     */
    public function getTotalSpentAmount(): float
    {
        return $this->financeTransactions()
            ->whereHas('financeTransactionType', function ($query) {
                $query->where('name', 'Expense');
            })
            ->sum('amount');
    }

    /**
     * Get remaining budget for this category
     */
    public function getRemainingBudget(): float
    {
        return $this->getTotalAllocatedAmount() - $this->getTotalSpentAmount();
    }
}
