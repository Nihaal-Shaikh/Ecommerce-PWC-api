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

    public function FavouriteList(Request $request)
    {
        $email = $request->email;
        error_log('email: '. $email);

        $result = Favourites::where('email', $email)->get();

        return $result;
    }

    public function FavouriteRemove(Request $request)
    {
        $product_code = $request->productCode;
        $email = $request->email;

        $result = Favourites::where('product_code', $product_code)::where('email', $email)->delete();
        return $result;
    }
}
