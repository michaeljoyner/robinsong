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

class Collection extends Model implements SluggableInterface, HasMediaConversions, BreadcrumbableInterface
{
    use SoftDeletes, SluggableTrait, HasModelImage, HasMediaTrait, BreadcrumbsTrait;

    public $defaultImageSrc = '/images/assets/default.jpg';

    protected $table = 'collections';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'description'
    ];

    protected $breadcrumblings = [
        'build_name_from' => 'name',
        'url_unique'      => 'slug',
        'url_base'        => 'collections',
        'parent'          => null
    ];

    protected $sluggable = [
        'build_from' => 'name',
        'save_to'    => 'slug'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function($collection) {
            $collection->categories->each(function($category) {
                $category->delete();
            });

            return true;
        });
    }

    public function registerMediaConversions()
    {
        $this->addMediaConversion('thumb')
            ->setManipulations(['w' => 300, 'h' => 300, 'fit' => 'crop'])
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
