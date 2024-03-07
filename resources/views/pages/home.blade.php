@extends('layouts.app')

@section('title', 'Home')

@section('css')

<link rel="stylesheet" href="{{ asset('css/pages/style.css') }}">
{{-- include slick carousel css --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==" crossorigin="anonymous" referrerpolicy="no-referrer">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" integrity="sha512-17EgCFERpgZKcm0j0fEq1YCJuyAWdz9KUtv1EjVuaOz8pDnh/0nZxmU6BBXwaaxqoi9PQXnRWqlcDB027hgv9A==" crossorigin="anonymous" referrerpolicy="no-referrer">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
@endsection

@section('content')

@if (session('message'))
<div class="container-fluid px-sm-3 px-md-5 mb-5">
  <div class="alert alert-danger alert-dismissible">
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    <strong>{{ session('message') }}</strong>
  </div>
</div>
@endif

<section class="container-fluid mb-5 set_relative">
  <div class="row mb-5">
    <div class="col-sm-6 col-lg-4">
      <img src="{{ asset('images/Group 2.png') }}" alt="jesmond Logo" width="100%" height="400px" id="jesmond_logo">
    </div>
    <div class="col-sm-10 col-lg-7 slider_div" style="height:400px; margin-left: 3rem;">
      <img src="{{ asset('images/roster1.jpeg') }}" alt="Roster" height="100%" width="100%" class="object-fit-cover m-auto" id="sliding_image">
    </div>
  </div>
  <i class="fa-solid fa-circle-arrow-left" onclick="reverseImg('{{ asset('images') }}')"></i>
  <i class="fa-solid fa-circle-arrow-right" onclick="forwardImg('{{ asset('images') }}')"></i>
</section>

@if (Auth::check())
<div class="container-fluid px-sm-3 px-md-5">
  <p class="text-start fs-2 purple mb-3">Hello, <span class="text-uppercase">{{ Auth::user()->first_name }}</span></p>
</div>
@endif

<div class="container-fluid px-sm-3 px-md-5">
  <div class="mb-5">
    <h5 class="text-uppercase purple mb-2">
      Welcome to <span class="h3">jesmond</span> poster
    </h5>
    <p>Showcase the history, culture, beauty and energy of our community <br>
      No one misses out on the exciting happenings in our town.<br>
      Whether you're organizing an event, or simply looking for something
      fun to do, you will find everything you need here.
    </p>
  </div>

  <div class="mb-5">
    <h5 class="purple mb-4">Event Categories</h5>
    <ul class="list-unstyled list-inline">
      <form class="d-flex flex-wrap justify-content-evenly" action="{{ route('pages.sortCategory') }}" method="GET">
        @foreach ($categories as $cat)
        <li class="mb-3">
          <input class="form-control btn events" type="submit" name="category" value="{{ $cat->name }}">
        </li>
        @endforeach
      </form>
    </ul>
  </div>

  <br />

  @php
  use Carbon\Carbon;
  @endphp

  <div class="row justify-content-center mt-5 mb-5">
    <div id="calendar" class="col-sm-6 col-md=6">
      {{-- <img
          class="img-fluid"
          src="{{ asset('images/multilingual-calendar-component.png') }}"
      > --}}
    </div>
    <div class="col-sm-6 col-md-4">
      <h4 class="text-uppercase purple text-underline">
        <ins>This month at jesmond</ins>
      </h4>
      <p>
        Have a look at what's going on this month and <br>
        join in the fun!
      </p>
      @forelse ($thisMonthEvents as $key => $item)
      @if ($key == 5)
      @break
      @endif
      <a href="{{ $item['url'] }}" class="d-flex text-decoration-none text-dark">
        <i class="p-2 fa-solid fa-angles-right orange"></i>
        <p>
          <span class="text-uppercase purple">{{ $item['title'] }}</span><br>
          <span>{{ Carbon::parse($item['start'])->format('l, M d, Y') }}, </span>
          <span>{{ Carbon::createFromFormat('H:i', substr($item['time'], 0, 5))->format('h:ia') }}</span>
        </p>
      </a>
      @empty
      <h5>There are no upcoming events for this month! Check back another time.</h5>
      @endforelse

    </div>
  </div>
</div>

<br />

<div id="upcoming_events" class="container-fluid events p-5 my-5">
  <h4 class="text-uppercase purple mb-5">Recurring events</h4>
  <div class="row myslide">
    @forelse ($recurring_events as $recurring)
    <div class="col-6 col-sm-4 col-md-3 col-lg-2 mx-3 each_event">
      <a href="{{ route('pages.recurring.show', $recurring->recurrings[0]['id']) }}" class="text-decoration-none text-dark">
        <img class="object-fit-cover" width="100%" height="300px" style="max-height: 300px;" src="{{ asset('images/upload/'.$recurring->poster_image->name) }}" alt="{{ $recurring->title }}">
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
    @empty
    <h4 class="col text-center">There are no upcoming recurring events. Check back later</h4>
    @endforelse
  </div>
</div>


<div style="box-sizing: border-box;" class="container-fluid events p-5 my-5">
  <h4 class="text-uppercase purple mb-5">Live and upcoming events</h4>
  <div class="row myslide">
    @forelse ($upcoming_events as $upcoming)
    <div class="col-6 col-sm-4 col-md-3 col-lg-2 mx-3 each_event">
      <a href="{{ route('pages.live.show', $upcoming->id) }}" class="text-decoration-none text-dark">
        <img class="object-fit-cover" width="100%" height="300px" src="{{ asset('images/upload/'.$upcoming->poster_image->name) }}" alt="{{ $upcoming->title }}">
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
    <h4 class="col text-center">There are no upcoming events. Check back later</h4>
    @endforelse
  </div>
</div>

<br />

<div class="container-fluid px-md-5 mt-5 mb-4">
  <h4 class="text-uppercase purple">Jesmond library blogs</h4>
  <div class="row justify-content-center">
    <div class="col-sm-6 col-md-4 mb-3 d-flex mt-3">
      <img src="{{ asset('images/2022 Booker Prize Year.jpg') }}" alt="2022 Booker Prize Shortlist" width="100px" height="120px">
      <p class="align-self-end px-2">
        <span class="purple">2022 Booker Prize Shortlist</span><br>
        <span>by <a class="text-dark link_special" href="http://jesmondlibrary.org/2022/10/08/2022-booker-prize-shortlist/" target="_blank">foji 8th October 2022</a></span>
      </p>
    </div>
    <div class="col-sm-6 col-md-4 mb-3 d-flex mt-3">
      <img src="{{ asset('images/Thursday Talks are back.jpeg') }}" alt="Thursday Talks are back" width="100px" height="120px">
      <p class="align-self-end px-2">
        <span class="purple">Thursday Talks are back!</span><br>
        <span>by <a class="text-dark link_special" href="http://jesmondlibrary.org/2022/08/26/thursday-talks-are-back/" target="_blank">foji 26th August 2022</a></span>
      </p>
    </div>
    <div class="col-sm-6 col-md-4 mb-3 d-flex mt-3">
      <img src="{{ asset('images/Book quiz.jpg') }}" alt="Book quiz" width="100px" height="120px">
      <p class="align-self-end px-2">
        <span class="purple">Book quiz</span><br>
        <span>by <a class="text-dark link_special" href="http://jesmondlibrary.org/2022/08/31/book-quiz/" target="_blank">foji 31st August 2022</a></span>
      </p>
    </div>
    <div class="col-sm-6 col-md-4 mb-3 d-flex mt-3">
      <img src="{{ asset('images/Happy Christmas.jpeg') }}" alt="Happy Chrisstmas" width="100px" height="120px">
      <p class="align-self-end px-2">
        <span class="purple">Happy Christmas!</span><br>
        <span>by <a class="text-dark link_special" href="http://jesmondlibrary.org/2022/12/24/happy-christmas-2/" target="_blank">JohnPeace 24th December 2022</a></span>
      </p>
    </div>
    <div class="col-sm-6 col-md-4 mb-3 d-flex mt-3">
      <img src="{{ asset('images/A Russian Dissident in Jesmond.jpeg') }}" alt="A Russian Dissident in Jesmond" width="100px" height="120px">
      <p class="align-self-end px-2">
        <span class="purple">1917</span><br>
        <span class="purple">A Russian Dissident in Jesmond</span><br>
        <span>by <a class="text-dark link_special" href="http://jesmondlibrary.org/2022/02/20/1917-a-russian-dissident-in-jesmond/" target="_blank">foji 20th February 2022</a></span>
      </p>
    </div>
    <div class="col-sm-6 col-md-4 mb-3 d-flex mt-3 align-self-center">
      <a href="http://jesmondlibrary.org/blog/" target="_blank" class="text-decoration-none purple">
        <i class="fa-sharp fa-solid fa-cross"></i> View More
      </a>
    </div>
  </div>
</div>
<br>
@endsection

@section('js')
<script src="{{ asset('js/script.js') }}"></script>

<script>
  let rotation = 0;
  window.onload = function() {
    setInterval(() => {
      rotation += 90;
      logo.style.transform = `rotate(${rotation}deg)`;

      slider("{{ asset('images') }}");
    }, 5000);
  };
</script>

{{-- carousel js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
  $(document).ready(function() {
    $(".myslide").slick({
      dots: true,
      infinite: true,
      speed: 500,
      slidesToShow: 5,
      slidesToScroll: 3,
      autoplay: true,
      autoplaySpeed: 2000,
      responsive: [{
          breakpoint: 1024,
          settings: {
            slidesToShow: 4,
            slidesToScroll: 2,
            infinite: true,
            dots: true,
          },
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            dots: false,
          },
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1,
            dots: false,
          },
        },
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ],
    });
  });
</script>

<!-- fullCalendar 2.2.5 -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

<script>
  $(document).ready(function() {

    var calendarEvents = @json($calendarEvents);

    $('#calendar').fullCalendar({
      header: {
        left: 'prev, next today',
        center: 'title',
        right: 'month, agendaWeek, agendaDay'
      },
      events: calendarEvents
    });

    // $('.fc').css('background-color', '#002D62');

  });
</script>

@endsection