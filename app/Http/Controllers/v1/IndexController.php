<?php

namespace App\Http\Controllers\v1;

use App\Models\Products;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('pages.dashboard');
    }
}
