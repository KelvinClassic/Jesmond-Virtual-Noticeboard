@extends('layouts.app')
@section('title', 'Login to your account')

@section('content')
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                {{-- <div class="card-header">{{ __('Login') }}
            </div> --}}
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}" class="py-5 px-3">
                    @csrf
                    @method('post')

                    <div class="row">
                        <div class="col-md-5 align-self-center">
                            <img class="img-fluid object-fit-cover" src="{{ asset('images/Logo.png') }}" alt="Jesmond logo">
                        </div>
                        <div class="col-md-7">
                            <h4 class="purple text-uppercase text-center mb-5">Sign into your account</h4>
                            <div class="align-self-center">
                                <div class="row mb-4">
                                    @include('include.alert')
                                    {{-- @if ($errors->any())--}}
                                    {{-- <strong class="text-danger">Invalid email or password. Try--}}
                                    {{-- again</strong>--}}
                                    {{-- @endif--}}
                                </div>
                                <div class="row mb-4">
                                    {{-- <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label> --}}

                                    <div class="col">
                                        <input id="email" type="email" class="form_input form-control" name="email" placeholder="Email address" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    {{-- <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label> --}}

                                    <div class="col">
                                        <div class="input-group">
                                            <input id="password" type="password" class="form_input form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
                                            <i class="input-group-text fa-regular fa-eye" id="password-type" onclick="showPassword(this)"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <button type="submit" class="purple text-uppercase events form-control d-block mx-auto rounded-pill my-2" style="width: 70% !important">
                                        {{ __('Sign In') }}
                                    </button>
                                    <div class="col-sm-6 mt-3">
                                        @if (Route::has('password.request'))
                                        <a class="text-decoration-none purple fs-6" href="{{ route('password.request') }}">
                                            {{ __('Forgot Password?') }}
                                        </a>
                                        @endif
                                    </div>
                                    <div class="col-sm-6 text-sm-end mt-3">
                                        <a class="text-decoration-none purple fs-6" href="{{ route('register') }}">{{ __('Create Account?') }}</a>
                                    </div>
                                </div>


                            </div>

                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection