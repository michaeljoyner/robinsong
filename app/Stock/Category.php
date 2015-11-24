<?php

namespace App\Stock;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;

class Category extends Model implements SluggableInterface
{
    use SluggableTrait;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description'
    ];

    protected $sluggable = [
        'build_from' => 'name',
        'save_to' => 'slug'
    ];
}
