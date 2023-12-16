<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductList;

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
        $products = ProductList::latest()->get();

        return view('backend.product.product_all', compact('products'));
    }
}
