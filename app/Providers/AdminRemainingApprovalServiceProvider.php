<?php

namespace App\Providers;

use App\Models\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AdminRemainingApprovalServiceProvider extends ServiceProvider
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
            $pendingEvents = Event::with(['poster_image', 'poster_type'])->where('event_end_date', '>=', date('Y-m-d'))
                ->where('active', false)
                ->whereHas('poster_image')
                ->get();

            // Pass the data to the view
            $view->with('pendingEvents', $pendingEvents);
        });
    }
}
