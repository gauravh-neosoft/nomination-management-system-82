<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NominatorEventLimit extends Model
{
    use SoftDeletes;

    protected $table = 'nominator_event_limits';

    protected $fillable = [
        'event_id',
        'nominator_id',
        'max_nominees',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the event associated with this limit.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Events::class, 'event_id');
    }

    /**
     * Get the nominator associated with this limit.
     */
    public function nominator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nominator_id');
    }

    /**
     * Get the creator of this limit.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater of this limit.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
