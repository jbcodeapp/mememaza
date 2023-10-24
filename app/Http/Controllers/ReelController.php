<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect, Validator, Str;

use App\Models\Reel;
use App\Models\Category;

class ReelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.reels.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\RedirectResponse 
     */
    public function create()
    {
        return Redirect::to('reel_form/-1');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $id = ($request->id > 0) ? $request->id : 0;
            $rules = array(
                'name' => 'required',
                'meta_title' => 'required',
                'meta_keyword' => 'required',
                'meta_desc' => 'required',
                'category_id' => 'required',
                'reel_type' => 'required'
            );

            if ($request->reel_type == 1) { //link
                $rules['videolink'] = 'required';
            } else if ($request->reel_type == 2) { //video
                $rules['video'] = 'required|mimes:mp4,ogx,oga,ogv,ogg,webm|max:50000';

            } else if ($request->reel_type == 3) { //image
                $rules['image'] = 'required|mimes:jpeg,jpg,png,gif,webp|max:10000';
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => 'errors', 'errors' => $validator->getMessageBag()->toArray()]);
            }

            try {
                $reel = new Reel();
                $reel->reel = $request->name;
                $reel->category_id = $request->category_id;
                $reel->reel_type = $request->reel_type;

                switch ($request->reel_type) {
                    case 1:
                        $reel->link = $request->videolink;
                        break;
                    case 2:
                        $filePaths = $this->uploadVideo($request, 'video', 'reels');
                        $reel->link = $filePaths['video'];
                        $reel->vdo_image = $filePaths['vdo_image'];
                        break;
                    case 3:
                        $filePaths = $this->uploadImage($request, 'image', 'reels');
                        $reel->link = $filePaths['image'];
                        break;
                }

                $reel->meta_title = $request->meta_title;
                $reel->meta_keyword = $request->meta_keyword;
                $reel->meta_desc = $request->meta_desc;
                if ($reel->save()) {
                    $reel->slug = Str::slug($request->name . '_' . $reel->id);
                    $reel->saveQuietly();
                    return response()->json(['status' => 'success', 'message' => 'Reel created successfully!']);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Something happened when creating the Reel.']);
                }

            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'stack' => $e->getTrace()]);
            }

        } else {
            abort(400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $reel = Reel::find($id);
        $categories = Category::all();

        return view('admin.reels.form', ['id' => $id, 'obj' => $reel, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
