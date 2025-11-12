<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
    public function AdminProfile()

    {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_profile', compact('profileData'));
    }

    public function AdminProfileUpdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');

            // Delete old photo if exists
            if ($user->photo) {
                $oldPhotoPath = public_path('upload/admin_images/' . $user->photo);
                if (File::exists($oldPhotoPath)) {
                    File::delete($oldPhotoPath);
                }
            }

            // Generate secure unique filename
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/admin_images'), $filename);
            $user->photo = $filename;
        }

        $user->save();

        $notification = array('message' => 'Profile Updated Successfully', 'alert-type' => 'success');

        return redirect()->back()->with($notification);
    }


    public function AdminChangePassword()
    {
        return view('admin.admin_change_password');
    }

    public function AdminPasswordUpdate(Request $request)
    {
        // Validate the request data
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
            'new_password_confirmation' => 'required'
        ]);

        // Check if the old password matches
        if (!Hash::check($request->old_password, Auth::user()->password)) {
            $notification = array(
                'message' => 'Old Password Does not Match!',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }

        // Check if new password and confirmation match
        if ($request->new_password !== $request->new_password_confirmation) {
            $notification = array(
                'message' => 'New Password and Confirmation Do Not Match!',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }

        // Update the password
        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $notification = array(
            'message' => 'Password Changed Successfully',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }

}
