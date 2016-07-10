<?php

namespace App\Stock;

use Illuminate\Database\Eloquent\Model;

class StandardOptionValue extends Model
{
    protected $table = 'standard_option_values';

    protected $fillable = ['name'];
}
