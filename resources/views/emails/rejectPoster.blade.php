<section>
    <h5>Dear {{ ucwords(strtolower($delete->user->first_name)) }},</h5>
    <p>
        This is to notify you that your poster with the below details was rejected.
    </p>
    @php
        use Carbon\Carbon;
    @endphp
    <p>
        Poster Title: {{ $delete->title }}<br>
        Poster Date: {{ Carbon::parse($delete->event_start_date)->format('l, M d, Y') }}<br>
        Time: {{ Carbon::createFromFormat('H:i', substr($delete->event_start_time, 0, 5))->format('h:ia') }} - {{ Carbon::createFromFormat('H:i', substr($delete->event_end_time, 0, 5))->format('h:ia') }}<br>
        Reason: {{ $delete->reason ? $delete->reason : "Not specified" }}<br>
    </p>
    <p>
        Regards,<br>
        Jesmond Library.
    </p>
</section>