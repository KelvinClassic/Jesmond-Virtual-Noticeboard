<?php

namespace App\Http\Controllers;

use App\Models\EventBookmark;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\RecurringEvent;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $bookmarks = EventBookmark::with(['event.poster_image', 'event.poster_type'])->with('event.recurrings', function ($query) {
            return $query->where('event_date', '>=', date('Y-m-d'))->first();
        })
            ->where(['user_id' => Auth::user()->id, 'status' => 'active'])->paginate(4);

        return view("pages.bookmarks", compact(["bookmarks"]));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $event = Event::where(['id' => $request->eventId, 'active' => '1'])->firstOrFail();
        if (Carbon::parse($event->event_end_date . ' ' . $event->event_end_time)->lessThan(Carbon::now())) {
            return back()->with('error', 'Event with past date cannot be bookmarked');
        }
        EventBookmark::updateOrCreate(['user_id' => Auth::user()->id, 'event_id' => $event->id], [
            'status' => 'active'
        ]);


        return redirect()->route('pages.bookmarks')->with('success', 'Bookmark added successfully!');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request): \Illuminate\Http\RedirectResponse
    {
        $event = EventBookmark::where(['id' => $request->id, 'user_id' => Auth::user()->id])->firstOrFail();
        $event->update(['status' => 'inactive']);

        return redirect()->route('pages.bookmarks')->with('success', 'Bookmark removed successfully!');
    }

    public function view(Request $request)
    {

        $upcomingBookmarksPage = $request->input('upcoming_bookmarks_page', 1);
        $upcomingRecurringBookmarksPage = $request->input('upcoming_recurring_bookmarks_page', 1);

        $upcoming_bookmark_events = Event::with(['poster_image', 'poster_type'])
            ->where('event_end_date', '>=', date('Y-m-d'))
            ->where('active', true)
            ->orderBy('event_end_date')
            ->paginate(10, ['*'], 'upcoming_events_page', $upcomingBookmarksPage);


        dd($upcoming_bookmark_events);

        // $upcoming_recurring_bookmark_events = RecurringEvent::with(['event', 'poster_image', 'poster_type'])->whereHas('poster_type', function ($query) {
        //     $query->where('name', 'recurring');
        // })
        //     ->where('event_end_date', '>=', date('Y-m-d'))
        //     ->where('active', true)
        //     ->orderBy('event_end_date')
        //     ->paginate(10, ['*'], 'upcoming_events_page', $upcomingRecurringBookmarksPage);

        return view("pages.bookmarks", compact(["upcoming_bookmark_events", 'upcoming_recurring_bookmark_events']));
    }
}
