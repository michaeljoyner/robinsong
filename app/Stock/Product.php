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

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price'
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

    public function coverPic()
    {
        return $this->modelImage()->getUrl();
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
}
