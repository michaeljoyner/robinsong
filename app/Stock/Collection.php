<?php

namespace App\Stock;

use App\HasModelImage;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Collection extends Model implements SluggableInterface, HasMediaConversions
{
    use SluggableTrait, HasModelImage, HasMediaTrait;

    public $defaultImageSrc = '/images/assets/default.png';

    protected $table = 'collections';

    protected $fillable = [
        'name',
        'description'
    ];

    protected $sluggable = [
        'build_from' => 'name',
        'save_to'    => 'slug'
    ];

    public function registerMediaConversions()
    {
        $this->addMediaConversion('thumb')
            ->setManipulations(['w' => 200, 'h' => 200, 'fit' => 'crop'])
            ->performOnCollections('default');

        $this->addMediaConversion('web')
            ->setManipulations(['w' => 500, 'h' => 600])
            ->performOnCollections('default');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'collection_id');
    }

    public function addCategory($attributes)
    {
        return $this->categories()->create($attributes);
    }

    public function coverPic($conversion = null)
    {
        return $this->modelImage() ? $this->modelImage()->getUrl($conversion ? $conversion : '') : $this->defaultImageSrc;
    }
}
