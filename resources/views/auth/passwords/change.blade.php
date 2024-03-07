@extends('layouts.app')
@section('title', 'Change Password')

@section('content')
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('password.change.store') }}" class="py-5 px-3">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="col-md-5 align-self-center">
                                <img class="img-fluid" src="{{ asset('images/Logo.png') }}" alt="Jesmond logo">
                            </div>
                            <div class="col-md-7">
                                <h4 class="purple text-capitalize text-center mb-5">change password</h4>
                                <div class="align-self-center">
                                    <div class="row mb-4">
                                        <div class="col">
                                            <div class="input-group">
                                                <input id="current_password" type="password" class="form_input form-control @error('current_password') is-invalid @enderror" name="current_password" placeholder="Current Password" autocomplete="current-password">
                                                <i class="input-group-text fa-regular fa-eye" id="password-type" onclick="showPassword(this)"></i>
                                            </div>
                                            @error('current_password')
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
                                                <input id="new_password" type="password" class="form_input form-control @error('new_password') is-invalid @enderror" name="new_password" placeholder="New Password" autocomplete="current-password">
                                                <i class="input-group-text fa-regular fa-eye" id="password-type" onclick="showPassword(this)"></i>
                                            </div>
                                            @error('new_password')
                                                <strong class="text-danger">{{ $message }}</strong>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col">
                                            <div class="input-group">
                                                <input id="new_password_confirmation" type="password" class="form_input form-control" name="new_password_confirmation" placeholder="Confirm New Password" autocomplete="current-password">
                                                <i class="input-group-text fa-regular fa-eye" id="password-type" onclick="showPassword(this)"></i>
                                            </div>
                                        </div>
                                    </div>

                        
                                    <div class="row mb-0 justify-content-center">
                                        <div class="col-md-8 events text-center rounded-pill">
                                            <button type="submit" class="btn purple text-uppercase">
                                                {{ __('Change Password') }}
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
