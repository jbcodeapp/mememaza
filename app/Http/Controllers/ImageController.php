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
        $validator = \Validator::make($request->all(), [
            'image' => 'required|file|mimes:jpg,jpeg,png,webp|max:3000',
        ]);

        if ($validator->fails()) {
            return response()
                ->json(['statuscode' => false, 'errors' => $validator->errors()->all()]);
        }

        $params = $this->uploadImage($request, 'image', 'comments');

        return response()->json(['url' => $params['image']]);
    }
    //
}
