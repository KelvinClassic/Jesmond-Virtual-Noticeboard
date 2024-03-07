<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function index(){
        return view('auth.passwords.change');
    }

    public function change_password(Request $request){
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|max:15|confirmed',
        ]);

        $user = Auth::user();

         // Verify the user current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect');
        }

        // Update the user new password
        $user->password = Hash::make($request->new_password);
        $user->update();

        return redirect()->route('pages.account')->with('message', 'Password changed successfully!');
    }
}
