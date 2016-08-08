<?php

namespace App\Stock;

use Illuminate\Database\Eloquent\Model;

class Customisation extends Model
{
    protected $table = 'customisations';

    protected $fillable = [
        'name',
        'longform'
    ];

    protected $casts = ['longform' => 'boolean'];
}
