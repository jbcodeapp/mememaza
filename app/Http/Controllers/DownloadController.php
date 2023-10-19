<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class DownloadController extends Controller
{ 
    public function downloadFile(Request $request)
    {
		$file = $request->query('file');
		$type = $request->query('type');
		$postid = $request->query('id');

		$post = DB::table('posts')->select('download')->whereid($postid);
        
		if($post->first() == null) {
			return response()->json(['status' => 'error', 'message' => 'Post not found']);
		}
        
		$post->increment('download');

        // Serve the file for download
        $filePath = public_path('/' . $file);
        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }
}
