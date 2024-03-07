<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\EventBookmark;
use Illuminate\Http\Request;
use App\Models\RecurringEvent;
use App\Models\RejectedEvents;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    public function index()
    {
        $categories = Category::orderByRaw("CASE WHEN name = 'General' THEN 0 ELSE 1 END, name")->get();

        $upcoming_events = Event::with(['poster_image', 'poster_type'])->whereHas('poster_type', function ($query) {
            $query->where('name', 'non_recurring');
        })
            ->where('event_end_date', '>=', date('Y-m-d'))
            ->where('active', true)
            ->orderBy('event_end_date')
            ->take(12)
            ->get();

        $recurring_events = Event::has('recurrings')
            ->with(['recurrings' => function ($query) {
                $query->where('event_date', '>=', date('Y-m-d'))
                    ->orderBy('event_date');
            }])
            ->where('active', true)
            ->get();

        // convert to array so that usort can be used
        $recurring_events = $recurring_events->toArray();

        // Sort the first recurring events by date in ascending order
        usort($recurring_events, function ($a, $b) {
            return $a['recurrings'][0]['event_date'] <=> $b['recurrings'][0]['event_date'];
        });

        // convert back to eloquent format
        $recurring_events = Event::hydrate($recurring_events)->take(12);

        $allEvents = Event::with(['recurrings'])->where('active', true)->where('event_end_date', '>=', date('Y=m-d'))->get();

        $calendarEvents = array();
        foreach ($allEvents as $eachEvent) {
            if (count($eachEvent->recurrings) < 1) {
                $calendarEvents[] = [
                    'title' => $eachEvent->title,
                    'start' => $eachEvent->event_start_date,
                    'end' =>  $eachEvent->event_start_date,
                    'url' => route('pages.live.show', $eachEvent->id),
                    'time' => $eachEvent->event_start_time
                ];
            } else {
                foreach ($eachEvent->recurrings as $rec) {
                    if ($rec->event_date >= date('Y-m-d')) {
                        $calendarEvents[] = [
                            'title' => $eachEvent->title,
                            'start' => $rec->event_date,
                            'end' =>  $rec->event_date,
                            'url' => route('pages.recurring.show', $rec->id),
                            'time' => $eachEvent->event_start_time,
                            'color' => '#4c2281'
                        ];
                    }
                }
            }
        }
        usort($calendarEvents, function ($a, $b) {
            return $a['start'] <=> $b['start'];
        });

        $thisMonthEvents = array();
        foreach ($calendarEvents as $curr_month) {
            if (Carbon::createFromFormat('Y-m-d', $curr_month['start'])->isCurrentMonth()) {
                $thisMonthEvents[] = [
                    'title' => $curr_month['title'],
                    'start' => $curr_month['start'],
                    'url' => $curr_month['url'],
                    'time' => $curr_month['time']
                ];
            }
        }
        // return $thisMonthEvents;

        // add logic here to check if the event is alreay bookmarked.


        return view('pages.home', compact(['upcoming_events', 'recurring_events', 'categories', 'calendarEvents', 'thisMonthEvents']));
    }

    public function events(Request $request)
    {
        $categories = Category::orderByRaw("CASE WHEN name = 'General' THEN 0 ELSE 1 END, name")->get();

        $upcomingEventsPage = $request->input('upcoming_events_page', 1);

        $upcoming_events = Event::with(['poster_image', 'poster_type'])->whereHas('poster_type', function ($query) {
            $query->where('name', 'non_recurring');
        })
            ->where('event_end_date', '>=', date('Y-m-d'))
            ->where('active', true)
            ->orderBy('event_end_date')
            ->paginate(10, ['*'], 'upcoming_events_page', $upcomingEventsPage);

        $recurringEventsPage = $request->input('recurring_events_page', 1);

        $recurring_events = Event::has('recurrings')
            ->with(['recurrings' => function ($query) {
                $query->where('event_date', '>=', date('Y-m-d'))
                    ->orderBy('event_date');
            }])
            ->where('active', true)
            ->paginate(10, ['*'], 'recurring_events_page', $recurringEventsPage);

        return view('pages.events', compact(['upcoming_events', 'recurring_events', 'categories']));
    }

    public function account()
    {
        $user = Auth::user();
        return view('pages.account', compact(['user']));
    }

    public function live_event($id)
    {
        $event = Event::with(['poster_image', 'poster_type', 'user'])->whereHas('poster_type', function ($query) {
            $query->where('name', 'non_recurring');
        })
            ->where('active', true)
            ->where('event_end_date', '>=', date('Y-m-d'))
            ->where('id', $id)
            ->first();

        $isEventBookmarked = false;

        if (Auth::check()) {
            $bookMarkedEvent = EventBookmark::where("user_id", Auth::user()->id)->where("event_id", $id)->where("status", "active")->first();
            if ($bookMarkedEvent) {
                $isEventBookmarked = true;
            } else {
                $isEventBookmarked = false;
            }
        };

        if (!$event) {
            return redirect()->back()->with('message', 'Selected event does not exist or has passed');
        }

        return view('pages.show_live_event', compact(['event', "isEventBookmarked"]));
    }

    public function recurring_event($id)
    {
        $event = RecurringEvent::with(['event'])->whereHas('event.poster_type', function ($query) {
            $query->where('name', 'recurring');
        })
            ->whereHas('event', function ($query) {
                $query->where('active', true);
            })
            ->where('event_date', '>=', date('Y-m-d'))
            ->where('id', $id)
            ->firstOrFail();

        $isEventBookmarked = false;

        if (Auth::check()) {
            $bookMarkedEvent = EventBookmark::where("user_id", Auth::user()->id)->where("event_id", $event->event_id)->where("status", "active")->first();
            if ($bookMarkedEvent) {
                $isEventBookmarked = true;
            } else {
                $isEventBookmarked = false;
            }
        };

        return view('pages.show_recurring_event', compact(['event', 'isEventBookmarked']));
    }

    public function myPostedEvents()
    {
        $events = Event::with(['poster_image', 'poster_type', 'recurrings'])->where('user_id', Auth::user()->id)->orderByDesc('created_at')->get();

        $currentEvents = [];
        $pastEvents = [];
        $pendingEvents = [];

        foreach ($events as $event) {
            if ($event->active && $event->event_end_date >= date('Y-m-d')) {
                $currentEvents[] = $event;
            }
            if ($event->active && $event->event_end_date < date('Y-m-d')) {
                $pastEvents[] = $event;
            }
            if (!$event->active) {
                $pendingEvents[] = $event;
            }
        }

        $categories = Category::orderByRaw("CASE WHEN name = 'General' THEN 0 ELSE 1 END, name")->get();

        return view('pages.my_posted_events', compact(['currentEvents', 'pastEvents', 'pendingEvents', 'categories']));
    }

    public function dashboard()
    {

        $events = [];
        if (Auth::user()->is_admin) {
            $events = Event::with(['poster_image', 'poster_type', 'recurrings'])->orderByDesc('created_at')->get();
        } else {
            $events = Event::with(['poster_image', 'poster_type', 'recurrings'])->where('user_id', Auth::user()->id)->orderByDesc('created_at')->get();
        }
        $approvedEvents = [];
        $pendingEvents = [];

        foreach ($events as $event) {
            if ($event->active) {
                $approvedEvents[] = $event;
            }
            if (!$event->active) {
                $pendingEvents[] = $event;
            }
        }

        $rejectedEvents = [];
        if (Auth::user()->is_admin) {
            $rejectedEvents = RejectedEvents::orderByDesc('created_at')->get();
        } else {
            $rejectedEvents = RejectedEvents::where('user_id', Auth::user()->id)->orderByDesc('created_at')->get();
        }
        $categories = Category::orderByRaw("CASE WHEN name = 'General' THEN 0 ELSE 1 END, name")->get();


        return view('pages.dashboard', compact(['approvedEvents', 'rejectedEvents', 'pendingEvents', 'categories']));
    }

    // all live events regardless of time

    public function all_live_event($id)
    {
        $event = Event::with(['poster_image', 'poster_type', 'user'])->whereHas('poster_type', function ($query) {
            $query->where('name', 'non_recurring');
        })
            ->where('active', true)
            ->where('id', $id)
            ->first();

        $isEventBookmarked = false;

        if (Auth::check()) {
            $bookMarkedEvent = EventBookmark::where("user_id", Auth::user()->id)->where("event_id", $id)->where("status", "active")->first();
            if ($bookMarkedEvent) {
                $isEventBookmarked = true;
            } else {
                $isEventBookmarked = false;
            }
        };

        if (!$event) {
            return redirect()->back()->with('message', 'Selected event does not exist or has passed');
        }

        return view('pages.show_live_event', compact(['event', "isEventBookmarked"]));
    }


    public function all_recurring_event($id)
    {
        $event = RecurringEvent::with(['event'])->whereHas('event.poster_type', function ($query) {
            $query->where('name', 'recurring');
        })
            ->whereHas('event', function ($query) {
                $query->where('active', true);
            })
            ->where('id', $id)
            ->first();

        if (!$event) {
            return redirect()->back()->with('message', 'Selected event does not exist or has passed');
        }

        $isEventBookmarked = false;

        if (Auth::check()) {
            $bookMarkedEvent = EventBookmark::where("user_id", Auth::user()->id)->where("event_id", $event->event_id)->where("status", "active")->first();
            if ($bookMarkedEvent) {
                $isEventBookmarked = true;
            } else {
                $isEventBookmarked = false;
            }
        };

        return view('pages.show_recurring_event', compact(['event', 'isEventBookmarked']));
    }
}
