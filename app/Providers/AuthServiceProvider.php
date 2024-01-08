<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Admin;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Opcodes\LogViewer\Facades\LogViewer;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
      $this->registerPolicies();
      Gate::before(function (?Admin $user, $ability) {
        return $user->hasRole('super-admin') ? true : null;
      });

//      LogViewer::auth(function ($request) {
//        return $request->user()
//          && in_array($request->user()->email, [
//            'admin@example.com'
//            // 'john@example.com',
//          ]);
//      });
    }
}
