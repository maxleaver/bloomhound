<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('jsend_success', function ($data = null, $status = 200) {
            $content = ['status' => 'success', 'data' => $data];

            $response = Response::make($content, $status);
            $response->header('Content-Type', 'application/json');
            return $response;
        });

        Response::macro('jsend_fail', function ($data = null, $status = 400) {
            $content = ['status' => 'fail', 'errors' => $data];

            $response = Response::make($content, $status);
            $response->header('Content-Type', 'application/json');
            return $response;
        });

        Response::macro('jsend_error', function ($message = null, $status = 500) {
            $content = ['status' => 'error', 'message' => $message];

            $response = Response::make($content, $status);
            $response->header('Content-Type', 'application/json');
            return $response;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
