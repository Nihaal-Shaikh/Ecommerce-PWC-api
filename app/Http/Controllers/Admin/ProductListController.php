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
}
