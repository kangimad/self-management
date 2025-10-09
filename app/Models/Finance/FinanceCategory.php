<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FinanceCategory extends Model
{
    protected $table = 'finance_categories';
    protected $guarded = ['id'];

    public function categoryType()
    {
        return $this->belongsTo(FinanceCategoryType::class, 'category_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
