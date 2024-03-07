@extends('layouts.app')
@section('title', 'Create your account')

@section('content')
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                {{-- <div class="card-header">{{ __('Login') }}
            </div> --}}
            @include('include.alert')
            <div style="padding-right: 2rem;" class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    @method('post')

                    <div class="row">
                        <div class="col-md-5 align-self-center">
                            <img class="img-fluid object-fit-cover" src="{{ asset('images/Logo.png') }}" alt="Jesmond logo">
                        </div>
                        <div class="col-md-7">
                            <h4 class="purple text-capitalize mt-4 mb-4 text-center">Create Account</h4>
                            <div class="align-self-center">
                                <div class="row mb-4">
                                    <div class="col">
                                        <input id="first_name" type="text" class="form_input form-control @error('first_name') is-invalid @enderror" name="first_name" placeholder="First name" value="{{ old('first_name') }}" value="{{ old('password') }}" autocomplete="first_name" autofocus>

                                        @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col">
                                        <input id="last_name" type="text" class="form_input form-control @error('last_name') is-invalid @enderror" name="last_name" placeholder="Last name" value="{{ old('last_name') }}" value="{{ old('password') }}" autocomplete="last_name">

                                        @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    {{-- <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label> --}}

                                    <div class="col">
                                        <input id="email" type="email" class="form_input form-control @error('email') is-invalid @enderror" name="email" placeholder="Email address" value="{{ old('email') }}" value="{{ old('password') }}" autocomplete="email">

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col">
                                        <input id="phone_number" type="tel" class="form_input form-control @error('phone_number') is-invalid @enderror" name="phone_number" placeholder="Telephone" value="{{ old('phone_number') }}" value="{{ old('password') }}" autocomplete="phone_number">

                                        @error('phone_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col">
                                        <div class="input-group">
                                            <input id="password" type="password" class="form_input form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" value="{{ old('password') }}" autocomplete="current-password">
                                            <i class="input-group-text fa-regular fa-eye" id="password-type" onclick="showPassword(this)"></i>
                                        </div>

                                        @error('password')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col input-group">
                                        <input id="password-confirm" type="password" class="form_input form-control" name="password_confirmation" placeholder="Confirm Password" value="{{ old('password') }}" autocomplete="new-password">
                                        <i class="input-group-text fa-regular fa-eye" id="password-type" onclick="showPassword(this)"></i>
                                    </div>

                                </div>

                                <button type="submit" class="purple text-capitalize events form-control d-block mx-auto rounded-pill my-4" style="width: 70% !important">
                                    {{ __('Create account') }}
                                </button>
                            </div>
                            <p class=" fs-6 text-center">Already have an account? <a class="text-decoration-none purple" href="{{ route('login') }}">{{ __('Login') }}</a></p>

                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
</div>

@endsection