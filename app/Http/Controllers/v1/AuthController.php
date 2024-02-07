<?php

namespace App\Http\Controllers\v1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;

class AuthController extends Controller
{
    public function update(Request $request)
    {
        // return $request->all();
        $request->validate([
            'password' => 'required|string',
            'secret_code' => 'required|numeric'
        ], [
            'secret_code.numeric' => 'The secret code must be numeric only'
        ]);
        User::where('id', auth()->user()->id)->update([
            "secret_code" => Hash::make($request->secret_code),
            "password" => Hash::make($request->password),
            "updated_at" => now()->format('Y-m-d H:i:s')
        ]);
        return response()->json(['success' => 'Password Changed Successfully']);
    }
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'date_of_birth' => 'required|date',
            'fullname' => 'required|string'
        ]);

        if ($request->hasFile("image")) {
            $request->validate([
                'image' => 'required|file|mimes:png,jpg,jpeg,webp',
            ]);
            $path = $request->file("image")->store('/public/user');
            User::where('id', auth()->user()->id)->update([
                "username" => $request->input("username"),
                "fullname" => $request->input("fullname"),
                "gender" => $request->input("gender"),
                "phone" => $request->input("phone"),
                "date_of_birth" => $request->input("date_of_birth"),
                "photo" => '/storage/user/' . str_replace(['public/', 'user/'], '', $path),
                "updated_at" => now()->format('Y-m-d H:i:s')
            ]);
        } else {
            User::where('id', auth()->user()->id)->update([
                "username" => $request->input("username"),
                "fullname" => $request->input("fullname"),
                "gender" => $request->input("gender"),
                "phone" => $request->input("phone"),
                "date_of_birth" => $request->input("date_of_birth"),
                "updated_at" => now()->format('Y-m-d H:i:s')
            ]);
        }
        return back()->with("success", "User Updated");
    }
    public function login()
    {
        $title = "LOGIN";
        return view('auth.login', compact('title'));
    }

    /** authenticate the user */
    public function verify_user(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|alpha_num'
        ]);
        if (Auth::attempt($request->only('username', 'password'))) {
            DB::table('users')->where('id', auth()->user()->id)->update(['login_at' => now()->format('Y-m-d H:i:s')]);
            $request->session()->regenerate();
            return  redirect()->intended(RouteServiceProvider::HOME);
        }
        return back()->with('error', 'invalid credentials');
        //  response()->json(['error' => 'invalid credentials']);
    }

    public function user_logout()
    {
        DB::table('users')->where('id', auth()->user()->id)->update(['logout_at' => now()->format('Y-m-d H:i:s')]);
        session()->regenerate();
        session()->invalidate();
        Auth::guard('web')->logout();
        return redirect('login');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'secret_code' => 'required',
            'date_of_birth' => 'required|exists:users,date_of_birth'
        ]);
        $secret_code = Hash::check($request->secret_code, DB::table('users')->first()->secret_code);
        if ($secret_code && User::first()->date_of_birth === $request->date_of_birth) {
            return redirect()->route('new.password');
        }
        return back()->withErrors(['secret_code' => 'Secret Code is Invalid', 'date_of_birth' => 'Date of Birth is Invalid']);
    }

    public function ResetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|confirmed',
        ], [
            'password.confirmed' => 'Password and Confirm password mismatched'
        ]);
        User::first()->update([
            'password' => Hash::make($request->password)
        ]);
        return redirect()->route('login')->with('new_password', 'Password was changed');
    }
}
