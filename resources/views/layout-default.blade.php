@extends('printable::layout')
@section('title', 'asdasd')
@section('content')
    <x-orlyapps-print-page margin="51mm 20mm 45mm 22mm">
        <div class="flex text-base justify-between">
            <div style="width:85mm;height:45mm">
                <div class="text-xs underline block mb-1">
                    Nietiedt Gerüstbau GmbH • Zum Ölhafen 6 • 26384 Wilhelmshaven
                </div>
                <p class="leading-tight mb-0">

                </p>
            </div>
            <div class="text-sm text-right">
                 Orlyapps - Janzen und Strauß GbR<br>
                Azaleenweg 29<br>
                26160 Bad Zwischenahn<br><br>
                Telefon 0441 180 29 666<br>
                E-Mail: info@orlyapps.de<br><br><br>
                Mitglieds-Nr.: *9112493<br>
            </div>
        </div>
        <div class="text-base prose max-w-none">
            <h3>{{ $letter->subject }}</h3>
            {!! $letter->html !!}
        </div>

    </x-orlyapps-print-page>
@endsection
