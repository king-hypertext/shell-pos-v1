<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InstallerController extends Controller
{
    /**  Datebase configuration */
    public function stepOne(Request $request)
    {

        // $request->dd();
        $db_name = $request->db_name;
        $db_username = $request->db_username;
        $db_password = $request->db_password;
        $url = $request->app_url;
        $env = base_path('.env');
        // Get the content of the .env file
        $content = file_get_contents($env);
        // Replace the APP_NAME value with the new value
        $content = str_replace(
            [
                'DB_DATABASE=' . $_ENV['DB_DATABASE'],
                'DB_USERNAME=' . $_ENV['DB_USERNAME'],
                'DB_PASSWORD=' . $_ENV['DB_PASSWORD'],
                'APP_ENV=' . $_ENV['APP_ENV'],
                'APP_DEBUG=' . $_ENV['APP_DEBUG'],
                'APP_URL=' . $_ENV['APP_URL']
            ],
            [
                'DB_DATABASE=' . $db_name,
                'DB_USERNAME=' . $db_username ?? 'root',
                'DB_PASSWORD=' . $db_password ?? '',
                'APP_ENV=' . 'production',
                'APP_DEBUG=' . 'false',
                'APP_URL=' . $url
            ],
            $content
        );
        $success = file_put_contents($env, $content);

        if ($success) {
            // call migration command to save database configuration
            Artisan::call('migrate:fresh', ['--force' => true]);
            return response()->json(['next' => route('installer.step2')]);
        }
        // Save the content back to the .env file

    }
    public function stepTwo(Request $request)
    {

        try {
            DB::connection()->getPdo();
            // $request->dd();
            $request->validate([
                'username' => 'required|string',
                'fullname' => 'required|string',
                'date_of_birth' => 'required|date|date_format:Y-m-d',
                'secret_code' => 'required|numeric',
            ], [
                'secret_code.numeric' => 'The secret code must be numeric only',
            ]);
            $insert =  User::insert([
                'username' => $request->username,
                'fullname' => $request->fullname,
                'date_of_birth' => $request->date_of_birth,
                'phone' => $request->phone,
                'photo' => null,
                'gender' => $request->gender,
                'admin' => 1,
                'secret_code' => Hash::make($request->secret_code),
                'password' => Hash::make($request->password),
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);
            $id = User::first()->id;
            $env = base_path('.env');
            // Get the content of the .env file
            $content = file_get_contents($env);
            // Replace the APP_NAME value with the new value
            $content = str_replace(
                [
                    'APP_ENV=' . $_ENV['APP_ENV'],
                    'APP_DEBUG=' . $_ENV['APP_DEBUG']
                ],
                [
                    'APP_ENV=' . 'production',
                    'APP_DEBUG=' . 'false'
                ],
                $content
            );
            file_put_contents($env, $content);
            if ($insert) {
                Artisan::call('storage:link', ['--force' => true]);
                return redirect()->to('/install/final')->withInput(['id' => $id]);
            }
        } catch (\Throwable $th) {
            return redirect()->route('installer.step1')->with('error', 'Step one is not completed');
        }
    }
    public function stepThree(Request $request)
    {
        if ($request->has('skip')) {
            return redirect()->to('/login');
        } else {
            $request->validate(
                [
                    'image' => 'required|file|mimes:png,jpg,jpeg,webp'
                ],
                [
                    'image.mimes' => 'The uploaded file type is not allowed',
                    'image.file' => 'The uploaded file is not an image file'
                ]
            );
            if ($request->hasFile('image')) {
                $path =  $request->file('image')->store('/public/user');
                User::where('id', $request->id)->update([
                    'photo' => '/storage/user/' . str_replace(['public/', 'user/'], '', $path)
                ]);
            }
            return redirect()->to('/login');
        }
    }
}
