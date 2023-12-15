<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
class CategoryController extends Controller
{
    public function AllCategory(Request $request) {

        $categories = Category::all();
        $categoryDetails = [];

        foreach($categories as $value) {
            $subCategory = SubCategory::where('category_name', $value['category_name'])->get();
            $item = [
                'category_name' => $value['category_name'],
                'category_image' => $value['category_image'],
                'subcategory_name' => $subCategory
            ];

            array_push($categoryDetails, $item);
        }

        return $categoryDetails;
    }

    public function AllCategories() {
        $categories = Category::latest()->get();

        return view('backend.category.category_view', compact('categories'));
    }

    public function AddCategory() {
        return view('backend.category.category_add');
    }

    public function StoreCategory(Request $request) {
        $request ->validate([
            'category_name' => 'required',
        ] , [
            'category_name.required' => 'Input category name'
        ]);

        $save_url = '';

        if ($request->hasFile('category_image')) {
            $image = $request->file('category_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            // Specify the upload directory
            $uploadPath = public_path('uploads/category/');

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
                    $newWidth = 128;
                    $newHeight = 128;
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
            $save_url = 'http://127.0.0.1:8000/uploads/category/' . $name_gen; // Adjust the URL path accordingly
        }

        Category::insert([
            'category_name' => $request->category_name,
            'category_image' => $save_url
        ]);
        
        $notification = array(
            'message' => 'Category added successfully.',
            'alert-type' => 'success'
        );

        return redirect()->route('all.categories')->with($notification);
    }

    public function EditCategory($id) {
        $category = Category::findOrFail($id);
        return view('backend.category.category_edit', compact('category'));
    }

    public function UpdateCategory(Request $request) {
        $category_id = $request->id;

        if($request->file('category_image')) {
            $image = $request->file('category_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            // Specify the upload directory
            $uploadPath = public_path('uploads/category/');

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
                    $newWidth = 128;
                    $newHeight = 128;
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
            $save_url = 'http://127.0.0.1:8000/uploads/category/' . $name_gen; // Adjust the URL path accordingly
            // Image::make($image)->resize(128, 128)->save('uploads/category/'. $name_gen);

            // $save_url = 'http://127.0.0.1:8000/uploads/category' . $name_gen;

            Category::findOrFail($category_id)->update([
                'category_name' => $request->category_name,
                'category_image' => $save_url
            ]);
            
            $notification = array(
                'message' => 'Category updated successfully.',
                'alert-type' => 'success'
            );

            return redirect()->route('all.categories')->with($notification);
        } else {
            Category::findOrFail($category_id)->update([
                'category_name' => $request->category_name
            ]);
            
            $notification = array(
                'message' => 'Category updated successfully.',
                'alert-type' => 'success'
            );

            return redirect()->route('all.categories')->with($notification);
        }
    }

    public function DeleteCategory($id) {
        Category::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Category deleted successfully.',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
