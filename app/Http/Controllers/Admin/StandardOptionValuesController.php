<?php

namespace App\Http\Controllers\Admin;

use App\Stock\StandardOption;
use App\Stock\StandardOptionValue;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StandardOptionValuesController extends Controller
{

    public function index($optionId)
    {
        return StandardOption::findOrFail($optionId)->values;
    }

    public function store(Request $request, $optionId)
    {
        $this->validate($request, ['name' => 'required']);

        $value = StandardOption::findOrFail($optionId)->addValue($request->name);

        return $value;
    }

    public function delete($valueId)
    {
        $value = StandardOptionValue::findOrFail($valueId);

        $deleted = $value->delete();

        return response()->json(['deleted' => $deleted]);
    }
}
