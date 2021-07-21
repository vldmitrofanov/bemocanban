<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function create($column_id, Request $request)
    {
        $column = \App\Models\Columns::findOrFail($column_id);
        $card = \App\Models\Cards::create(['title' => $request->title, 'order' => $column->cards->count() + 1]);
        $column->cards()->save($card);
        return  response()->json(['status' => 'OK']);
    }

    public function update($id, Request $request)
    {
        $card = \App\Models\Cards::findOrFail($id);
        $card->update([
            'title' => $request->title,
            'description' => $request->description,
            'column_id' => $request->column_id,
            'order' => $request->order
        ]);
        return  response()->json(['status' => 'OK']);
    }

    public function delete($id){
        \App\Models\Cards::find($id)->delete();
        return  response()->json(['status' => 'OK']);
    }
}
