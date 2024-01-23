<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class IndexController extends Controller
{
    public function index()
    {
        return view('pages.dashboard');
    }
    public function showLogin()
    {
    }
    public function test()
    {
        Browsershot::html('<p>hello<p/>')->savePdf('hello.pdf');
    }
}
