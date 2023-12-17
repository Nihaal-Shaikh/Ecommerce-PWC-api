<?php

namespace App\Helpers;

class ImageProcessing
{
    public static function uploadAndResizeImage($request, $inputName, $uploadDirectory, $newWidth, $newHeight)
    {
        $save_url = null;

        if ($request->hasFile($inputName)) {
            $image = $request->file($inputName);
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $uploadPath = public_path($uploadDirectory);
    
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
    
            $image->move($uploadPath, $name_gen);
            $savedImagePath = $uploadPath . $name_gen;
    
            if (extension_loaded('gd')) {
                $imageInfo = getimagesize($savedImagePath);
                $imageType = $imageInfo[2];
    
                switch ($imageType) {
                    case IMAGETYPE_JPEG:
                        $sourceImage = imagecreatefromjpeg($savedImagePath);
                        break;
                    case IMAGETYPE_PNG:
                        $sourceImage = imagecreatefrompng($savedImagePath);
                        break;
                    case IMAGETYPE_GIF:
                        $sourceImage = imagecreatefromgif($savedImagePath);
                        break;
                    default:
                        // Handle unsupported image type as needed
                        break;
                }
    
                if (isset($sourceImage)) {
                    $resized = imagecreatetruecolor($newWidth, $newHeight);
                    imagecopyresampled($resized, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($sourceImage), imagesy($sourceImage));
    
                    switch ($imageType) {
                        case IMAGETYPE_JPEG:
                            imagejpeg($resized, $savedImagePath);
                            break;
                        case IMAGETYPE_PNG:
                            imagepng($resized, $savedImagePath);
                            break;
                        case IMAGETYPE_GIF:
                            imagegif($resized, $savedImagePath);
                            break;
                        default:
                            // Handle unsupported image type as needed
                            break;
                    }
    
                    imagedestroy($resized);
                    imagedestroy($sourceImage);
                }
            }
    
            $save_url = url('/' . $uploadDirectory . $name_gen);
        }
    
        return $save_url;
    }
}
