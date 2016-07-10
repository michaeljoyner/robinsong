<?php

namespace App\Http\Controllers\Admin;

use App\Stock\StandardOption;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StandardOptionsController extends Controller
{

    public function app()
    {
        return view('admin.productmanagement.standardoptions');
    }

    public function index()
    {
        return StandardOption::all();
    }

    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required|unique:standard_options']);

        return StandardOption::create(['name' => $request->name]);
    }

    public function delete($id)
    {
        $option = StandardOption::findOrFail($id);

        $deleted = $option->delete();

        return response()->json(['deleted' => $deleted]);
    }
}
