<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InstallerController extends Controller
{
    public function stepOne()
    {
        return view('install.step_one');
    }
}
