<?php

namespace App\Stock;

use Illuminate\Database\Eloquent\Model;

class OptionValue extends Model
{
    protected $table = 'option_values';

    protected $fillable = [
        'name'
    ];
}
