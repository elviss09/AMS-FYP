@component('mail::message')
# Hello {{ $data['name'] }}

This is your automated scheduled email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
