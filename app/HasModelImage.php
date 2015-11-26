<?php
/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 11/24/15
 * Time: 11:44 AM
 */

namespace App;


trait HasModelImage
{
    public function setModelImage($file)
    {
        $this->clearMediaCollection();

        return $this->addMedia($file)->preservingOriginal()->toMediaLibrary();
    }

    public function modelImage()
    {
        return $this->getMedia()->first();
    }
}