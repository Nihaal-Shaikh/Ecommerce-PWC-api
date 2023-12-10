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
    }
}
