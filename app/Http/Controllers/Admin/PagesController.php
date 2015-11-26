<?php

namespace App\Http\Controllers\Admin;

use App\Stock\Collection;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{
    public function dashboard()
    {
        $collection = new Collection();
        return view('admin.pages.dashboard')->with(compact('collection'));
    }
}
