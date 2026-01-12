<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->resolving(\Illuminate\Http\Request::class, function ($request, $app) {
            if ($request->is('register') && $request->isMethod('post')) {
                return \App\Http\Requests\RegisterRequest::createFrom($request);
            }
            return $request;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
