<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FinanceSource extends Model
{
    protected $table = 'finance_sources';
    protected $guarded = ['id'];

    public function sourceType()
    {
        return $this->belongsTo(FinanceSourceType::class, 'source_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
