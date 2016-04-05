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
        return redirect('admin/products/index');
    }
}
