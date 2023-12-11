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
}
