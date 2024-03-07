<section>
    <h5>Dear {{ ucwords(strtolower($accept->user->first_name)) }},</h5>
    <p>
        This is to notify you that your poster with the below details is now live on our website.
    </p>
    @php
        use Carbon\Carbon;
    @endphp
    <p>
        Event Title: {{ $accept->title }}<br>
        Event Date: {{ Carbon::parse($accept->event_start_date)->format('l, M d, Y') }}<br>
        Time: {{ Carbon::createFromFormat('H:i', substr($accept->event_start_time, 0, 5))->format('h:ia') }} - {{ Carbon::createFromFormat('H:i', substr($accept->event_end_time, 0, 5))->format('h:ia') }}
    </p>
    <p>
        Regards,<br>
        Jesmond Library.
    </p>
</section>