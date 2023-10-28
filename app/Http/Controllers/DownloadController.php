<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DownloadController extends Controller
{
    public function downloadFile(Request $request)
    {
        $file = $request->query('file');
        $type = $request->query('type');
        $postOrReelId = $request->query('id');

        $tableName = 'posts';

        if ($type === 'Reel') {
            $tableName = 'reels';
        }



        $postOrReel = DB::table($tableName)->select('download')->whereid($postOrReelId);

        if ($postOrReel->first() == null) {
            return response()->json(['status' => 'error', 'message' => $type + ' not found']);
        }

        $postOrReel->increment('download');

        // Serve the file for download
        // Set the expiration time for the URL

        $urlComponents = parse_url(urldecode($file));

        $expiration = now()->addMinutes(2);

        $signedUrl = \Storage::disk('s3')->temporaryUrl(substr($urlComponents['path'], 1), $expiration);
        if ($signedUrl) {
            $exploded = explode('/', $urlComponents['path']);

            $fileNameExploded = explode('_', $exploded[count($exploded) - 1]);

            $extensionExploded = explode('.', $fileNameExploded[count($fileNameExploded) - 1]);

            $filename = 'memesmaza_' . strtolower($type) . '_' . base64_encode($postOrReelId) . '_' . time() . $extensionExploded[1];

            return Response::make('', 200)
                ->header('Location', $signedUrl)
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }
}
