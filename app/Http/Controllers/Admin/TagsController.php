<?php

namespace App\Http\Controllers\Admin;

use App\Stock\Tag;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TagsController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('name')->get();

        return response()->json([
            'tags' => $tags->map(function($tag) {
                return $tag->name;
            })->toArray()
        ]);
    }
}
