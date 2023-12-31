<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductReview;

class ProductReviewController extends Controller
{
    public function ProductReviewList(Request $request) {

        $product_code = $request->product_code;
        $result = ProductReview::where('product_code', $product_code)->orderBy('id', 'desc')->limit(4)->get();

        return $result;
    }

    public function PostReview(Request $request) {

        $product_name = $request->input('product_name');
        $product_code = $request->input('product_code');
        $reviewer_name = $request->input('reviewer_name');
        $reviewer_photo = $request->input('reviewer_photo');
        $reviewer_rating = $request->input('reviewer_rating');
        $reviewer_comment = $request->input('reviewer_comment');

        $result = ProductReview::insert([
            'product_name'=> $product_name,
            'product_code'=> $product_code,
            'reviewer_name'=> $reviewer_name,
            'reviewer_photo'=> $reviewer_photo,
            'reviewer_rating'=> $reviewer_rating,
            'reviewer_comment'=> $reviewer_comment
        ]);

        return $result;
    }

    public function GetAllReviews() {
        $reviews = ProductReview::latest()->get();

        return view('backend.review.review_all', compact('reviews'));
    }

    public function DeleteReview($id) {
        ProductReview::findOrfail($id)->delete();
            
        $notification = array(
            'message' => 'Review deleted successfully.',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
