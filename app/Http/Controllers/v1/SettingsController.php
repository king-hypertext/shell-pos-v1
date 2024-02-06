<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        // return 'k';
        return view('pages.settings', ['title' => 'SETTINGS']);
    }
}
