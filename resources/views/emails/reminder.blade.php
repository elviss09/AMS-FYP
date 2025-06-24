@component('mail::message')
# Appointment Reminder

Hello {{ $data['name'] }},

{{ $data['message'] }}

Thanks,  
{{ config('app.name') }}
@endcomponent
