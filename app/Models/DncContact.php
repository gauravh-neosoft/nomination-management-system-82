<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DncContact extends Model
{
    protected $table = 'dnc_contacts';
    protected $fillable = ['name', 'email'];
}
