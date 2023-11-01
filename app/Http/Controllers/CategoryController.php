<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function categorybyslug(Request $request, $slug)
    {
        $category = Category::whereSlug($slug)
            ->withCount('posts')
            ->withCount('reels')
            ->firstOrFail();

        return response()->json(['obj' => $category]);
    }
}
