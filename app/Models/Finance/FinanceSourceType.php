<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class FinanceSourceType extends Model
{
    protected $table = 'finance_source_types';
    protected $guarded = ['id'];

    public function sources()
    {
        return $this->hasMany(FinanceSource::class, 'source_type_id');
    }
}
