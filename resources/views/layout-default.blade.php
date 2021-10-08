@extends('printable::layout')
@section('title', $letter->subject)
@section('content')
    <x-orlyapps-print-page margin="51mm 20mm 45mm 22mm">
        <div class="flex text-base justify-between">
            <div style="width:85mm;height:45mm">
                <div class="text-xs underline block mb-1">
                    {{ $letter->sender_address }}
                </div>
                <p class="leading-tight mb-0">
                    {!! nl2br($letter->receiver_address) !!}
                </p>
            </div>
            <div class="text-sm text-right">
               {!! nl2br($letter->info) !!}
            </div>
        </div>
        <div class="text-base prose max-w-none">
            <h3>{{ $letter->subject }}</h3>
            {!! $letter->html !!}
        </div>
    </x-orlyapps-print-page>
@endsection
