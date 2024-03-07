<?php

namespace App\Providers;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class EventPostedServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.nav', function ($view) {
            if(Auth::check()){
                $eventsPosted = Event::where('user_id', Auth::user()->id)
                ->get();
            }
            else{
                $eventsPosted = [];
            }
    
            // Pass the data to the view
            $view->with('eventsPosted', $eventsPosted);
        });
    }

}
