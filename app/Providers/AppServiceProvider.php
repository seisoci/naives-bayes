<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      Response::macro('api', function ($data = [], $code = 200) {
        $output = [
          'status' => [
            'code' => $code,
            'text' => config('http_status')[$code]
          ]
        ];
        if (($code >= 200) && ($code < 300)) {
          $output = array_merge($output, ['data' => $data]);
        } else {
          $output = array_merge($output, ['errors' => $data]);
        }
        return response()->json($output, $code);
      });

      $settings = (object) json_decode(Storage::get('public/app.json'), TRUE);
      view()->share('settings', $settings);

      Paginator::useBootstrapFive();
    }
}
