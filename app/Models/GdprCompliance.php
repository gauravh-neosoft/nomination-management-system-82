<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GdprCompliance extends Model
{
    use SoftDeletes;
    protected $table = 'gdpr_compliances';

    protected $fillable = ['name', 'is_active', 'created_by', 'updated_by'];
}
