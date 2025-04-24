<?php
namespace App\Providers;

use App\Models\master;
use App\Models\retails;
use App\Models\employe_master;
use Illuminate\Support\ServiceProvider;
use Session;
use View;

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
        $this->app->booted(function () {


            if (Session::has('logintype')) {
                View::share('logintype', Session::get('logintype'));

                if (Session::get('logintype') == 'supeadmin') {
                    View::share('name', 'Super Admin');
                    View::share('username', 'admin@gmail.com');
                }

                if (Session::get('logintype') == 'admin') {
                    View::share('name', 'Admin');
                    View::share('username', 'admin@gmail.com');
                }

                if (Session::get('logintype') == 'retailer') {
                    $admin = retails::find(Session::get('adminloginid'));
                    View::share('name', $admin->name);
                    View::share('username', $admin->username);
                }

                if (Session::get('logintype') == 'distributer') {
                    $admin = retails::find(Session::get('adminloginid'));
                    View::share('name', $admin->name);
                    View::share('username', $admin->username);
                }

                if (Session::get('logintype') == 'user') {
                    $admin = employe_master::find(Session::get('adminloginid'));
                    View::share('name', $admin->name);
                    View::share('username', $admin->username);
                }
            }
            View::share('username', 'admin@gmail.com');
            $master = master::first();
            View::share('master', $master);
        });
    }
}
