<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DncDomain extends Model
{
    protected $table = 'dnc_domains';
    protected $fillable = ['account_name', 'domain'];
}
