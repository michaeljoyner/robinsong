<?php

namespace App\Stock;

use App\HasModelImage;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Product extends Model implements SluggableInterface, HasMediaConversions
{
    use SluggableTrait, HasMediaTrait, HasModelImage;

    public $defaultImageSrc = '/images/assets/default.png';

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'weight'
    ];

    protected $sluggable = [
        'build_from' => 'name',
        'save_to' => 'slug'
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function($product) {
            $product->addGallery('product images');
        });
    }

    public function registerMediaConversions()
    {
        $this->addMediaConversion('thumb')
            ->setManipulations(['w' => 200, 'h' => 200, 'fit' => 'crop'])
            ->performOnCollections('default');

        $this->addMediaConversion('web')
            ->setManipulations(['w' => 500, 'h' => 600])
            ->performOnCollections('default');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function coverPic($conversion = null)
    {
        return $this->modelImage() ? $this->modelImage()->getUrl($conversion ? $conversion : '') : $this->defaultImageSrc;
    }

    public function galleries()
    {
        return $this->hasMany(ProductGallery::class, 'product_id');
    }

    public function getGallery()
    {
        return $this->galleries()->where('name', 'product images')->firstOrFail();
    }

    public function addGallery($name)
    {
        return $this->galleries()->create(['name' => $name]);
    }

    public function galleryImages()
    {
        return $this->getGallery()->getMedia();
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class, 'product_id');
    }

    public function addOption($name)
    {
        return $this->options()->create(['name' => $name]);
    }

    public function customisations()
    {
        return $this->hasMany(Customisation::class, 'product_id');
    }

    public function addCustomisation($name, $isLongForm = false)
    {
        return $this->customisations()->create(['name' => $name, 'longform' => $isLongForm ? 1 : 0]);
    }
}
