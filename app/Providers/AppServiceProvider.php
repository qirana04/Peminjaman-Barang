<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Shoe;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }


    public function boot()
    {
        // Mengirim data sepatu ke semua file blade dengan variabel $global_shoes
        View::composer('*', function ($view) {
           view()->share('global_shoes', \App\Models\Shoe::where('stok', '>', 0)->get());
        });
    }

    
}
