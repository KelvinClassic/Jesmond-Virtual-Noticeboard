@extends('layouts.app')

@section('title', 'Events')

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/events.css') }}">
@endsection

@section('content')
<div id="event" class="event my-5">
  <section class="container-fluid px-3 px-md-5">
    <div class="categories row">
      <div class="sort_dropdown col-sm-4 col-md-2 events me-3">
        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-mdb-toggle="dropdown" aria-expanded="false">
          Categories
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <form action="{{ route('pages.sortCategory') }}" method="GET">
            @foreach ($categories as $cat)
            <li>
              <input class="form-control btn" type="submit" name="category" value="{{ $cat->name }}">
            </li>
            @endforeach
          </form>
        </ul>
      </div>
      <div class="sort_dropdown col-sm-4 col-md-2 events me-3">
        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-mdb-toggle="dropdown" aria-expanded="false">
          When
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <form action="{{ route('pages.sortWhen') }}" method="get">
            <li>
              <input type="submit" class="form-control btn" name="when" value="Today">
            </li>
            <li>
              <input type="submit" class="form-control btn" name="when" value="This Week">
            </li>
            <li>
              <input type="submit" class="form-control btn" name="when" value="This Month">
            </li>
          </form>
        </ul>
      </div>
      <div class="sort_dropdown col-sm-4 col-md-2 events me-3">
        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-mdb-toggle="dropdown" aria-expanded="false">
          Recurring Events
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <form action="{{ route('pages.sortFrequency') }}" method="get">
            <li>
              <input type="submit" class="form-control btn" name="frequency" value="Weekly">
            </li>
            <li>
              <input type="submit" class="form-control btn" name="frequency" value="Fortnightly">
            </li>
            <li>
              <input type="submit" class="form-control btn" name="frequency" value="Monthly">
            </li>
            <li>
              <input type="submit" class="form-control btn" name="frequency" value="Yearly">
            </li>
          </form>
        </ul>
      </div>

      <div class="sort_dropdown col-sm-4 col-md-2 events">
        <a class="btn" href="{{ route('pages.events') }}"> Clear search results </a>
      </div>
    </div>

    <h4 class="text-uppercase purple mt-5">search result(s)</h4>

  </section>

  @php
  use Carbon\Carbon;
  @endphp


  @if ($upcoming_events != "neglect")
  <section class="container-fluid events p-5 my-5">
    <h4 class="text-uppercase purple mb-3 fs-5">Live and upcoming events</h4>
    <div class="float-start ms-2">
      {{ $upcoming_events->appends(['upcoming_events_page' => $upcoming_events->currentPage()])->links() }}
    </div>
    <div class="row justify-content-evenly" style="clear:both;">
      @forelse ($upcoming_events as $upcoming)
      <div class="col-10 col-sm-4 col-md-3 col-lg-2 mb-3 px-0 me-1 each_event">
        <a href="{{ route('pages.live.show', $upcoming->id) }}" class="text-decoration-none text-dark">
          <img width="100%" height="300px" src="{{ asset('images/upload/'.$upcoming->poster_image->name) }}" alt="{{ $upcoming->title }}">
          <div class="text-center">
            <p class="mt-2 px-2">
            <h6 class="purple">{{ $upcoming->title }}</h6>
            {{ Carbon::parse($upcoming->event_start_date)->format('l, M d, Y') }}<br>
            {{ Carbon::createFromFormat('H:i', substr($upcoming->event_start_time, 0, 5))->format('h:ia') }} -
            {{ Carbon::createFromFormat('H:i', substr($upcoming->event_end_time, 0, 5))->format('h:ia') }}
            </p>
          </div>
        </a>
      </div>
      @empty
      <h4 class="col">There are no upcoming events. Check back later</h4>
      @endforelse
    </div>
    <div class="float-end me-2">
      {{ $upcoming_events->appends(['upcoming_events_page' => $upcoming_events->currentPage()])->links() }}
    </div>

  </section>

  @endif

  <section class="container-fluid events p-5 my-5">
    <h4 class="text-uppercase purple mb-3 fs-5">Recurring events</h4>
    <div class="float-start ms-2">
      {{ $recurring_events->appends(['recurring_events_page' => $recurring_events->currentPage()])->links() }}
    </div>
    <div class="row justify-content-evenly" style="clear:both;">
      @forelse ($recurring_events->sortBy(function ($recurring){
      if(count($recurring->recurrings) > 0){
      return $recurring->recurrings[0]['event_date'];
      }
      }) as $recurring)
      @if (count($recurring->recurrings) > 0)
      <div class="col-10 col-sm-4 col-md-3 col-lg-2 mb-5 px-0 me-1 each_event">
        <a href="{{ route('pages.recurring.show', $recurring->recurrings[0]['id']) }}" class="text-decoration-none text-dark">
          <img width="100%" height="300px" src="{{ asset('images/upload/'.$recurring->poster_image->name) }}" alt="{{ $recurring->title }}">
          <div class="text-center">
            <p class="mt-2 px-2">
            <h6 class="purple">{{ $recurring->title }}</h6>
            Type: {{ ucfirst($recurring->recurrings[0]['frequency']) }}<br>
            {{ Carbon::parse($recurring->recurrings[0]['event_date'])->format('l, M d, Y') }}<br>
            {{ Carbon::createFromFormat('H:i', substr($recurring->event_start_time, 0, 5))->format('h:ia') }} -
            {{ Carbon::createFromFormat('H:i', substr($recurring->event_end_time, 0, 5))->format('h:ia') }}
            </p>
          </div>
        </a>
      </div>

      @endif
      @empty
      <h4 class="col">There are no upcoming recurring events. Check back later</h4>
      @endforelse
    </div>
    <div class="float-end me-2">
      {{ $recurring_events->appends(['recurring_events_page' => $recurring_events->currentPage()])->links() }}
    </div>

    <div class="row my-2" style="clear: both">
      {{-- <div class="col-md-1"></div> --}}
      <div class="col px-5">
        <i class="fa-solid fa-circle-plus"></i>
        @if (Auth::check())
        <a href="{{ route('poster.create') }}" class="text-decoration-none purple">Click here to create an event</a>
        @else
        <a href="{{ route('login') }}" class="text-decoration-none purple">To create an event, please SIGN IN</a>
        @endif
      </div>
    </div>

  </section>


</div>

@endsection