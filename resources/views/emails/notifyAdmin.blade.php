<section>
    <h5>Dear Admin,</h5>
    <p>
        This is to notify you that you have a pending poster to review.
    </p>
    @php
        use Carbon\Carbon;
    @endphp
    <p>
        Poster Title: {{ $event->title }}<br>
        Poster Date: {{ Carbon::parse($event->event_start_date)->format('l, M d, Y') }}<br>
        Time: {{ Carbon::createFromFormat('H:i', substr($event->event_start_time, 0, 5))->format('h:ia') }} - {{ Carbon::createFromFormat('H:i', substr($event->event_end_time, 0, 5))->format('h:ia') }}
        
    </p>
    <button style="background-color: green; border: none;">
        <a style="text-decoration: none; color: white; display: inline-block; padding: 7px;"
        href="{{ route('admin.approval.show', $event->id) }}">Review</a>
    </button>
    <p>
        Regards,<br>
        Jesmond Library.
    </p>
</section>