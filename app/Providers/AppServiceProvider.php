<?php

namespace App\Providers;

use App\Models\admin\ContactModel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;  // 👈 THÊM DÒNG NÀY

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {   
        // 👇 FORCE URL TỪ ENV VAR - CHUẨN LARAVEL
        if ($appUrl = env('APP_URL')) {
            URL::forceRootUrl($appUrl);
        }
        
        View::composer('admin.blocks.sidebar', function ($view) {
            $contactModel = new ContactModel();
            $unreadData = $contactModel->countContactsUnread();

            $view->with('unreadCount', $unreadData['countUnread']);
            $view->with('unreadContacts', $unreadData['contacts']);
        });
    }
}