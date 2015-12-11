<?php

namespace App\Stock;

use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    protected $table = 'product_options';

    protected $fillable = [
        'name'
    ];

    public function values()
    {
        return $this->hasMany(OptionValue::class, 'product_option_id');
    }

    public function addValue($name)
    {
        return $this->values()->create(['name' => $name]);
    }
}
