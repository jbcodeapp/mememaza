<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoUploadController extends Controller
{
    public function showUploadForm()
    {
        return view('upload');
    }
	
	public function upload(Request $request)
	{
		 $resizedVideo = cloudinary()->uploadVideo($request->file('video')->getRealPath(), [
				'folder' => 'uploads',
				'transformation' => [
						  'width' => 350,
						  'height' => 200
				 ]
	])->getSecurePath();

		dd($resizedVideo);
	} 
	
	public function storeUploads(Request $request) {
		// Video compress 1800222999
		// Audio compress
		//11060492
		//11060492
		
		$inputAudio = public_path('/uploads/audio/test.mp4');
		$outputAudio = public_path('/uploads/output/outputAudio.avi');
		//exec("ffmpeg -i $inputAudio -ab 64 -ss 00:00:05 -t 00:00:08 $outputAudio");
		exec("ffmpeg -i $inputAudio -ab 64 $outputAudio");
		dd($outputAudio);
		/* $inputAudio = public_path('/audio/myaudio.mp3');
		$outputAudio = public_path('/output/outputAudio.mp3');
		exec("ffmpeg -i $inputAudio -ab 64 $outputAudio");

		// Video compress
		$inputVideo = public_path('/audio/myvideo.mp4');
		$outputVideo = public_path('/output/outputVideo.mp4');
		exec("ffmpeg -i $inputVideo -ab 64 $outputVideo"); */
	}
	
	/* public function upload(Request $request)
	{
		 $compressedVideo = cloudinary()->upload($request->file('video')->getRealPath(), [
				'folder' => 'uploads',
				'transformation' => [
						  'quality' => auto,
				 ]
	])->getSecurePath();
	dd($resizedVideo);
	}  */
}
