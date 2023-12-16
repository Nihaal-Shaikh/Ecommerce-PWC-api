<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeSlider;

class HomeSliderController extends Controller
{
    public function AllSlider() {

        $result = HomeSlider::all();

        return $result;
    }

    public function GetAllSlider() {
        $slider = HomeSlider::latest()->get();

        return view('backend.slider.slider_view', compact('slider'));
    }

    public function AddSlider() {
        return view('backend.slider.slider_add');
    }

    public function StoreSlider(Request $request) {
        $request ->validate([
            'slider_image' => 'required',
        ] , [
            'slider_image.required' => 'Upload slider image'
        ]);

        $save_url = '';

        if ($request->hasFile('slider_image')) {
            $image = $request->file('slider_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            // Specify the upload directory
            $uploadPath = public_path('uploads/slider/');

            // Check if the directory exists, if not, create it
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Move the uploaded file to the specified directory with the generated name
            $image->move($uploadPath, $name_gen);

            // Get the full path of the saved image
            $savedImagePath = $uploadPath . $name_gen;

            // Resize the image (if GD library is available)
            if (extension_loaded('gd')) {
                $imageInfo = getimagesize($savedImagePath);
                $imageType = $imageInfo[2]; // Get the image type

                // Create an image resource based on the image type
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
                    // Add cases for other image types if needed
                    default:
                        // Unsupported image type
                        // You may handle this according to your needs
                        break;
                }

                if (isset($sourceImage)) {
                    $newWidth = 1024;
                    $newHeight = 379;
                    $resized = imagecreatetruecolor($newWidth, $newHeight);
                    imagecopyresampled($resized, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($sourceImage), imagesy($sourceImage));

                    // Save the resized image based on the original image type
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
                        // Add cases for other image types if needed
                        default:
                            // Unsupported image type
                            // You may handle this according to your needs
                            break;
                    }

                    imagedestroy($resized);
                    imagedestroy($sourceImage);
                }
            }

            // Get the URL of the saved image
            $save_url = 'http://127.0.0.1:8000/uploads/slider/' . $name_gen; // Adjust the URL path accordingly
        }

        HomeSlider::insert([
            'slider_image' => $save_url
        ]);
        
        $notification = array(
            'message' => 'Slider image added successfully.',
            'alert-type' => 'success'
        );

        return redirect()->route('all.slider')->with($notification);
    }
}
