@extends('layouts.app')
@section('title', 'Reset Password')

@section('content')
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row">
                            <div class="col-md-5 align-self-center">
                                <img class="img-fluid" src="{{ asset('images/Logo.png') }}" alt="Jesmond logo">
                            </div>
                            <div class="col-md-7">
                                <h4 class="purple text-capitalize text-center mb-5">change password</h4>
                                <div class="row mb-4">
                                    <div class="col">
                                        <input id="email" type="email" class="form-control form_input @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col">
                                        <div class="input-group">
                                            <input id="current_password" type="password" class="form_input form-control @error('password') is-invalid @enderror" name="password" placeholder="New Password" autocomplete="current-password">
                                            <i class="input-group-text fa-regular fa-eye" id="password-type" onclick="showPassword(this)"></i>
                                        </div>
                                        @error('password')
                                            <strong class="text-danger">{{ $message }}</strong>
                                        @enderror
                                        @if (session('error'))
                                            <strong class="text-danger">{{ session('error') }}</strong>
                                        @endif

                                    </div>

                                </div>

                                <div class="row mb-4">
                                    <div class="col">
                                        <div class="input-group">
                                            <input id="password-confirm" type="password" class="form_input form-control" name="password_confirmation" placeholder="Confirm New Password" autocomplete="new-password">
                                            <i class="input-group-text fa-regular fa-eye" id="password-type" onclick="showPassword(this)"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Reset Password') }}
                                        </button>
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
