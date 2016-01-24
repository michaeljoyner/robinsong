<?php

namespace App\Stock;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = ['name'];

    public static function makeTag($data)
    {
        if(! array_key_exists('name', $data)) {
            throw new InvalidArgumentException('The name attribute must be provided');
        }

        $data['name'] = mb_strtolower($data['name']);

        $existing = static::where('name', $data['name'])->first();

        return $existing ? $existing : static::create($data);
    }
}
