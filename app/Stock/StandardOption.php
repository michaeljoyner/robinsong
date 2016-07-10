<?php

namespace App\Stock;

use Illuminate\Database\Eloquent\Model;

class StandardOption extends Model
{
    protected $table = 'standard_options';

    protected $fillable = ['name'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function($option) {
            $option->values->each(function($value) {
                $value->delete();
            });

            return true;
        });
    }

    public function values()
    {
        return $this->hasMany(StandardOptionValue::class, 'standard_option_id');
    }

    public function addValue($name)
    {
        return $this->values()->create(['name' => $name]);
    }
}
