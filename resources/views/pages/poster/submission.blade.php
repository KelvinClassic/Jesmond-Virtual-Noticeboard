@extends('layouts.app')

@section('title', 'Poster Submission')

@section('content')
<section class="submission container-fluid my-5" id="submission">
    <div class="row">
        <div class="col-sm-8 mx-auto text-center purple">
            <p>
                Thank you for submitting your event on our website. Our team is currently reviewing your submission to ensure that it meets our guidelines and standards. We appreciate your patience as we work to get your event live on our site as soon as possible.
            </p>
            <p>
                If we need any additional information or clarification from you, we will reach out to you via email. In the meantime, if you have any questions or concerns, please feel free to contact us.
            </p>
        </div>

        <div class="col-sm-8 mx-auto text-center mt-5">
            <p>
                <a class="text-decoration-none text-danger" href="{{ route('pages.index') }}">Back to Homepage</a>
            </p>
            <p>Or</p>
            <p>
                <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            </p>
        </div>
    </div>
</section>
@endsection