@extends('layouts.app')

@section('title', 'Dashboard')

@section('css')
{{-- adminlte css --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css" integrity="sha512-IuO+tczf4J43RzbCMEFggCWW5JuX78IrCJRFFBoQEXNvGI6gkUw4OjuwMidiS4Lm9Q2lILzpJwZuMWuSEeT9UQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
@if (session('message'))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    <strong>{{ session('message') }}</strong>
</div>
@endif

<section class="container-fluid p-5">
    @php
    use Carbon\Carbon;
    @endphp

    <div class="row justify-content-md-center">
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="bg-danger text-white p-3">
                <h2 class="text-end">{{ count($pendingEvents) }}</h2>
                <h4>Pending Events</h4>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="bg-success text-white p-3">
                <h2 class="text-end">{{count($approvedEvents)}}</h2>
                <h4>Approved Events</h4>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="bg-warning text-white p-3">
                <h2 class="text-end">{{count($rejectedEvents)}}</h2>
                <h4>Rejected Events</h4>
            </div>
        </div>
    </div>
</section>

<section class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pending Events <sup><span class="badge bg-danger">{{count($pendingEvents)}}</span></sup></h3>
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
                    <table id="pendingEvents" class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Event Title</th>
                                <th>Event Category</th>
                                <th>Event Date</th>
                                <th>Event Time</th>
                                <th>Date Posted</th>
                                <th>Type</th>
                                <th>Picture</th>
                                @if (Auth::user()->is_admin)
                                <th>Action</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @if ($pendingEvents)
                            @foreach ($pendingEvents as $key => $ev)
                            @php
                            $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $ev->created_at);
                            @endphp
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$ev->title}}</td>
                                <td>
                                    @if (Auth::user()->is_admin)
                                    <form class="row" action="{{ route('admin.changeCategory', $ev->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="col">
                                            <select name="category" class="p-2 form-control @error('category') is-invalid @enderror">
                                                <option value="">Select category</option>
                                                @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('category') ? old('category') : ($cat->id == $ev->category_id ? "selected" : "" )}}>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="col btn btn-success p-2">Change</button>
                                    </form>
                                    @else
                                    {{ $ev->category->name }}
                                    @endif
                                </td>
                                <td>
                                    @if ($ev->poster_type->name == 'recurring')
                                    @php
                                    $nextDate = null;
                                    foreach ($ev->recurrings as $value) {
                                    if($value->event_date >= date('Y-m-d')){
                                    $nextDate = $value->event_date;
                                    break;
                                    }
                                    }
                                    @endphp
                                    {{ (new DateTime($nextDate))->format('M d, Y') }}
                                    @else
                                    @php
                                    $eventDate = Carbon::createFromFormat('Y-m-d', $ev->event_start_date);
                                    @endphp
                                    {{ $eventDate->format('M d, Y') }}
                                    @endif
                                </td>
                                <td>
                                    {{ Carbon::createFromFormat('H:i', substr($ev->event_start_time, 0, 5))->format('h:ia') }} -
                                    {{ Carbon::createFromFormat('H:i', substr($ev->event_end_time, 0, 5))->format('h:ia') }}
                                </td>
                                <td>{{ $created_at->format('M d, Y h:ia') }}</td>
                                <td>
                                    @if ($ev->poster_type->name == "recurring")
                                    Recurring / {{ ucwords($ev->recurrings[0]['frequency']) }}
                                    @else
                                    Non - recurring
                                    @endif
                                </td>
                                <td>
                                    @if ($ev->poster_image)
                                    <div style="height: 50px; width: 50px;">
                                        <img class="object-fit-cover" width="100%" height="100%" src="{{ asset('images/upload/' . $ev->poster_image->name) }}" alt="{{ $ev->title }}">
                                    </div>
                                    @endif
                                </td>
                                @if (Auth::user()->is_admin)
                                <td>
                                    <a class="text-decoration-none align-middle" href="{{ route('admin.approval.show', $ev->id) }}">
                                        <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                    </a>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>S/N</th>
                                <th>Event Title</th>
                                <th>Event Category</th>
                                <th>Event Date</th>
                                <th>Event Time</th>
                                <th>Date Posted</th>
                                <th>Type</th>
                                <th>Picture</th>
                                @if (Auth::user()->is_admin)
                                <th>Action</th>
                                @endif
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>

    <div class="row mb-3">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Approved Events <sup><span class="badge bg-success">{{count($approvedEvents)}}</span></sup></h3>
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
                    <table id="approvedEvents" class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Event Title</th>
                                <th>Event Category</th>
                                <th>Event Date</th>
                                <th>Event Time</th>
                                <th>Date Posted</th>
                                <th>Date Approved</th>
                                <th>Type</th>
                                <th>Picture</th>
                                @if (Auth::user()->is_admin)
                                <th>Action</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @if ($approvedEvents)
                            @foreach ($approvedEvents as $key => $ev)
                            @php
                            $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $ev->created_at);
                            $approved_at = Carbon::createFromFormat('Y-m-d H:i:s', $ev->approved_date);
                            @endphp
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$ev->title}}</td>
                                <td>
                                    @if (Auth::user()->is_admin)
                                    <form class="row" action="{{ route('admin.changeCategory', $ev->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="col">
                                            <select name="category" class="p-2 form-control @error('category') is-invalid @enderror">
                                                @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('category') ? old('category') : ($cat->id == $ev->category_id ? "selected" : "" )}}>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="col btn btn-success p-2">Change</button>
                                    </form>
                                    @else
                                    {{ $ev->category->name }}
                                    @endif
                                </td>
                                <td>
                                    @if ($ev->poster_type->name == 'recurring')
                                    @php
                                    $nextDate = null;
                                    foreach ($ev->recurrings as $value) {
                                    if($value->event_date >= date('Y-m-d')){
                                    $nextDate = $value->event_date;
                                    break;
                                    }
                                    }
                                    @endphp
                                    {{ (new DateTime($nextDate))->format('M d, Y') }}
                                    @else
                                    @php
                                    $eventDate = Carbon::createFromFormat('Y-m-d', $ev->event_start_date);
                                    @endphp
                                    {{ $eventDate->format('M d, Y') }}
                                    @endif
                                </td>
                                <td>
                                    {{ Carbon::createFromFormat('H:i', substr($ev->event_start_time, 0, 5))->format('h:ia') }} -
                                    {{ Carbon::createFromFormat('H:i', substr($ev->event_end_time, 0, 5))->format('h:ia') }}
                                </td>
                                <td>{{ $created_at->format('M d, Y h:ia') }}</td>
                                <td>{{ $approved_at->format('M d, Y h:ia') }}</td>
                                <td>
                                    @if ($ev->poster_type->name == "recurring")
                                    Recurring / {{ ucwords($ev->recurrings[0]['frequency']) }}
                                    @else
                                    Non - recurring
                                    @endif
                                </td>
                                <td>
                                    @if ($ev->poster_image)
                                    <div style="height: 50px; width: 50px;">
                                        <img class="object-fit-cover" width="100%" height="100%" src="{{ asset('images/upload/' . $ev->poster_image->name) }}" alt="{{ $ev->title }}">
                                    </div>
                                    @endif

                                </td>
                                @if (Auth::user()->is_admin)
                                <td>
                                    <button class="btn btn-danger delete-event" url="{{ route('poster.delete', $ev->id) }}" data="{{ json_encode($ev) }}" data-bs-toggle="modal" data-bs-target="#delete_ButtonName">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>S/N</th>
                                <th>Event Title</th>
                                <th>Event Category</th>
                                <th>Event Date</th>
                                <th>Event Time</th>
                                <th>Date Posted</th>
                                <th>Date Approved</th>
                                <th>Type</th>
                                <th>Picture</th>
                                @if (Auth::user()->is_admin)
                                <th>Action</th>
                                @endif
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>

    <div class="row mb-3">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rejected Events <sup><span class="badge bg-warning">{{count($rejectedEvents)}}</span></sup></h3>
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
                    <table id="rejectedEvents" class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Event Title</th>
                                <th>Type</th>
                                <th>Date Posted</th>
                                <th>Date Rejected</th>
                                <th>Reason</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if ($rejectedEvents)
                            @foreach ($rejectedEvents as $key => $ev)
                            @php
                            $datePosted = Carbon::createFromFormat('Y-m-d', $ev->date_posted);
                            $dateRejected = Carbon::createFromFormat('Y-m-d H:i:s', $ev->created_at);
                            @endphp
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$ev->title}}</td>
                                <td>{{ $ev->type }}</td>
                                {{-- <td>{{ $ev->date_posted }}</td>
                                <td>{{ $ev->created_at }}</td> --}}
                                <td>{{ $datePosted->format('M d, Y h:ia') }}</td>
                                <td>{{ $dateRejected->format('M d, Y h:ia') }}</td>
                                <td>{{ $ev->reason ? $ev->reason : "Not specified" }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>S/N</th>
                                <th>Event Title</th>
                                <th>Type</th>
                                <th>Date Posted</th>
                                <th>Date Rejected</th>
                                <th>Reason</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>

</section>

<!-- Static Backdrop Modal -->
<div class="modal fade" id="delete_ButtonName" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="staticBackdropLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h2 class="text-center">Are you sure?</h2>
                <p class="text-center">Do you want to delete this event <b class="deletename"></b></p>
            </div>
            <form action="" method="post" id="deleteform">
                @method('DELETE')
                @csrf
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-info" data-bs-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-danger">Yes, Proceed</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('js')
<!-- DataTables  & Plugins -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(function() {
        $("#approvedEvents").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["excel", "print"]
        }).buttons().container().appendTo('#approvedEvents_wrapper .col-md-6:eq(0)');
    });

    $(function() {
        $("#rejectedEvents").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["excel", "print"]
        }).buttons().container().appendTo('#rejectedEvents_wrapper .col-md-6:eq(0)');
    });
    $(function() {
        $("#pendingEvents").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["excel", "print"]
        }).buttons().container().appendTo('#pendingEvents_wrapper .col-md-6:eq(0)');
    });
</script>

<script>
    $(document).ready(function() {
        $(document).on('click', '.delete-event', function() {
            var datas = $(this).attr('data');
            var url = $(this).attr('url')
            $('#deleteform').attr('action', url);
            var data = JSON.parse(datas);
            $('.deletename').text(data.title);
        })
    })
</script>

@endsection