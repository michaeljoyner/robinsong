<?php

namespace App\Stock;

use App\HasModelImage;
use App\Services\BreadcrumbableInterface;
use App\Services\BreadcrumbsTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Product extends Model implements SluggableInterface, HasMediaConversions, BreadcrumbableInterface
{
    use SluggableTrait, HasMediaTrait, HasModelImage, BreadcrumbsTrait, SoftDeletes;

    public $defaultImageSrc = '/images/assets/default.jpg';

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'writeup',
        'available'
    ];

    protected $sluggable = [
        'build_from' => 'name',
        'save_to'    => 'slug'
    ];

    protected $breadcrumblings = [
        'build_name_from' => 'name',
        'url_unique'      => 'slug',
        'url_base'        => 'product',
        'parent'          => 'category'
    ];

    protected $casts = [
        'available' => 'boolean'
    ];

    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();

        static::created(function ($product) {
            $product->addGallery('product images');
        });
    }

    public function registerMediaConversions()
    {
        $this->addMediaConversion('thumb')
            ->setManipulations(['w' => 250, 'h' => 250, 'fit' => 'crop'])
            ->performOnCollections('default');

        $this->addMediaConversion('web')
            ->setManipulations(['w' => 500, 'h' => 600])
            ->performOnCollections('default');
    }

    public function stockUnits()
    {
        return $this->hasMany(StockUnit::class, 'product_id');
    }

    public function addStockUnit($attributes)
    {
        return $this->stockUnits()->create($attributes);
    }

    public function lowestPriceString()
    {
        $price = $this->stockUnits->filter(function ($unit) {
            return $unit->available;
        })->sortBy(function ($unit) {
            return $unit->price->inCents();
        })->first();

        return $price ? $price->price->asCurrencyString() : '';
    }


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function setTags($tags)
    {
        $tagIds = collect($tags)->map(function ($tagName) {
            return Tag::makeTag(['name' => $tagName])->id;
        })->toArray();

        return $this->tags()->sync($tagIds);
    }

    public function getTagsList()
    {
        return $this->tags->map(function ($tag) {
            return $tag->name;
        })->toArray();
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


    public function useStandardOption($standardOptionId)
    {
        $standardOption = StandardOption::findOrFail($standardOptionId);

        $option = $this->addOption($standardOption->name);

        $standardOption->values->each(function ($value) use ($option) {
            $option->addValue($value->name);
        });

        return $option;
    }

    public function customisations()
    {
        return $this->hasMany(Customisation::class, 'product_id');
    }

    public function addCustomisation($name, $isLongForm = false)
    {
        return $this->customisations()->create(['name' => $name, 'longform' => $isLongForm ? 1 : 0]);
    }

    public function setAvailability($available)
    {
        $this->available = $available;

        return $this->save();
    }

    public function setWriteup($writeup)
    {
        $this->writeup = $writeup;

        return $this->save();
    }

    public function hasWriteup()
    {
        return !!$this->writeup;
    }

    public function cloneAs($cloneName)
    {
        $clone = $this->category->addProduct([
            'name'        => $cloneName,
            'description' => $this->description,
            'writeup'     => $this->writeup,
            'available'   => 0
        ]);

        $this->stockUnits->each(function ($unit) use ($clone) {
            $clone->addStockUnit([
                'name'   => $unit->name,
                'price'  => $unit->price->inCents(),
                'weight' => $unit->weight
            ]);
        });

        $this->options->each(function ($option) use ($clone) {
            $new_option = $clone->addOption($option->name);
            $option->values->each(function($value) use ($new_option) {
                $new_option->addValue($value->name);
            });
        });

        $this->customisations->each(function($customisation) use ($clone) {
           $clone->addCustomisation($customisation->name, $customisation->longform);
        });

        return $clone;
    }


}
