<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class FinanceCategoryType extends Model
{
    protected $table = 'finance_category_types';
    protected $guarded = ['id'];

    public function categories()
    {
        return $this->hasMany(FinanceCategory::class, 'category_type_id');
    }
}
