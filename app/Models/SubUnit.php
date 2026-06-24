<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubUnit extends Model
{
    use SoftDeletes;

    protected $fillable = ['unit_id', 'name', 'is_active', 'created_by', 'last_updated_by'];

    /**
     * Get the parent unit that owns this sub unit.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
