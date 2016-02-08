<?php

namespace App\Http\Controllers\Admin;

use App\Stock\ProductGallery;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Spatie\MediaLibrary\Media;

class GalleriesController extends Controller
{
    public function index($galleryId)
    {
        $images = ProductGallery::findOrFail($galleryId)->getMedia()->map(function($image) {
            return [
                'image_id' => $image->id,
                'src' => $image->getUrl(),
                'web_src' => $image->getUrl('web'),
                'thumb_src' => $image->getUrl('thumb')
            ];
        });

        return response()->json($images);
    }

    public function storeImage($galleryId, Request $request)
    {
        $this->validate($request, ['file' => 'required|image']);
        $image = ProductGallery::findOrFail($galleryId)->addImage($request->file('file'));

        return response()->json([
            'image_id' => $image->id,
            'src' => $image->getUrl(),
            'thumb_src' => $image->getUrl('thumb')
        ]);
    }

    public function deleteImage($galleryId, $imageId)
    {
        Media::findOrFail($imageId)->delete();

        return response()->json('ok');
    }
}
