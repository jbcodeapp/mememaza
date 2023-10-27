<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{

    public function create()
    {
        return view("image");
    }

    public function store(Request $request)
    {
        $path = \Storage::disk('s3')->put('/', $request->file('image'));

        return response()->json(['path' => $path]);
    }
    //
}
