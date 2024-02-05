<?php

namespace App\Http\Controllers\test;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class TestController extends Controller
{
    // public function __invoke()
    // {
    //     $test = putenv('APP_NAME=test');
    //     return getenv('APP_ENV');
    // }


    public function runMigrations()
    {
        try {
            // Run the migrations
            Artisan::call('migrate');

            // Return a success message
            return 'Migrations ran successfully.';
        } catch (Exception $e) {
            // Return an error message
            return 'Migrations failed: ' . $e->getMessage();
        }
    }

    public function test()
    {
        // Get the path of the .env file
        $path = base_path('.env');

        // Get the content of the .env file
        $content = file_get_contents($path);
        // Replace the APP_NAME value with the new value
        $content = str_replace(
            'APP_NAME=' . $_ENV['APP_NAME'],
            'APP_NAME=' . 'shell_pos_v1',
            $content
        );

        // Save the content back to the .env file
        file_put_contents($path, $content);
        // return $content;
        return Artisan::call('tinker');
        // Artisan::call('migrate:status');
    }
}
