@extends('layouts.app')

@section('title', "Review - $event->title")

@section('content')
<form action="{{ route('admin.approval.review', $event->id) }}" method="POST" id="single_event" class="container-fluid single_event px-3 px-md-5 my-5">
    @csrf
    @method('post')

    <div class="row">
        <div class="col-md-6 text-center">
            @if ($event->poster_image)
            <img class="border border-2 border-dark rounded-3 p-5 object-fit-cover" width="80%" height="550px" src="{{ asset('images/upload/'.$event->poster_image->name) }}" alt="{{ $event->title }}">
            @endif
        </div>
        <div class="col-md-6 mt-5 mt-md-0 px-5 px-md-0">
            @error('feedback')
            <strong class="text-danger">{{ $message }}</strong>
            @enderror

            <div class="d-flex mb-5">
                <i class="align-self-center me-3 purple fa-solid fa-book"></i>
                <div class="purple">{{ $event->title }}</div>
            </div>

            @php
            use Carbon\Carbon;
            @endphp
            <div class="d-flex mb-5">
                <i class="align-self-center me-3 purple fa-solid fa-calendar-day"></i>
                <div>
                    {{ Carbon::parse($event->event_start_date)->format('l, M d, Y') }}
                    @if (count($event->recurrings) > 0)
                    - {{ Carbon::parse($event->recurrings[count($event->recurrings) - 1]->event_date)->format('l, M d, Y') }}
                    @endif

                </div>
            </div>
            <div class="d-flex mb-5">
                <i class="align-self-center me-3 purple fa-solid fa-wave-square"></i>
                @if ($event->poster_type->name == "recurring")
                <div>{{ ucwords($event->recurrings->first()->frequency) }}</div>
                @else
                <div>Non recurring</div>
                @endif

            </div>

            <div class="d-flex mb-5">
                <i class="align-self-center me-3 purple fa-solid fa-location-dot"></i>
                <div>{{ $event->location }}</div>
            </div>
            <div class="d-flex mb-5">
                <i class="align-self-center me-3 purple fa-regular fa-clock"></i>
                <div>
                    {{ Carbon::createFromFormat('H:i', substr($event->event_start_time, 0, 5))->format('h:ia') }} -
                    {{ Carbon::createFromFormat('H:i', substr($event->event_end_time, 0, 5))->format('h:ia') }}
                </div>
            </div>
            <div class="d-flex mb-5">
                <i class="align-self-center me-3 purple fa-solid fa-circle-info"></i>
                <div {{ $event->description ? "" : "class=text-danger" }}>{{ $event->description ? $event->description : "No description available" }}</div>
            </div>
            <div class="d-flex mb-5">
                <i class="align-self-center me-3 purple fa-solid fa-phone-volume"></i>
                <div>{{ $event->user->phone_number }}</div>
            </div>

            <div class="row mb-5">
                <div class="col-sm-4 col-md-3 col-lg-2">Category:</div>
                <div class="col-sm-8 col-md-9  col-lg-10">
                    <select name="category" id="category" class="form-control @error('category') is-invalid @enderror">
                        @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category') ? old('category') : ($cat->id == $event->category_id ? "selected" : "" )}}>{{ $cat->name }}</option>
                        @endforeach
                    </select>

                </div>

            </div>


        </div>
    </div>

    <div class="row">
        <div class="col-md-10 mx-auto">
            <h4 class="text-uppercase purple mt-5">feedback</h4>
            <div>
                <textarea name="feedback" oninput="countRemainingWords(this)" id="" cols="30" rows="10" class="form-control events">{{ old('feedback') }}</textarea>
                <p class="text-danger"></p>
                @error('feedback')
                <strong class="text-danger">{{ $message }}</strong>
                @enderror
            </div>
            <div class="row mt-4">
                <div class="col-8 col-sm-4 mx-auto">
                    <div class="row">
                        <div class="col p-2">
                            <button class="btn purple text-uppercase" type="submit" name="accept" value="1">Accept</button>
                        </div>
                        <div class="col p-2">
                            <button class="btn purple text-uppercase" type="submit" name="reject" value="1">Reject</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>
@endsection

@section('js')
<script src="{{ asset('js/getRemainingWords.js') }}"></script>
@endsection