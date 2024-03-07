@extends('layouts.app')
@section('title', 'Approvals')

@section('content')
@if (session('message'))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    <strong>{{ session('message') }}</strong>
</div>
@endif

<section class="container-fluid px-3 px-md-5">
    <h4 class="purple text-uppercase mb-5">welcome!</h4>
    <h5 class="purple text-capitalize">pending approvals</h5>
    <hr>
</section>

@php
use Carbon\Carbon;
@endphp


<section class="container-fluid events p-5 my-5">
    <div class="float-start ms-2">
        {{ $events->links() }}
    </div>
    <div class="row justify-content-evenly" style="clear:both;">
        @forelse ($events as $ev)
        @if (!!$ev->poster_image)
        <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3 px-0 me-1 each_event">
            <a href="{{ route('admin.approval.show', $ev->id) }}" class="text-decoration-none text-dark">
                <img style="max-width: 100%;" class="object-fit-cover" height="550px" src="{{ asset('images/upload/'.$ev->poster_image->name) }}" alt="{{ $ev->title }}">
                <div class="text-center">
                    <p class="mt-2 px-2">
                        {{ $ev->title }}<br>
                        Type:
                        @if ($ev->poster_type->name == "recurring")
                        {{ ucwords($ev->recurrings->first()->frequency) }}
                        @else
                        Non recurring
                        @endif
                        {{-- {{ Carbon::parse($ev->event_start_date)->format('l, M d, Y') }}<br> --}}
                        {{-- {{ Carbon::createFromFormat('H:i', substr($ev->event_time, 0, 5))->format('h:ia') }} --}}
                    </p>
                </div>
            </a>
        </div>
        @endif
        @empty
        <h4 class="col">There are no pending approvals. Check back later</h4>
        @endforelse
    </div>
    <div class="float-end me-2">
        {{ $events->links() }}
    </div>

</section>

@endsection