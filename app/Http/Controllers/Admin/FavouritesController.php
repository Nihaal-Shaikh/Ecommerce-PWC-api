<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favourites;
use App\Models\ProductList;


class FavouritesController extends Controller
{
    public function AddFavourite(Request $request)
    {
        $product_code = $request->productCode;
        $email = $request->email;

        $product_details = ProductList::where('product_code', $product_code)->get();

        $result = Favourites::insert([
            'product_name' => $product_details[0]['title'],
            'image' => $product_details[0]['image'],
            'product_code' => $product_code,
            'email' => $email
        ]);

        return $result;
    }
}
