@component('mail::message')

{{ $letter->receiver->getSalutation() }},

Bitte beachten Sie anbei unser Anschreiben.

<x-orlyapps-signature :user="$letter->user" />

@endcomponent
