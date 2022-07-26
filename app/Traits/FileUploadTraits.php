<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Image;

trait FileUploadTraits
{
    /**
     * Request File Upload
     *
     * Upload a File from request body
     *
     * @param  \Illuminate\Http\Request  $file
     * @param $destinationPath
     * @param $oldFileFullPath
     * @return array
     */
    public function requestUpload($file, $destinationPath, $convert = true, $oldFileFullPath = null)
    {
        $result = [];
        if ($file) {
            // \Log::debug("Debug file request upload", [
            //     'destination' => $destinationPath,
            //     'convert' => $convert,
            //     'old' => $oldFileFullPath
            // ]);

            if (! empty($oldFileFullPath)) {
                $this->removeFile($oldFileFullPath);
            }

            $dimension = getimagesize($file);
            $uploadedFile = $file;
            $filename = 'files-'.(Carbon::now()->timestamp + rand(1, 1000));
            if($convert){
                $extension = 'webp';
            } else {
                $extension = $uploadedFile->getClientOriginalExtension();
            }
            $fullname = $filename.'.'.strtolower($extension);
            $fileSize = $uploadedFile->getSize();
            // $path = $uploadedFile->storeAs($destinationPath, $fullname);

            // Check if directory exists
            if (! (\File::exists($destinationPath))) {
                \Log::debug('Debug on making directory', [
                    'destinationPath' => $destinationPath,
                ]);
                \File::makeDirectory($destinationPath, 0777, true, true);
            }

            $path = $destinationPath.'/'.$fullname;
            if($convert){
                $imageResize = Image::make($uploadedFile)->encode('webp', 90);
                // Resize image
                // if ($imageResize->width() > 750){
                //     $imageResize->resize(750, null, function ($constraint) {
                //         $constraint->aspectRatio();
                //     });
                // }
                $imageResize->save($path);
            } else {
                copy($uploadedFile->getRealPath(), $path);
            }

            $result = [
                'file' => [
                    'filename' => $filename,
                    'extension' => $extension,
                    'fullname' => $fullname,
                    'path' => $path,
                ],
                'dimension' => [
                    'width' => $dimension[0],
                    'height' => $dimension[1],
                ],
                'fileSize' => $fileSize,
            ];
        }

        return $result;
    }

    /**
     * Remove Specific File
     */
    public function removeFile($oldFileFullPath)
    {
        if (! empty($oldFileFullPath)) {
            // \Log::debug("Debug file request upload - on remove", [
            //     'old' => $oldFileFullPath,
            //     'exists' => Storage::exists($oldFileFullPath),
            // ]);

            // Check if old file exists
            if (Storage::exists($oldFileFullPath)) {
                Storage::delete($oldFileFullPath);

                return true;
            }

            return false;
        }

        return false;
    }
}
