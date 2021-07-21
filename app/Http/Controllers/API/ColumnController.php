<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    public function index()
    {
        $columns = \App\Models\Columns::with('cards')->get();
        return  response()->json(compact('columns'), 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);
        \App\Models\Columns::create(['title' => $request->title]);
        return  response()->json(['status' => 'OK']);
    }

    public function delete($id){
        \App\Models\Columns::find($id)->delete();
        return  response()->json(['status' => 'OK']);
    }
}
