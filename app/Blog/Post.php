<?php

namespace App\Blog;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Post extends Model implements SluggableInterface, HasMediaConversions
{
    use SluggableTrait, HasMediaTrait;

    protected $table = 'posts';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'content',
        'published'
    ];

    protected $sluggable = [
        'build_from' => 'title',
        'save_to' => 'slug'
    ];

    public function registerMediaConversions()
    {
        $this->addMediaConversion('thumb')
            ->setManipulations(['w' => 200, 'h' => 200, 'fit' => 'crop'])
            ->performOnCollections('default');

        $this->addMediaConversion('web')
            ->setManipulations(['w' => 800, 'h' => 600, 'fit' => 'max'])
            ->performOnCollections('default');
    }

    public function addImage($image)
    {
        return $this->addMedia($image)->preservingOriginal()->toMediaLibrary();
    }

    public function setPublished($publish)
    {
        $this->published = $publish ? 1 : 0;
        $this->save();

        return !! $this->published;
    }
}
