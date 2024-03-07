@extends('layouts.app')

@section('title', 'Create New Poster')

@section('content')
<section id="poster" class="container-fluid poster px-3 px-md-5 my-5">
    <form id="createPoster" action="{{ route('poster.store') }}" method="post" enctype="multipart/form-data" class="row">
        @csrf
        @method('post')
        <div class="col-md-6">
            <div class="row">
                <div class="col-10 col-md-8 mx-auto text-white text-center rounded-2" style="padding: 2%; border: 2px solid transparent; background-color: #878787;">
                    <div style="padding: 100px 10%; border: 7px solid white">
                        <div class="text-uppercase h2">upload your poster</div>
                        <input type="file" name="file" class="form-control">
                        <strong id="file_err" class="text-danger"></strong>
                        <a class="d-block mt-3 btn btn-success" href="{{ asset('document/Jesmond Poster Template.pdf') }}" download>Download Sample File <i class="fa-solid fa-download"></i></a>
                    </div>
                </div>
                <p class="text-center orange">
                    POSTER format should be either JPEG, PNG or JPG
                </p>
            </div>
        </div>

        <div class="col-md-5">
            <div class="row mb-3">
                <div>
                    <label for="title" class="col-12 purple mb-2 text-capitalize">poster title</label>
                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
                    <strong id="title_err" class="text-danger"></strong>
                </div>

            </div>

            <div class="row mb-3">
                <div>
                    <label for="location" class="col-12 purple mb-2 text-capitalize">location</label>
                    <input type="text" id="location" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}">
                    <strong id="location_err" class="text-danger"></strong>
                </div>
            </div>

            <div class="row mb-3">
                <div>
                    <label for="description" class="col-12 purple mb-2 text-capitalize">description</label>
                    <textarea name="description" oninput="countRemainingWords(this)" class="form-control" id="description" cols="30" rows="5">{{ old('description') }}</textarea>
                    <p class="text-danger"></p>
                    <strong id="description_err" class="text-danger"></strong>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 mb-sm-3">
                    <label for="category" class="col-12 purple mb-2 text-capitalize" data-bs-toggle="tooltip" title="Select the General Category if your category is not listed.">
                        category <i class="fa-solid fa-circle-info"></i>
                    </label>
                    <select name="category" id="category" class="form-control @error('category') is-invalid @enderror">
                        <option value="">Select category</option>
                        @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $cat->id == old('category') ? "selected" : "" }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <strong id="category_err" class="text-danger"></strong>
                </div>
            </div>

            <div class="row mb-3">
                <div id="poster_time" class="col-sm-6 mb-sm-3">
                    <label for="start_time" class="col-12 purple mb-2 text-capitalize">start time</label>
                    <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}" class="form-control @error('time') is-invalid @enderror" style="width: 70% !important">
                    <strong id="start_time_err" class="text-danger"></strong>
                </div>
                <div id="poster_time" class="col-sm-6 mb-sm-3">
                    <label for="end_time" class="col-12 purple mb-2 text-capitalize">end time</label>
                    <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}" class="form-control @error('time') is-invalid @enderror" style="width: 70% !important">
                    <strong id="end_time_err" class="text-danger"></strong>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-6 mb-3 mb-sm-0 purple">
                    Is this event a recurring event?
                </div>

                <div class="col-sm-6 mb-3 mb-sm-0">
                    <div class="row">
                        @foreach ($poster_types as $key => $item)
                        <div class="col-5">
                            <input type="radio" name="poster_type" value="{{ $item->name }}" {{ $item->name == old('poster_type') ? "checked" : "" }} id="radio_{{ $item->name }}">
                            <label for="radio_{{ $item->name }}" class="purple">{{ $item->name == "recurring" ? "Yes" : "No" }}</label>
                        </div>
                        @endforeach
                        <strong id="poster_type_err" class="text-danger"></strong>
                    </div>
                </div>
            </div>

            {{-- recurring --}}
            <section id="recurring" class="recurring">
                <div class="row mb-3">
                    <div class="col-6 col-sm-3 text-sm-center">
                        <input type="radio" name="recurring_type" value="weekly" id="rec_week">
                        <label for="rec_week" class="purple">Weekly</label>
                    </div>
                    <div class="col-6 col-sm-3 text-sm-center">
                        <input type="radio" name="recurring_type" value="monthly" id="rec_month">
                        <label for="rec_month" class="purple">Monthly</label>
                    </div>
                    <div class="col-6 col-sm-3 text-sm-center">
                        <input type="radio" name="recurring_type" value="fortnightly" id="rec_fortnight">
                        <label for="rec_fortnight" class="purple">Fortnightly</label>
                    </div>
                    <div class="col-6 col-sm-3 text-sm-center">
                        <input type="radio" name="recurring_type" value="yearly" id="rec_year">
                        <label for="rec_year" class="purple">Yearly</label>
                    </div>
                    <strong id="recurring_type_err" class="text-center text-danger"></strong>
                </div>
            </section>

            <div id="recurring_week_fortnight" class="row px-3">
                <div class="col-3 mb-2">
                    <input type="radio" name="recurring_week_fortnight" value="Sunday" id="rec_week_sunday">
                    <label for="rec_week_sunday" class="purple">Sun</label>
                </div>
                <div class="col-3 mb-2">
                    <input type="radio" name="recurring_week_fortnight" value="Monday" id="rec_week_monday">
                    <label for="rec_week_monday" class="purple">Mon</label>
                </div>
                <div class="col-3 mb-2">
                    <input type="radio" name="recurring_week_fortnight" value="Tuesday" id="rec_week_tuesday">
                    <label for="rec_week_tuesday" class="purple">Tue</label>
                </div>
                <div class="col-3 mb-2">
                    <input type="radio" name="recurring_week_fortnight" value="Wednesday" id="rec_week_wednesday">
                    <label for="rec_week_wednesday" class="purple">Wed</label>
                </div>
                <div class="col-3 mb-2">
                    <input type="radio" name="recurring_week_fortnight" value="Thursday" id="rec_week_thursday">
                    <label for="rec_week_thursday" class="purple">Thur</label>
                </div>
                <div class="col-3 mb-2">
                    <input type="radio" name="recurring_week_fortnight" value="Friday" id="rec_week_friday">
                    <label for="rec_week_friday" class="purple">Fri</label>
                </div>
                <div class="col-3 mb-2">
                    <input type="radio" name="recurring_week_fortnight" value="Saturday" id="rec_week_saturday">
                    <label for="rec_week_saturday" class="purple">Sat</label>
                </div>
                <strong id="rec_frequency_err" class="text-center text-danger mb-3"></strong>
            </div>


            <div id="poster_date_nonrec" class="row mb-3">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <div class="row">
                        <label for="nr_date" class="col-12 purple mb-2 text-capitalize">date</label>
                        <input type="date" id="nr_date" name="nr_date" min="{{ date('Y-m-d') }}" class="form-control" style="width: 70% !important">
                    </div>
                    <strong id="nr_date_err" class="text-danger mb-3"></strong>
                </div>
            </div>

            <div id="monthly_by" class="row mb-3 justify-content-center">
                <div class="col-6 col-sm-4">
                    <input type="radio" name="monthly_by" value="monthly_by_date" {{ old('monthly_by') == "monthly_by_date" ? "checked" : "" }} id="monthly_by_date">
                    <label for="monthly_by_date" class="purple">By Date</label>
                </div>
                <div class="col-6 col-sm-4">
                    <input type="radio" name="monthly_by" value="monthly_by_day" {{ old('monthly_by') == "monthly_by_day" ? "checked" : "" }} id="monthly_by_day">
                    <label for="monthly_by_day" class="purple">By Day</label>
                </div>
                <strong id="monthly_by_err" class="text-sm-center text-danger"></strong>
            </div>


            <div id="poster_date_rec" class="row mb-3">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <div class="row">
                        <label for="rec_start_date" class="col-12 purple mb-2 text-capitalize">start date</label>
                        <input type="date" id="rec_start_date" name="rec_start_date" min="{{ date('Y-m-d') }}" class="form-control" style="width: 70% !important">
                    </div>
                    <strong id="rec_start_date_err" class="text-danger mb-3"></strong>
                </div>
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <div class="row">
                        <label for="rec_end_date" class="col-12 purple mb-2 text-capitalize">end date</label>
                        <input type="date" id="rec_end_date" name="rec_end_date" min="{{ date('Y-m-d') }}" class="form-control" style="width: 70% !important">
                    </div>
                    <strong id="rec_end_date_err" class="text-danger mb-3"></strong>
                </div>

            </div>



            <button type="submit" class="text-uppercase events purple form-control d-block mx-auto rounded-pill my-4" style="width: 50% !important">submit poster</button>
            <div id="spinner" class="row justify-content-center d-none">
                <div class="col-1 spinner-border text-warning"></div>
            </div>
        </div>
    </form>

    <div class="col-md-6">
        <ul class="row mt-5 orange">
            <li>
                Please note that once the poster is submitted, it will be reviewed by the admin before it becomes public. The review process is to ensure that posters meet the website's standards and do not contain inappropriate or incomplete content.
            </li>
            <li>
                Notification will be sent to the registered email to inform users once their poster has been reviewed.
            </li>
            <li>
                Just remember to follow the guidelines and be patient while your poster undergoes admin review.
            </li>
        </ul>
    </div>


</section>
@endsection

@section('js')
<script src="{{ asset('js/getRemainingWords.js') }}"></script>
<script src="{{ asset('js/createPoster.js') }}"></script>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    postForm.addEventListener('submit', function(e) {
        e.preventDefault();
        createPoster("{{ route('poster.store') }}", "{{route('poster.submission')}}");
    });
</script>
@endsection