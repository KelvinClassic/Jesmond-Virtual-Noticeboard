<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\RejectedEvents;
use App\Http\Controllers\Controller;
use App\Mail\AcceptPoster;
use App\Mail\AddAdmin;
use App\Mail\AddSuperAdmin;
use App\Mail\RejectPoster;
use App\Mail\RemoveAdmin;
use App\Mail\RemoveSuperAdmin;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{

    public function category()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('pages.admin.category.index', compact('categories'));
    }

    public function storecategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:20|unique:categories,name',
        ]);
        $category = Category::create([
            'name' => ucwords(strtolower($request->category))
        ]);
        return redirect()->back()->with('message', 'Category ' . $category->name . ' created successfully');
    }

    public function deleteCategory($id)
    {
        Category::where('id', $id)->delete();
        return redirect()->back()->with('message', 'Category deleted successfully');
    }

    public function approval(Request $request)
    {

        $events = Event::with(['poster_image', 'poster_type', 'recurrings'])
            ->where('event_end_date', '>=', date('Y-m-d'))
            ->where('active', false)
            ->whereHas('poster_image')
            ->orderBy('event_start_date')
            ->paginate(10);

        // return $events;

        // dd($events);

        // $events = $eventList->filter(function ($value) {
        //     return $value->poster_image;
        // })->all();

        return view('pages.admin.approval.index', compact(['events']));
    }

    public function showApproval($id)
    {
        $event = Event::with(['poster_image', 'poster_type', 'user', 'recurrings'])
            ->where('active', false)
            ->where('event_end_date', '>=', date('Y-m-d'))
            ->where('id', $id)
            ->firstOrFail();

        // return $event;

        $categories = Category::orderByRaw("CASE WHEN name = 'General' THEN 0 ELSE 1 END, name")->get();


        return view('pages.admin.approval.show', compact(['event', 'categories']));
    }

    public function reviewPoster(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|integer|exists:categories,id',
            'feedback' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (str_word_count($value) > 50) {
                    $fail('The ' . $attribute . ' cannot exceed 50 words.');
                }
            }],
        ]);
        $event = Event::with(['poster_image', 'user', 'poster_type', 'recurrings'])->where('id', $id);
        $review = $event->firstOrFail();
        if ($request->has('accept')) {
            // Accept button was clicked
            $event->update([
                'active' => true,
                'category_id' => $request->category,
                'approved_date' => date('Y-m-d H:i:s')
            ]);

            Mail::to($review->user->email)->send(new AcceptPoster($review));

            return redirect()->route('admin.approval')->with('message', "$review->title is now live on the website");
        } elseif ($request->has('reject')) {
            $image = $event->firstOrfail()->poster_image->name;

            if ($image) {
                // Reject button was clicked
                unlink(public_path() . '/images/upload/' . $image);
            }

            $rec_type = "";
            if (count($review->recurrings) > 0) {
                $rec_type = " / " . ucwords($review->recurrings[0]['frequency']);
            }
            RejectedEvents::create([
                'title' => $review->title,
                'date_posted' => $review->created_at,
                'user_id' => $review->user_id,
                'type' => ucwords($review->poster_type->name) . $rec_type,
                'reason' => ucfirst($request->reason)
            ]);
            $event->delete();
            $review['reason'] = ucfirst($request->reason);
            Mail::to($review->user->email)->send(new RejectPoster($review));

            return redirect()->route('admin.approval')->with('message', "$review->title is rejected successfully");
        }

        // Default action if neither button was clicked
        return redirect()->route('admin.approval')->with('message', "Invalid input!");
    }

    public function deletePoster($id)
    {
        Event::where('id', $id)->delete();
        return redirect()->back()->with('message', 'Event deleted successfully');
    }

    public function changeCategory(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|integer|exists:categories,id'
        ]);
        Event::where('id', $id)->update([
            'category_id' => $request->category,
        ]);

        return redirect()->back()->with('message', 'Category changed successfully');
    }

    public function getAdmins()
    {

        $users = User::where("is_admin", true)->get();

        return view('pages.admin.createAdmin.view', compact(['users']));
    }

    public function addAdmin(Request $request)
    {
        $new_admin_email_address = $request->input('email');
        $user = User::where("email", $new_admin_email_address)->first();

        if ($user) {
            $user->is_admin = true;
            $user->updated_at = date('Y-m-d H:i:s');
            $user->save();

            Mail::to(env('MAIL_FROM_ADDRESS'))->send(new AddAdmin($user));

            return redirect()->back()->with('message', 'Admin added successfully');
        } else {
            return redirect()->back()->with('message', 'User with this email does not exist');
        }

        return redirect()->back()->with('message', 'Something unexpected happened');
    }

    public function removeAdmin(Request $request, $id)
    {
        $user = User::where("id", $id)->where("is_admin", true)->first();

        if ($user) {
            $user->is_admin = false;
            $user->updated_at = date('Y-m-d H:i:s');

            $user->save();

            Mail::to(env('MAIL_FROM_ADDRESS'))->send(new RemoveAdmin($user));

            return redirect()->back()->with('message', 'Admin removed successfully');
        } else {
            return redirect()->back()->with('message', 'User with this email does not exist');
        }

        return redirect()->back()->with('message', 'Something unexpected happened');
    }


    // super admins

    public function getSuperAdmins()
    {

        $users = User::where("is_super_admin", true)->get();

        return view('pages.admin.createAdmin.viewSuperAdmin', compact(['users']));
    }

    public function addSuperAdmin(Request $request)
    {
        $new_admin_email_address = $request->input('email');
        $user = User::where("email", $new_admin_email_address)->first();

        if ($user) {
            $user->is_super_admin = true;
            $user->is_admin = true;
            $user->updated_at = date('Y-m-d H:i:s');
            $user->save();

            Mail::to(env('MAIL_FROM_ADDRESS'))->send(new AddSuperAdmin($user));


            return redirect()->back()->with('message', 'Super Admin added successfully');
        } else {
            return redirect()->back()->with('message', 'User with this email does not exist');
        }

        return redirect()->back()->with('message', 'Something unexpected happened');
    }

    public function removeSuperAdmin(Request $request, $id)
    {
        $user = User::where("id", $id)->where("is_admin", true)->first();

        if ($user) {
            $user->is_super_admin = false;
            $user->updated_at = date('Y-m-d H:i:s');

            $user->save();

            Mail::to(env('MAIL_FROM_ADDRESS'))->send(new RemoveSuperAdmin($user));


            return redirect()->back()->with('message', 'Super Admin removed successfully');
        } else {
            return redirect()->back()->with('message', 'User with this email does not exist');
        }

        return redirect()->back()->with('message', 'Something unexpected happened');
    }
}
