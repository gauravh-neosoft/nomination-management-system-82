<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubUnit extends Model
{
    protected $fillable = ['unit_id', 'name', 'is_active', 'created_by', 'updated_by'];

    /**
     * Get the parent unit that owns this sub unit.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
