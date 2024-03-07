@extends('layouts.app')

@section('title', 'My Posted Events')

@section('css')
{{-- adminlte css --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css" integrity="sha512-IuO+tczf4J43RzbCMEFggCWW5JuX78IrCJRFFBoQEXNvGI6gkUw4OjuwMidiS4Lm9Q2lILzpJwZuMWuSEeT9UQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<section class="container-fluid p-5">
    <div class="row justify-content-md-center">
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="bg-danger text-white p-3">
                <h2 class="text-end">{{ count($pendingEvents) }}</h2>
                <h4>Pending Events</h4>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="bg-success text-white p-3">
                <h2 class="text-end">{{count($currentEvents)}}</h2>
                <h4>Current Events</h4>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="bg-warning text-white p-3">
                <h2 class="text-end">{{count($pastEvents)}}</h2>
                <h4>Past Events</h4>
            </div>
        </div>
    </div>
</section>

<section class="container-fluid">
    @php
    use Carbon\Carbon;
    @endphp

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
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pendingEvents as $key => $ev)
                            @php
                            $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $ev->created_at);
                            @endphp
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$ev->title}}</td>
                                <td>{{ $ev->category->name }}</td>
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
                            </tr>
                            @endforeach
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
                    <h3 class="card-title">Current Events <sup><span class="badge bg-success">{{count($currentEvents)}}</span></sup></h3>
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
                    <table id="currentEvents" class="table table-bordered table-striped text-center">
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
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($currentEvents as $key => $ev)
                            @php
                            $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $ev->created_at);
                            $approved_at = Carbon::createFromFormat('Y-m-d H:i:s', $ev->approved_date);
                            @endphp
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$ev->title}}</td>
                                <td>{{ $ev->category->name }}</td>
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
                            </tr>
                            @endforeach
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
                    <h3 class="card-title">Past Events <sup><span class="badge bg-warning">{{count($pastEvents)}}</span></sup></h3>
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
                    <table id="pastEvents" class="table table-bordered table-striped text-center">
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
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pastEvents as $key => $ev)
                            @php
                            $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $ev->created_at);
                            $approved_at = Carbon::createFromFormat('Y-m-d H:i:s', $ev->approved_date);
                            @endphp
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$ev->title}}</td>
                                <td>{{ $ev->category->name }}</td>
                                <td>
                                    @php
                                    $eventDate = Carbon::createFromFormat('Y-m-d', $ev->event_end_date);
                                    @endphp
                                    {{ $eventDate->format('M d, Y') }}
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
                            </tr>
                            @endforeach
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

@endsection

@section('js')
<!-- DataTables  & Plugins -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(function() {
        $("#currentEvents").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["excel", "print"]
        }).buttons().container().appendTo('#currentEvents_wrapper .col-md-6:eq(0)');
    });

    $(function() {
        $("#pastEvents").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["excel", "print"]
        }).buttons().container().appendTo('#pastEvents_wrapper .col-md-6:eq(0)');
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

@endsection