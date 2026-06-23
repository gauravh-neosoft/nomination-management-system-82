<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'is_active', 'created_by', 'updated_by'];

    /**
     * Get the sub units associated with this business unit.
     */
    public function subUnits(): HasMany
    {
        return $this->hasMany(SubUnit::class, 'unit_id');
    }
}
