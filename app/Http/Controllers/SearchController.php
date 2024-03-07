<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchPoster(Request $request)
    {
        $categories = Category::orderByRaw("CASE WHEN name = 'General' THEN 0 ELSE 1 END, name")->get();

        $search = $request->search;

        $upcomingEventsPage = $request->input('upcoming_events_page', 1);

        $upcoming_events = Event::with(['poster_image', 'category', 'poster_type'])->whereHas('poster_type', function($query){
            $query->where('name', 'non_recurring');
        })
        ->where('event_end_date', '>=', date('Y-m-d'))
        ->where('active', true)
        ->where(function($query) use($search){
            $query->where('title', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
            ->orWhereHas('category', function($query) use($search){
                $query->where('name', 'like', "%$search%");
            });
        })      
        ->orderBy('event_end_date')
        ->paginate(10, ['*'], 'upcoming_events_page', $upcomingEventsPage);


        $recurringEventsPage = $request->input('recurring_events_page', 1);

        $recurring_events = Event::has('recurrings')
        ->with(['recurrings' => function ($query){
            $query->where('event_date', '>=', date('Y-m-d'))
            ->orderBy('event_date');
        }])
        ->where('active', true)
        ->where(function($query) use($search){
            $query->where('title', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
            ->orWhereHas('category', function($query) use($search){
                $query->where('name', 'like', "%$search%");
            });
        })      
        ->paginate(10, ['*'], 'recurring_events_page', $recurringEventsPage);


        return view('pages.search', compact(['upcoming_events', 'recurring_events', 'categories']));
    }

    public function sortByCategory(Request $request){
        $categories = Category::orderByRaw("CASE WHEN name = 'General' THEN 0 ELSE 1 END, name")->get();

        $search = $request->category;

        $category_id = Category::where('name', $search)->firstOrFail()->id;


        $upcomingEventsPage = $request->input('upcoming_events_page', 1);

        $upcoming_events = Event::with(['poster_image', 'poster_type'])->whereHas('poster_type', function($query){
            $query->where('name', 'non_recurring');
        })
        ->where('event_end_date', '>=', date('Y-m-d'))
        ->where('active', true)
        ->where('category_id', $category_id)
        ->orderBy('event_end_date')
        ->paginate(10, ['*'], 'upcoming_events_page', $upcomingEventsPage);


        $recurringEventsPage = $request->input('recurring_events_page', 1);

        $recurring_events = Event::has('recurrings')
        ->with(['recurrings' => function ($query){
            $query->where('event_date', '>=', date('Y-m-d'))
            ->orderBy('event_date');
        }])
        ->where('active', true)
        ->where('category_id', $category_id)
        ->paginate(10, ['*'], 'recurring_events_page', $recurringEventsPage);


        return view('pages.search', compact(['upcoming_events', 'recurring_events', 'categories']));

    }

    public function sortByWhen(Request $request){
        $categories = Category::orderByRaw("CASE WHEN name = 'General' THEN 0 ELSE 1 END, name")->get();

        $search = $request->when;

        $upcomingEventsPage = $request->input('upcoming_events_page', 1);
        $recurringEventsPage = $request->input('recurring_events_page', 1);

        if(strtolower($search) == "today"){

            $upcoming_events = Event::with(['poster_image', 'poster_type'])->whereHas('poster_type', function($query){
                $query->where('name', 'non_recurring');
            })
            ->where('event_end_date', date('Y-m-d'))
            ->where('active', true)
            ->orderBy('event_end_date')
            ->paginate(10, ['*'], 'upcoming_events_page', $upcomingEventsPage);
    

            $recurring_events = Event::has('recurrings')
            ->with(['recurrings' => function ($query){
                $query->where('event_date', date('Y-m-d'))
                ->orderBy('event_date');
            }])
            ->where('active', true)
            ->paginate(10, ['*'], 'recurring_events_page', $recurringEventsPage);
        
            return view('pages.search', compact(['upcoming_events', 'recurring_events', 'categories']));

        }
        elseif(strtolower($search) == "this week"){
            $currentWeekStartDate = Carbon::now()->startOfWeek()->format('Y-m-d');
            $currentWeekEndDate = Carbon::now()->endOfWeek()->format('Y-m-d');

            $upcoming_events = Event::with(['poster_image', 'poster_type'])->whereHas('poster_type', function($query){
                $query->where('name', 'non_recurring');
            })
            ->where('event_end_date', '>=', date('Y-m-d'))
            ->where('active', true)
            ->whereBetween('event_end_date', [$currentWeekStartDate, $currentWeekEndDate])
            ->orderBy('event_end_date')
            ->paginate(10, ['*'], 'upcoming_events_page', $upcomingEventsPage);
    

            $recurring_events = Event::has('recurrings')
            ->with(['recurrings' => function ($query) use($currentWeekStartDate, $currentWeekEndDate){
                $query->where('event_date', '>=', date('Y-m-d'));
                $query->whereBetween('event_date', [$currentWeekStartDate, $currentWeekEndDate])
                ->orderBy('event_date');
            }])
            ->where('active', true)
            ->paginate(10, ['*'], 'recurring_events_page', $recurringEventsPage);
        
            return view('pages.search', compact(['upcoming_events', 'recurring_events', 'categories']));
    
    
        }
        elseif(strtolower($search) == "this month"){
            $currentMonth = Carbon::now()->month;

            $upcoming_events = Event::with(['poster_image', 'poster_type'])->whereHas('poster_type', function($query){
                $query->where('name', 'non_recurring');
            })
            ->where('event_end_date', '>=', date('Y-m-d'))
            ->where('active', true)
            ->whereMonth('event_end_date', $currentMonth)
            ->orderBy('event_end_date')
            ->paginate(10, ['*'], 'upcoming_events_page', $upcomingEventsPage);
    

            $recurring_events = Event::has('recurrings')
            ->with(['recurrings' => function ($query) use($currentMonth){
                $query->where('event_date', '>=', date('Y-m-d'));
                $query->whereMonth('event_date', $currentMonth)
                ->orderBy('event_date');
            }])
            ->where('active', true)
            ->paginate(10, ['*'], 'recurring_events_page', $recurringEventsPage);
        
            return view('pages.search', compact(['upcoming_events', 'recurring_events', 'categories']));

        }
        else{
            abort(404);
        }

    }

    public function sortByFrequency(Request $request){
        $categories = Category::orderByRaw("CASE WHEN name = 'General' THEN 0 ELSE 1 END, name")->get();

        $search = $request->frequency;

        $upcoming_events = "neglect";

        if(strtolower($search) == "weekly" || strtolower($search) == "fortnightly" || strtolower($search) == "monthly" || strtolower($search) == "yearly"){
            $recurring_events = Event::has('recurrings')
            ->with(['recurrings' => function ($query) use ($search){
                $query->where('event_date', '>=', date('Y-m-d'));
                $query->where('frequency', strtolower($search))
                ->orderBy('event_date');
            }])
            ->where('active', true)
            ->paginate(10);
        
            return view('pages.search', compact(['upcoming_events', 'recurring_events', 'categories']));

        }
        else{
            abort(404);
        }
    }
}
