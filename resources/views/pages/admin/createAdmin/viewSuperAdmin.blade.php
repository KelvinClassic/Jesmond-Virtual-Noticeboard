@extends('layouts.app')
@section('title', 'Categories')

@section('css')

@section('content')

@if (session('message'))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    <strong>{{ session('message') }}</strong>
</div>
@endif

<section class="container-fluid px-3 px-md-5">
    <div class="d-flex justify-content-between align-items-center my-5">
        <div>
            <h4 class="purple text-uppercase">Manage Super admins</h4>
        </div>
        <div>
            <button class="events purple form-control d-block mx-auto rounded-pill" data-bs-toggle="modal" data-bs-target="#addAdmin">
                Add new Super Admin
            </button>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="addAdmin" tabindex="-1" aria-labelledby="addAdmin" aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add new super admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.createAdmin.createSuperAdmin') }}" method="post">
                    @csrf
                    @method('post')
                    <div class="modal-body">

                        <p class="mb-2">Input the email address of the new admin</p>
                        <div class="row mb-2">
                            {{-- <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label> --}}

                            <div class="col">
                                <input id="email" type="email" class="form-control" name="email" placeholder="Email address" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p style="color: red; font-size: 14px;" class="purple text-uppercase my-3">* Please be careful who you grant this privilege to.</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    </div>
                </form>

            </div>
        </div>

    </div>

    <div class="row mb-3">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Super Admins <sup><span class="badge bg-success">{{count($users)}}</span></sup></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="ourCategories" class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th> Name</th>
                                <th> Email</th>
                                <th> Phone Number</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $key => $user)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$user->first_name}} {{$user->last_name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->phone_number}}</td>
                                <td>
                                    {{-- <button type="button" class="btn btn-primary edit-button px-2" data-toggle="modal" data-target="#edit-modal" data-file-id="{{ $category->id }}">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                    </button> --}}
                                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#cat{{ ++$key }}">
                                        <i class="fa fa-trash text-danger px-2" aria-hidden="true"></i>
                                    </button>
                                    <div class="modal fade" id="cat{{ $key }}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Remove Super Admin</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to remove this super admin - {{$user->first_name}} {{$user->last_name}}?
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('admin.createAdmin.removeSuperAdmin', $user->id) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Yes</button>
                                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>S/N</th>
                                <th>Admin Name</th>
                                <th>Admin Email</th>
                                <th> Phone Number</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
</section>

@php
use Carbon\Carbon;
@endphp


@endsection