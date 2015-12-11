<?php

namespace App\Stock;

use App\HasModelImage;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Category extends Model implements SluggableInterface, HasMediaConversions
{
    use SluggableTrait, HasMediaTrait, HasModelImage;

    public $defaultImageSrc = '/images/assets/default.png';

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description'
    ];

    protected $sluggable = [
        'build_from' => 'name',
        'save_to' => 'slug'
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

    public function collection()
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function addProduct($attributes)
    {
        return $this->products()->create($attributes);
    }

    public function coverPic($conversion = null)
    {
        return $this->modelImage() ? $this->modelImage()->getUrl($conversion ? $conversion : '') : $this->defaultImageSrc;
    }
}
