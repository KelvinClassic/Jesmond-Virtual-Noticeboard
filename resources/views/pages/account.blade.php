@extends('layouts.app')

@section('title', 'My Account')
    
@section('content')
@if (session('message'))
<div class="alert alert-success alert-dismissible">
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  <strong>{{ session('message') }}</strong>
</div>
@endif

    <section id="account" class="container-fluid account px-3 px-md-5 my-5">
        <div class="row mb-3">
            <div class="col-4 col-sm-3 col-md-2 purple">Name</div>
            <div class="col-8 col-sm-9 col-md-10">{{ $user->first_name }} {{ $user->last_name }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-4 col-sm-3 col-md-2 purple">Email</div>
            <div class="col-8 col-sm-9 col-md-10">{{ $user->email }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-4 col-sm-3 col-md-2 purple">Telephone</div>
            <div class="col-8 col-sm-9 col-md-10">{{ $user->phone_number }}</div>
        </div>
        <div class="row">
            <div class="col-sm-3 col-md-2"><a class="text-decoration-none purple" href="{{ route('password.change') }}">Change Password</a></div>
            <div class="col-sm-3 col-md-2"><a class="text-decoration-none purple" href="{{ route('password.request') }}">Reset Password</a></div>
        </div>

    </section>
@endsection