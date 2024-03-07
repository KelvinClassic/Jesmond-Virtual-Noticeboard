<?php

namespace App\Http\Controllers;

use App\Jobs\NotifyAdminJob;
use App\Mail\NotifyAdminEmail;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Category;
use App\Models\PosterType;
use App\Models\PosterImage;
use Illuminate\Http\Request;
use App\Models\RecurringEvent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PosterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderByRaw("CASE WHEN name = 'General' THEN 0 ELSE 1 END, name")->get();
        $poster_types = PosterType::all();
        return view('pages.poster.create', compact(['categories', 'poster_types']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|unique:events,title',
            'location' => 'required|string',
            'description' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (str_word_count($value) > 50) {
                    $fail('The ' . $attribute . ' cannot exceed 50 words.');
                }
            }],
            'category' => 'required|integer|exists:categories,id',
            'poster_type' => 'required|string|exists:poster_types,name',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'file' => 'required|file|image|mimes:jpeg,png,jpg'
        ]);

        $customErrors = array();
        if (strtotime($request->start_time) > strtotime($request->end_time)) {
            $customErrors['time'] = "Your end time cannot be less than your start time";
        }
        if ($request->poster_type == "recurring") {
            if (!$request->recurring_type) {
                $customErrors['recurring_type'] = "Please select a recurring type";
            }
            if ($request->recurring_type == "weekly" || $request->recurring_type == "fortnightly") {
                if (!$request->recurring_week_fortnight) {
                    $customErrors['frequency'] = "Please select your frequency";
                }
            }
            if ($request->recurring_type == "monthly") {
                if (!$request->monthly_by) {
                    $customErrors['monthly_by'] = "Please select how you want your poster to appear monthly";
                }
            }

            if (!$request->rec_start_date) {
                $customErrors['rec_start_date'] = "Please select your start date";
            }
            if (!$request->rec_end_date) {
                $customErrors['rec_end_date'] = "Please select your end date";
            } else if ($request->rec_start_date > $request->rec_end_date) {
                $customErrors['rec_end_date'] = "Your end date cannot be less than your start date";
            }
        }
        if ($request->poster_type == "non_recurring") {
            if (!$request->nr_date) {
                $customErrors['nr_date'] = "Please select your event date";
            }
        }

        if ($customErrors) {
            return response()->json([$customErrors]);
        } else {
            $poster_type_id = PosterType::where('name', $request->poster_type)->firstOrFail()->id;
            if (($request->poster_type == "non_recurring")) {
                $startDate = $request->nr_date;
                $endDate = $startDate;
            }
            if ($request->poster_type == "recurring") {
                if ($request->recurring_type == "monthly" || $request->recurring_type == "yearly") {
                    $startDate = $request->rec_start_date;
                    $endDate = $request->rec_end_date;
                }
                if ($request->recurring_type == "weekly" || $request->recurring_type == "fortnightly") {
                    $day_date = Carbon::parse($request->recurring_week_fortnight);
                    if ($day_date->isSameDay($request->rec_start_date)) {
                        $startDate = Carbon::parse($request->rec_start_date);
                        // return $startDate;
                    } else {
                        $startDate = Carbon::parse($request->rec_start_date)->next($request->recurring_week_fortnight);
                    }
                    $endDate = $request->rec_end_date;

                    if ($startDate > $endDate) {
                        $customErrors['rec_end_date'] = "Invalid date match for the selected date";
                        return response()->json([$customErrors]);
                    }
                }
            }

            $event = Event::create([
                'title' => ucfirst(strtolower($request->title)),
                'location' => ucfirst(strtolower($request->location)),
                'description' => ucfirst(strtolower($request->description)),
                'category_id' => $request->category,
                'poster_type_id' => $poster_type_id,
                'user_id' => Auth::user()->id,
                'event_start_date' => $startDate,
                'event_end_date' => $endDate,
                'event_start_time' => $request->start_time,
                'event_end_time' => $request->end_time,
            ]);

            // $adminUsers = User::where("is_admin", true)->limit(15)->get();
            $adminUsers = User::where("is_admin", true)->get();

            foreach ($adminUsers as $admin) {
                if ($admin->email) {
                    // Mail::to($admin->email)->send(new NotifyAdminEmail($event));
                    dispatch(new NotifyAdminJob($admin, $event));
                }
            }

            if ($file = $request->File('file')) {
                $extension = $file->getClientOriginalExtension();
                $fileName = "$event->title.$extension";
                $file->move('images/upload/', $fileName);
                PosterImage::create([
                    'name' => $fileName,
                    'event_id' => $event->id
                ]);
            }

            // if(Auth::user()->is_admin){
            //     $active = true;
            //     $event->update([
            //         'active' => $active
            //     ]);
            // }


            if ($request->poster_type == "recurring") {
                if ($request->recurring_type == "weekly" || $request->recurring_type == "fortnightly") {
                    $currentDate = $startDate;
                    // return $currentDate;

                    while ($currentDate <= $endDate) {
                        // Check if the date matches the recurrence pattern
                        // $currentDate->format('N') == $event->start_date->format('N');
                        if ($currentDate->format('1') == $event->event_start_date->format('1')) {
                            // Generate a recurring occurrence for the date
                            RecurringEvent::create([
                                'event_id' => $event->id,
                                'frequency' => $request->recurring_type,
                                'event_date' => $currentDate,
                            ]);
                        }

                        // Move to the next week
                        if ($request->recurring_type == "weekly") {
                            $currentDate = $currentDate->addWeek();
                        }
                        if ($request->recurring_type == "fortnightly") {
                            $currentDate = $currentDate->addWeeks(2);
                        }
                    }
                }

                if ($request->recurring_type == "monthly" || $request->recurring_type == "yearly") {
                    $currentDate = Carbon::parse($startDate);
                    while ($currentDate <= $endDate) {
                        // Create a new Recurring Event model instance
                        RecurringEvent::create([
                            'event_id' => $event->id,
                            'frequency' => $request->recurring_type,
                            'event_date' => $currentDate,
                        ]);

                        if ($request->recurring_type == "monthly" && $request->monthly_by == "monthly_by_day") {
                            $dayOccurenceInMonth = 0;
                            for ($day = 1; $day <= $currentDate->daysInMonth; $day++) {
                                if ($currentDate->clone()->setDay($day)->dayOfWeek == $currentDate->dayOfWeek) {
                                    $dayOccurenceInMonth++;
                                }
                            }

                            $dayOccurenceTillEndOfMonth = 0;
                            for ($day = $currentDate->day; $day <= $currentDate->daysInMonth; $day++) {
                                if ($currentDate->clone()->setDay($day)->dayOfWeek == $currentDate->dayOfWeek) {
                                    $dayOccurenceTillEndOfMonth++;
                                }
                            }

                            $dayNextMonthOccurence = 0;
                            $nextMonth = $currentDate->clone()->addMonth();
                            for ($day = 1; $day <= $nextMonth->daysInMonth; $day++) {
                                if ($nextMonth->clone()->setDay($day)->dayOfWeek == $currentDate->dayOfWeek) {
                                    $dayNextMonthOccurence++;
                                }
                            }

                            if ($dayOccurenceInMonth == 4 && $dayNextMonthOccurence == 4) {
                                $currentDate = $currentDate->addWeeks(4);
                            } else if ($dayOccurenceInMonth == 4 && $dayNextMonthOccurence == 5 && $dayOccurenceTillEndOfMonth == 1) {
                                $currentDate = $currentDate->addWeeks(5);
                            } else if ($dayOccurenceInMonth == 4 && $dayNextMonthOccurence == 5 && $dayOccurenceTillEndOfMonth > 1) {
                                $currentDate = $currentDate->addWeeks(4);
                            } else if ($dayOccurenceInMonth == 5 && $dayNextMonthOccurence == 4 && $dayOccurenceTillEndOfMonth == 1) {
                                $currentDate = $currentDate->addWeeks(4);
                            } else if ($dayOccurenceInMonth == 5 && $dayNextMonthOccurence == 4 && $dayOccurenceTillEndOfMonth > 1) {
                                $currentDate = $currentDate->addWeeks(5);
                            } else {
                                $currentDate = $currentDate->addWeeks(4);
                            }
                        }
                        if ($request->recurring_type == "monthly" && $request->monthly_by == "monthly_by_date") {
                            $currentDate = $currentDate->addMonth();
                        }
                        if ($request->recurring_type == "yearly") {
                            $currentDate = $currentDate->addMonths(12);
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
            ]);
        }
    }

    public function submission()
    {
        return view('pages.poster.submission');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
