<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'date_of_birth' => 'required|date',
            'fullname' => 'required|string'
        ]);

        if ($request->hasFile("user-image")) {
            $request->validate([
                'user-image' => 'required|file|mimes:png,jpg,jpeg,webp',
            ]);
            $path = $request->file("user-image")->store('/public/user');
        }
        User::insert([
            "username" => $request->input("username"),
            "fullname" => $request->input("fullname"),
            "gender" => $request->input("gender"),
            "date_of_birth" => $request->input("dob"),
            "address" => $request->input("address"),
            "password" => $request->input("password"),
            "photo" => '/storage/user/' . str_replace(['public/', 'user/'], '', $path),
            "created_at" => now()->format('Y-m-d')
        ]);

        return back()->with("success", "User Updated");
    }

    /**
     * Display the specified resource.
     */
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
            $request->session()->regenerate();
            return response()->json(['data' => route('dashboard')]);
        }
        // return redirect()->intended('/');
        // $request->session()->regenerate();

        return response()->json(['error' => 'invalid credentials']);
    }
    public function verify_login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|alpha_num'
        ]);

        if (Auth::attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return response()->json(['error' => 'invalid credentials']);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
