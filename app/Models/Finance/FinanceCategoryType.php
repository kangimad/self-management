<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class FinanceCategoryType extends Model
{
    protected $table = 'finance_category_types';
    protected $guarded = ['id'];

    /**
     * Finance category type has many categories
     */
    public function financeCategories()
    {
        return $this->hasMany(FinanceCategory::class, 'finance_category_type_id');
    }

    /**
     * Legacy method for backward compatibility
     */
    public function categories()
    {
        return $this->hasMany(FinanceCategory::class, 'category_type_id');
    }
}
