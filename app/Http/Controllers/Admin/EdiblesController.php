<?php

namespace App\Http\Controllers\Admin;

use App\Content\Gallery;
use App\Content\Page;
use App\Content\Textblock;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EdiblesController extends Controller
{
    public function showPage($pageId)
    {
        $page = Page::with('textblocks', 'galleries')->findOrFail($pageId);

        return view('admin.edibles.showpage')->with(compact('page'));
    }

    public function editTextblock($pageId, $textblockId)
    {
        $textblock = Textblock::findOrFail($textblockId);
        $page = Page::findOrFail($pageId);

        return view('admin.edibles.textblock')->with(compact('textblock', 'page'));
    }

    public function editGallery($pageId, $galleryId)
    {
        $gallery = Gallery::findOrFail($galleryId);
        $page = Page::findOrFail($pageId);

        return view('admin.edibles.gallery')->with(compact('gallery', 'page'));
    }

    public function updateTextblock(Request $request, $textblockId)
    {
        $textblock = Textblock::findOrFail($textblockId);
        $textblock->update(['content' => $request->get('content')]);

        return redirect('admin/site-content/pages/'.$textblock->page->id);
    }

    public function storeUploadedImage(Request $request, $galleryId)
    {
        $this->validate($request, ['file' => 'required|image']);

        $image = $gallery = Gallery::findOrFail($galleryId)->addImage($request->file('file'));

        return response()->json([
            'image_id' => $image->id,
            'src' => $image->getUrl(),
            'thumb_src' => $image->getUrl('thumb')
        ]);
    }

    public function getGalleryImages($galleryId)
    {
        $images = Gallery::findOrFail($galleryId)->getMedia()->map(function($image) {
            return [
                'image_id' => $image->id,
                'src' => $image->getUrl(),
                'web_src' => $image->getUrl('web'),
                'thumb_src' => $image->getUrl('thumb')
            ];
        });

        return response()->json($images);
    }
}
