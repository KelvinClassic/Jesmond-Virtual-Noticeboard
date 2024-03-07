<section>
    <h5>Dear {{ ucwords(strtolower($event->first_name)) }},</h5>
    <p>
        You have been removed as a Super Admin to the Jesmond library website.
    </p>

    @if ($event->is_admin)
    <p>You will still be able to review and accept posters and poster categories</p>
    <p>However, You will be unable to add other admins and super admins</p>
    @endif

    <p>
        Regards,<br>
        Jesmond Library.
    </p>
</section>