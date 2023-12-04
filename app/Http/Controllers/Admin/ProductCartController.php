<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCart;
use App\Models\ProductList;
use App\Models\CartOrder;

class ProductCartController extends Controller
{
    public function AddToCart(Request $request) 
    {

        $email = $request->input('email');
        $size = $request->input('size');
        $colour = $request->input('colour');
        $quantity = $request->input('quantity');
        $product_code = $request->input('product_code');

        $product_details = ProductList::where('product_code', $product_code)->get();

        $price = $product_details[0]['price'];
        $special_price = $product_details[0]['special_price'];

        if(!$special_price) {
            $total_price = $price * $quantity;
            $unit_price = $price;
        } else {
            $total_price = $special_price * $quantity;
            $unit_price = $special_price;
        }

        $result = ProductCart::insert([
            'email' => $email,
            'image' => $product_details[0]['image'],
            'product_name' => $product_details[0]['title'],
            'product_code' => $product_details[0]['product_code'],
            'size' => $size,
            'colour' => $colour,
            'quantity' => $quantity,
            'unit_price' => $unit_price,
            'total_price' => $total_price
        ]);

        return $result;
    }

    public function CartCount(Request $request)
    {
        $product_code = $request->product_code;
        $result = ProductCart::count();

        return $result;
    }

    public function CartList(Request $request)
    {
        $email = $request->email;

        $result = ProductCart::where('email', $email)->get();

        return $result;
    }

    public function RemoveCartItem(Request $request)
    {
        $id = $request->id;

        $result = ProductCart::where('id', $id)->delete();

        return $result;
    }

    public function CartItemPlus(Request $request)
    {
        $id = $request->id;
        $price = $request->price;
        $quantity = $request->quantity;
        
        $newQuantity = $quantity + 1;
        $totalPrice = $newQuantity * $price;

        $result = ProductCart::where('id', $id)->update(['quantity' => $newQuantity, 'total_price' => $totalPrice]);
        return $result;
    }

    public function CartItemMinus(Request $request)
    {
        $id = $request->id;
        $price = $request->price;
        $quantity = $request->quantity;
        
        $newQuantity = $quantity - 1;
        $totalPrice = $newQuantity * $price;

        $result = ProductCart::where('id', $id)->update(['quantity' => $newQuantity, 'total_price' => $totalPrice]);
        return $result;
    }

    public function CartOrder(Request $request)
    {
        $city = $request->input('city');
        $payment_method = $request->input('payment_method');
        $name = $request->input('name');
        $email = $request->input('email');
        $delivery_address = $request->input('delivery_address');
        $invoice_no = $request->input('invoice_no');
        $delivery_charge = $request->input('delivery_charge');

        date_default_timezone_set('Asia/Kolkata');
        $request_time = date('h:i:sa');
        $request_date = date('d-m-Y');

        $cart_list = ProductCart::where('email', $email)->get();

        foreach ($cart_list as $item) {
            $cartDeleteResult = '';
            $resultInsert = CartOrder::insert([
                'invoice_no' => 'Easy'.$invoice_no,
                'product_name' => $item['product_name'],
                'product_code' => $item['product_code'],
                'size' => $item['size'],
                'colour' => $item['colour'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
                'email' => $item['email'],
                'name' => $name,
                'payment_method' => $payment_method,
                'delivery_address' => $delivery_address,
                'delivery_charge' => $delivery_charge,
                'city' => $city,
                'order_date' => $request_date,
                'order_time' => $request_time,
                'order_status' => 'Pending'
            ]);

            if($resultInsert) {
                $resultDelete = ProductCart::where('id', $item['id'])->delete();

                if($resultDelete) {
                    $cartDeleteResult = 1;
                } else {
                    $cartDeleteResult = 0;
                }
            }
        }

        return $cartDeleteResult;
    }
}
