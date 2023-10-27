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

        $tableName = 'posts';

        if ($type === 'Reel') {
            $tableName = 'reels';
        }



        $postOrReel = DB::table($tableName)->select('download')->whereid($postid);

        if ($postOrReel->first() == null) {
            return response()->json(['status' => 'error', 'message' => $type + ' not found']);
        }

        $postOrReel->increment('download');

        // Serve the file for download
        $filePath = public_path('/' . $file);
        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }
}
