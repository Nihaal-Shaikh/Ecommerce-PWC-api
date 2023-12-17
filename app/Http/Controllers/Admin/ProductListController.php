<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageProcessing;
use App\Http\Controllers\Controller;
use App\Models\ProductDetails;
use Illuminate\Http\Request;
use App\Models\ProductList;
use App\Models\Category;
use App\Models\SubCategory;

class ProductListController extends Controller
{
    public function ProductListByRemark(Request $request) {

        $remark = $request->remark;

        $productList = ProductList::where('remark', $remark)->limit(8)->get();

        return $productList;
    }

    public function ProductListByCategory(Request $request) {

        $category = $request->category;

        $productList = ProductList::where('category', $category)->get();

        return $productList;
    }

    public function ProductListBySubCategory(Request $request) {

        $category = $request->category;
        $subcategory = $request->subcategory;

        $productList = ProductList::where('category', $category)->where('subcategory', $subcategory)->get();

        return $productList;   
    }

    public function SearchByProduct(Request $request) {

        $key = $request->key;

        $productList = ProductList::where('title', 'LIKE', '%' . $key . '%')->orWhere('brand', 'LIKE', '%' . $key . '%')->get();

        return $productList;
    }

    public function SimilarProducts(Request $request) {

        $subcategory = $request->subCategory;
        $productList  = ProductList::where('subcategory', $subcategory)->orderBy('id', 'desc')->limit(6)->get();

        return $productList;
    }

    public function GetAllProducts() {
        $products = ProductList::latest()->paginate(10);

        return view('backend.product.product_all', compact('products'));
    }

    public function AddProduct() {
        $category = Category::orderBy('category_name', 'ASC')->get();
        $subcategory = SubCategory::orderBy('subcategory_name', 'ASC')->get();
        return view('backend.product.product_add', compact('category','subcategory'));
    }

    public function StoreProduct(Request $request) {

        $request ->validate([
            'product_code' => 'required',
        ] , [
            'product_code.required' => 'Input product code'
        ]);
        
        $save_url = '';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            // Specify the upload directory
            $uploadPath = public_path('uploads/product/');

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
                    $newWidth = 711;
                    $newHeight = 960;
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
            $save_url = 'http://127.0.0.1:8000/uploads/product/' . $name_gen; // Adjust the URL path accordingly
        }

        $product_id = ProductList::insertGetId([
            'title' => $request->title,
            'price' => $request->price,
            'special_price' => $request->special_price,
            'category' => $request->category,
            'subcategory' => $request->subcategory,
            'remark' => $request->remark,
            'brand' => $request->brand,
            'product_code' => $request->product_code,
            'image' => $save_url
        ]);

        $imageOneUrl = ImageProcessing::uploadAndResizeImage($request, 'image_one', 'uploads/productdetails/', 711, 960);
        $imageTwoUrl = ImageProcessing::uploadAndResizeImage($request, 'image_two', 'uploads/productdetails/', 711, 960);
        $imageThreeUrl = ImageProcessing::uploadAndResizeImage($request, 'image_three', 'uploads/productdetails/', 711, 960);
        $imageFourUrl = ImageProcessing::uploadAndResizeImage($request, 'image_four', 'uploads/productdetails/', 711, 960);

        ProductDetails::insert([
            'product_id' => $product_id,
            'image_one' => $imageOneUrl,
            'image_two' => $imageTwoUrl,
            'image_three' => $imageThreeUrl,
            'image_four' => $imageFourUrl,
            'short_description' => $request->short_description,
            'color' => $request->color,
            'size' => $request->size,
            'long_description' => $request->long_description,
        ]);
        
        $notification = array(
            'message' => 'Product added successfully.',
            'alert-type' => 'success'
        );

        return redirect()->route('all.products')->with($notification);
    }

    public function EditProduct($id) {
        $category = Category::orderBy('category_name', 'ASC')->get();
        $subcategory = SubCategory::orderBy('subcategory_name', 'ASC')->get();
        $product = ProductList::findOrFail($id);
        $details = ProductDetails::where('product_id', $id)->get();
        return view('backend.product.product_edit', compact('category','subcategory', 'product', 'details'));
    }
}
