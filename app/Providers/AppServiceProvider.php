<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Post;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use PSpell\Config;

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
        View::composer('frontend.body.header', function ($view) {
            $categories = Category::latest()->get();
            $view->with('categories', $categories);
        });

        View::composer('frontend.main', function ($view) {
            $site = SiteSetting::find(1);
            $view->with('site', $site);
        });

        View::composer('frontend.dashboard.dashboard_user', function ($view) {
            $user = auth()->user();
            $view->with('user', $user);
        });


        if(Schema::hasTable('setting_smtps')) {
            $smtp = \App\Models\SettingSmtp::first();
            if($smtp) {
                $date = [
                    'driver'     => $smtp->mailer,
                    'host'       => $smtp->host,
                    'port'       => $smtp->port,
                    'username'   => $smtp->username,
                    'password'   => $smtp->password,
                    'encryption' => $smtp->encryption,
                    'from'       => [
                        'address' => $smtp->from_address,
                        'name' => 'EASYLMS',
                    ],
                ];
                config(['mail' => $date]);
            }
        }
}

}