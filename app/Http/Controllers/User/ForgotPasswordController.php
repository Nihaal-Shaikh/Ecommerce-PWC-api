<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;

use App\Http\Requests\ForgotPasswordRequest;
use DB;
use Illuminate\Support\Facades\Hash;
use Mail;
use App\Mail\ForgotPasswordMail;

class ForgotPasswordController extends Controller
{
    
    public function ForgotPassword(ForgotPasswordRequest $request){
        $email = $request->email;

        if (User::where('email',$email)->doesntExist()) {
            return response([
                'message' => 'Email Invalid'
            ],401);
        }

        // generate Randome Token 
        $token = rand(10,100000);

        try{
            DB::table('password_reset_tokens')->insert([
                'email' => $email,
                'token' => $token
            ]);

            // Mail Send to User 
            Mail::to($email)->send(new ForgotPasswordMail($token));

            return response([
                'message' => 'Reset Password Mail send on your email'
            ],200);

        }catch(Exception $exception){
            return response([
                'message' => $exception->getMessage()
            ],400);
        }
    }
}
