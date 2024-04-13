<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DownloadController extends Controller
{
    public function downloadFile(Request $request)
    {
        // http://mememaza.test/storage/posts/April2024/img_1712643493_giancarlo-corti-z88llSRVZ-c-unsplash.webp
        // &type=Post
        // &id=108

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
        //$expiration = now()->addMinutes(2);
        // $signedUrl = \Storage::disk('s3')->temporaryUrl(substr($urlComponents['path'], 1), $expiration);
        // $imagepath = str_replace("env('APP_URL')", '' , $urlComponents );
       //$imagepath = explode(env('APP_URL'),$file);
        //dd($file,env('APP_URL'), $urlComponents['path'], $imagepath);
        $imageFile = '';//$imagepath . '/images/photos/account/'  . '.png';

        if (!file_exists(public_path($urlComponents['path']))) {
            return response()->json(['error' => 'File not found'], 404);
        } else {
            
            $extension = last(explode('.',$urlComponents['path']));
            $filename = 'memesmaza_' . strtolower($type) . '_' . base64_encode($postOrReelId) . '_' . time() .".". $extension;
            //$fullFilePath = $filePath . $filename;

            return Response::make('', 200)
                ->header('Location', public_path($urlComponents['path']))
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
            dd($urlComponents['path'], 'else');
        }
        dd($urlComponents['path'], 'exit');

        // if ($imageFile) {
        //     $exploded = explode('/', $urlComponents['path']);

        //     $fileNameExploded = explode('_', $exploded[count($exploded) - 1]);

        //     $extensionExploded = explode('.', $fileNameExploded[count($fileNameExploded) - 1]);

        //     $filePath = 'storage/posts/April2024/';
            
        //     if (!file_exists($filePath)) {
        //         mkdir($filePath, 0777, true);
        //     }
            
        //     $filename = 'memesmaza_' . strtolower($type) . '_' . base64_encode($postOrReelId) . '_' . time() . $extensionExploded[1];
        //     $fullFilePath = $filePath . $filename;

        //     return Response::make('', 200)
        //         ->header('Location', $imageFile)
        //         ->header('Content-Type', 'application/octet-stream')
        //         ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        // } else {
        //     return response()->json(['error' => 'File not found'], 404);
        // }
    }
}
