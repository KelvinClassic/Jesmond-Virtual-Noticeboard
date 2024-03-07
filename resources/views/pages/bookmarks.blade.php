@extends('layouts.app')

@section('title', 'Bookmarks')

@section('css')

@php
use Carbon\Carbon;
@endphp


@section('content')
<section class="container-fluid p-5 my-5">
    @if (session('message'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <strong>{{ session('message') }}</strong>
    </div>
    @endif

    <h4 class="text-uppercase purple mb-5"> Bookmarked Events</h4>
    <div class="row justify-content-evenly" style="clear:both;">
        @include('include.alert')
        @forelse ($bookmarks as $bookmark)
        <div class="col-10 col-sm-4 col-md-3 col-lg-2 mb-3 px-0 me-1 each_event">
            @if ($bookmark->event->poster_image)
            <img class="object-fit-cover" width="100%" height="300px" src="{{ asset('images/upload/'.$bookmark->event->poster_image->name) }}" alt="{{ $bookmark->event->title }}">
            @endif
            <div class="text-center">
                <p class="mt-2 px-2">
                <h6 class="purple">{{ $bookmark->event->title }}</h6>
                {{ Carbon::parse($bookmark->event->event_start_date)->format('l, M d, Y') }}<br>
                {{ Carbon::createFromFormat('H:i', substr($bookmark->event->event_start_time, 0, 5))->format('h:ia') }}
                -
                {{ Carbon::createFromFormat('H:i', substr($bookmark->event->event_end_time, 0, 5))->format('h:ia') }}
                </p>
            </div>
            <div class="d-flex justify-content-between align-items-center p-2 gap-2">
                @if($bookmark->event->poster_type->name == 'non_recurring')
                <a href="{{ route('pages.live.showAll', $bookmark->event->id) }}" class="btn btn-primary btn-sm">
                    View Event
                </a>
                @else
                @foreach ($bookmark->event->recurrings as $rec)
                <a href="{{ route('pages.recurring.showAll', $rec->id) }}" class="btn btn-primary btn-sm">
                    View Event
                </a>
                @endforeach
                @endif

                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#{{$bookmark->id}}">
                    Remove Bookmark
                </button>

                <div class="modal fade" tabindex="-1" id="{{$bookmark->id}}" tabindex="-1" aria-labelledby="{{$bookmark->id}}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Remove Event</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('pages.bookmarks.remove',['bookmarkId'=>$bookmark->id]) }}" method="post">
                                @csrf
                                @method('post')
                                <input type="hidden" id="id" name="id" value="{{$bookmark->id}}">
                                <div class="modal-body">
                                    <p>Are you sure you want to remove this event from your bookmarks?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" class="btn btn-danger">Yes, Remove Event
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @empty
        <h4 class="col">You do not have any bookmarked events. </h4>
        @endforelse
    </div>



    {{ $bookmarks->links() }}

</section>

@endsection

<script>
    $(document).on('click', '.delete', function() {
        let id = $(this).attr('data-id');
        $('#id').val(id);
    });
    var removeBookmarkId = ""

    const trackEventId = (val) => {
        removeBookmarkId = val;
    }

    const removeEventFromBookmark = () => {
        // call api to remove id

        console.log(removeBookmarkId)
    }
</script>