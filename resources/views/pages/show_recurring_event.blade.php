@extends('layouts.app')

@section('title', $event->event->title)

@section('content')
<section id="single_event" class="container-fluid single_event px-3 px-md-5 my-5">
    <a class="text-decoration-none text-dark" style="font-size: 40px;" href="{{ url()->previous() }}">
        <i class="fa-solid fa-circle-arrow-left"></i>
    </a>
    <div class="row my-5 justify-content-center">
        <div class="col-md-4 text-center">
            @if ($event->poster_image)
            <img class="border border-2 border-dark rounded-3 p-5 object-fit-cover" width="80%" height="550px" src="{{ asset('images/upload/'.$event->event->poster_image->name) }}" alt="{{ $event->event->title }}">
            @endif

        </div>
        <div class="col-md-6 mt-5 mt-md-0 px-5 px-md-0">
            <div class="d-flex mb-5">
                <i class="align-self-center me-3 purple fa-solid fa-book"></i>
                <div class="purple">{{ $event->event->title }}</div>
            </div>

            @php
            use Carbon\Carbon;
            @endphp
            <div class="d-flex mb-5">
                <i class="align-self-center me-3 purple fa-solid fa-calendar-day"></i>
                <div>{{ Carbon::parse($event->event_date)->format('l, M d, Y') }}</div>
            </div>
            <div class="d-flex mb-5">
                <i class="align-self-center me-3 purple fa-solid fa-wave-square"></i>
                <div>{{ ucwords($event->frequency) }}</div>
            </div>
            <div class="d-flex mb-5">
                <i class="align-self-center me-3 purple fa-solid fa-location-dot"></i>
                <div>{{ $event->event->location }}</div>
            </div>
            <div class="d-flex mb-5">
                <i class="align-self-center me-3 purple fa-regular fa-clock"></i>
                <div>
                    {{ Carbon::createFromFormat('H:i', substr($event->event->event_start_time, 0, 5))->format('h:ia') }} -
                    {{ Carbon::createFromFormat('H:i', substr($event->event->event_end_time, 0, 5))->format('h:ia') }}
                </div>
            </div>
            <div class="d-flex mb-5">
                <i class="align-self-center me-3 purple fa-solid fa-circle-info"></i>
                <div {{ $event->event->description ? "" : "class=text-danger" }}>{{ $event->event->description ? $event->event->description : "No description available" }}</div>
            </div>
            <div class="d-flex mb-5">
                <i class="align-self-center me-3 purple fa-solid fa-phone-volume"></i>
                <div>{{ $event->event->user->phone_number }}</div>
            </div>


            @auth
            @if ($isEventBookmarked == false)
            <div class="d-flex">
                <form method="POST" action="{{ route('pages.bookmarks.add',['eventId'=>$event->event_id]) }}">
                    @csrf
                    <button type="submit" class="events purple form-control d-block mx-auto rounded-pill">Bookmark Event</button>
                </form>
            </div>

            @endif
            @endauth

        </div>
    </div>

</section>
@endsection