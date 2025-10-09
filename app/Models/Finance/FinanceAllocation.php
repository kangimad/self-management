<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FinanceAllocation extends Model
{
    protected $table = 'finance_allocations';
    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'allocation_date' => 'datetime',
    ];

    /**
     * Finance allocation belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Finance allocation belongs to a source (polymorphic)
     * Can be either FinanceSource or FinanceCategory
     */
    public function financeSource()
    {
        return $this->belongsTo(FinanceSource::class, 'finance_source_id');
    }

    /**
     * Finance allocation belongs to a category (polymorphic)
     * Can be either FinanceSource or FinanceCategory
     */
    public function financeCategory()
    {
        return $this->belongsTo(FinanceCategory::class, 'finance_category_id');
    }

    /**
     * Finance allocation has target source
     */
    public function targetSource()
    {
        return $this->belongsTo(FinanceSource::class, 'target_source_id');
    }

    /**
     * Finance allocation has target category
     */
    public function targetCategory()
    {
        return $this->belongsTo(FinanceCategory::class, 'target_category_id');
    }

    /**
     * Get the source of allocation (either finance_source or finance_category)
     */
    public function getAllocationSource()
    {
        if ($this->finance_source_id) {
            return $this->financeSource;
        }

        if ($this->finance_category_id) {
            return $this->financeCategory;
        }

        return null;
    }

    /**
     * Get the target of allocation (either target_source or target_category)
     */
    public function getAllocationTarget()
    {
        if ($this->target_source_id) {
            return $this->targetSource;
        }

        if ($this->target_category_id) {
            return $this->targetCategory;
        }

        return null;
    }

    /**
     * Check if allocation is from source to source
     */
    public function isSourceToSource(): bool
    {
        return $this->finance_source_id && $this->target_source_id;
    }

    /**
     * Check if allocation is from source to category
     */
    public function isSourceToCategory(): bool
    {
        return $this->finance_source_id && $this->target_category_id;
    }

    /**
     * Check if allocation is from category to source
     */
    public function isCategoryToSource(): bool
    {
        return $this->finance_category_id && $this->target_source_id;
    }

    /**
     * Check if allocation is from category to category
     */
    public function isCategoryToCategory(): bool
    {
        return $this->finance_category_id && $this->target_category_id;
    }
}
