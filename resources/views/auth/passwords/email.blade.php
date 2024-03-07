@extends('layouts.app')
@section('title', 'Reset Password')
    
@section('content')
@if (session('message'))
    <div class="alert alert-success alert-dismissible">
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    <strong>{{ session('message') }}</strong>
    </div>
@endif

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('password.email') }}" class="py-5 px-3">
                        @csrf
                        @method('post')
                        <div class="row">
                            <div class="col-md-5 align-self-center">
                                <img class="img-fluid" src="{{ asset('images/Logo.png') }}" alt="Jesmond logo">
                            </div>
                            <div class="col-md-7">
                                <h4 class="purple text-capitalize text-center mb-5">reset password</h4>
                                <div class="align-self-center">
                                    <div class="row mb-4">            
                                        <div class="col">
                                            <input id="email" type="email" class="form_input form-control @error('email') is-invalid @enderror" name="email" placeholder="Email address" value="{{ old('email') }}" required autocomplete="email" autofocus>
            
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                        
                                    <div class="row mb-0 justify-content-center">
                                        <div class="col-md-8 events text-center rounded-pill">
                                            <button type="submit" class="btn purple text-uppercase">
                                                {{ __('Send Reset Code') }}
                                            </button>
            
                                            
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
