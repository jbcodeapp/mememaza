<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function uploadImage($request, $field, $storage_dir_key)
    {
        $params = [];

        if ($request->hasFile($field)) {
            $date = \Carbon\Carbon::now();
            $prefix_directory = $storage_dir_key . '/' . $date->format('FY');

            // Handle jpeg, jpg, svg, png image types here
            // This assumes your client sends the image with the key 'photo'
            $file = $request->file($field);

            $file_name_extension = $field . '_' . time() . '_' . str_replace(
                ' ',
                '_',
                explode('.', $file->getClientOriginalName())[0] . '.webp'
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
            imagewebp($image, $filePath, 90);

            $fileContents = file_get_contents(public_path($filePath));

            if (\Storage::disk('s3')->put($filePath, $fileContents)) {
                $awsPath = \Storage::disk('s3')->url($filePath);
                unlink($filePath);
                // Free up memory
                imagedestroy($image);
                $params[$field] = $awsPath;
            }

        }

        return $params;
    }

    public function uploadVideo($request, $field, $storage_dir_key, $isStory = false)
    {
        $params = [];

        if ($request->hasFile($field)) {
            $date = \Carbon\Carbon::now();
            $prefix_directory = $storage_dir_key . '/' . $date->format('FY');

            // Get the uploaded video file
            $file = $request->file($field);

            // Generate a unique file name with a timestamp
            $file_name_extension = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());

            // Define the path to the storage directory
            $path = 'storage/' . $prefix_directory . '/videos';

            // Check if the storage directory exists, and create it if not
            if (!\File::isDirectory($path)) {
                \File::makeDirectory($path, 0777, true, true);
            }

            $filePath = $path . '/' . $file_name_extension;
            $croppedFilePath = $path . '/cropped_' . $file_name_extension;

            $gifVideoPath = $path . '/gif_' . preg_replace("/\.[^.]+$/", "", $file_name_extension) . '.gif';

            // Move the uploaded video to the storage directory
            if ($file->move($path, $file_name_extension)) {
                $command = 'ffmpeg -i ' . $filePath . ' -vf "fps=24,scale=160:-1" -t 3 ' . $gifVideoPath;
                $output = shell_exec($command);

                $gifContents = file_get_contents(public_path($gifVideoPath));

                if (\Storage::disk('s3')->put($gifVideoPath, $gifContents)) {
                    $awsGifPath = \Storage::disk('s3')->url($gifVideoPath);
                    unlink($gifVideoPath);
                }
                //system($command);
                if ($isStory) {
                    exec("ffmpeg -i $filePath -c:a aac $croppedFilePath");
                    //vdo_image
                } else {
                    exec("ffmpeg -i $filePath -ab 128 -c:a acc -ss 00:00:00 -t 00:00:28 $croppedFilePath");
                }

                $vidContents = file_get_contents(public_path($croppedFilePath));

                if (\Storage::disk('s3')->put($croppedFilePath, $vidContents)) {
                    $awsCroppedFilePath = \Storage::disk('s3')->url($croppedFilePath);
                    unlink($croppedFilePath);
                }

                try {
                    unlink($filePath);
                } catch (\Exception $e) {

                }
            }

            $params[$field] = $awsCroppedFilePath;
            $params['vdo_image'] = $awsGifPath;
        }

        return $params;
    }
}