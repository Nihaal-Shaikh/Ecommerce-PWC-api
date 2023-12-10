<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function AdminLogout() {
        Auth::logout();
        return Redirect()->route('login');
    }

    public function UserProfile() {
        $adminData = User::find(1);

        return view('backend.admin.admin_profile', compact('adminData'));
    }

    public function UserProfileStore(Request $request) {

        $data = User::find(1);
        $data->name = $request->name;
        $data->email = $request->email;

        if ($request->file('profile_photo_path')) {
            $file = $request->file('profile_photo_path');
            @unlink(public_path('uploads/admin_images/'. $data->profile_photo_path));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('uploads/admin_images'), $filename);
            $data['profile_photo_path'] = $filename;
        }

        $data->save();
        $notification = array(
            'message' => 'User profile updated successfully.',
            'alert-type' => 'success'
        );
        return redirect()->route('user.profile')->with($notification);
    }
}
