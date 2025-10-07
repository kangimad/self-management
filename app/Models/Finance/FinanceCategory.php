<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class FinanceCategory extends Model
{
    protected $table = 'finance_categories';
    protected $guarded = ['id'];

    public function categoryType()
    {
        return $this->belongsTo(FinanceCategoryType::class, 'category_type_id');
    }
}
