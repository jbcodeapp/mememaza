<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function uploadImage($request, $field, $storage_dir_key) {
		$params = [];
	
		if ($request->hasFile($field)){
			$date = \Carbon\Carbon::now();
			$prefix_directory = $storage_dir_key .'/'. $date->format('FY');

			// Handle jpeg, jpg, svg, png image types here
			// This assumes your client sends the image with the key 'photo'
			$file = $request->file($field);

            $file_name_extension = $field . '_' . time() . '_' . str_replace(
                ' ',
                '_',
                explode('.', $file->getClientOriginalName())[0].'.webp'
            );
	
			// Create a new image instance using GD
			$image = imagecreatefromstring(file_get_contents($file->getRealPath()));
	
			$path = 'storage/' . $prefix_directory;

			// Check if the storage directory exists, and create it if not
			if (!\File::isDirectory($path)) {
				\File::makeDirectory($path, 0777, true, true);
			}
			$filePath = $path . '/' . $file_name_extension;

			// Save the image as webp format
			imagewebp($image, $filePath, 0.9);
	
			// Free up memory
			imagedestroy($image);
	
			$params[$field] = $filePath;
		}
	
		return $params;
	}

    public function uploadVideo($request, $field, $storage_dir_key, $isStory = false) {
        $params = [];
    
        if ($request->hasFile($field)) {
            $date = \Carbon\Carbon::now();
            $prefix_directory = $storage_dir_key . '/' . $date->format('FY');
    
            // Get the uploaded video file
            $file = $request->file($field);
            
            // Generate a unique file name with a timestamp
            $file_name_extension = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            
            // Define the path to the storage directory
            $path = 'storage/' . $prefix_directory. '/videos';
    
            // Check if the storage directory exists, and create it if not
            if (!\File::isDirectory($path)) {
                \File::makeDirectory($path, 0777, true, true);
            }

            $filePath = $path . '/temp_' . $file_name_extension;
            $croppedFilePath = $path . '/' . $file_name_extension;
    
            // Move the uploaded video to the storage directory
            if($file->move($path, $file_name_extension)) {
                if($isStory) {
                    exec("ffmpeg -i $filePath -b 3000000 $croppedFilePath");
					//vdo_image
                } else {
                    exec("ffmpeg -i $filePath -ab 32 -ss 00:00:00 -t 00:00:28 $croppedFilePath");
                }
                try {
                    unlink($filePath);
                } catch(\Exception $e) {

                }
            }
    
            $params[$field] = $croppedFilePath;
        }
    
        return $params;
    }
}
