@component('mail::message')

## {{ $letter->subject }}
{!! $letter->html !!}

@endcomponent
