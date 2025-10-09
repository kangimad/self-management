<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class FinanceSourceType extends Model
{
    protected $table = 'finance_source_types';
    protected $guarded = ['id'];

    /**
     * Finance source type has many sources
     */
    public function financeSources()
    {
        return $this->hasMany(FinanceSource::class, 'finance_source_type_id');
    }

    /**
     * Legacy method for backward compatibility
     */
    public function sources()
    {
        return $this->hasMany(FinanceSource::class, 'source_type_id');
    }
}
