@extends('layouts.main')
@section('content')

@foreach($ad as $ad)

    <div class="p-3 border">
        <div class="row">
            <div class="col-6">
                <div>{{ $ad->make }}</div>
                <div>{{ $ad->model }}</div>
                <div>{{ $ad->year }}</div>
                <div>{{ $ad->price }}</div>
                <div>{{ $ad->street }}</div>
                <div>{{ $ad->city }}</div>
            </div>
            <div class="col-6">
                <div>
            @php
            // Šo vajag pārcelt uz AdController
            $photosLocation = $ad->photos;
            $photos = array_diff(scandir('./'.$photosLocation), array('..', '.')); // Noņem Linux tipa punktus
            @endphp
            @foreach ($photos as $photo)
                <ul>
                    <li>
                        <img width="200px" src="/{{ $photosLocation }}/{{ $photo }}">
                    </li>
                </ul>
            @endforeach
                </div>
            </div>
        </div>
        @auth
        @if ($ad->owner == Auth::id()) <!-- Ja sludinājums pieder lietotājam -->
        <div class="row">
            <div class="col">
                <a class="btn btn-warning" href="/ads/{{ $id }}/edit">Rediģēt</a>
                <a class="btn btn-danger" href="/ads/{{ $id }}/delete">Dzēst</a>
            </div>
        </div>
        @endif
        @endauth
        <hr>
        <div class="row justify-content-center">
            <div class="mapouter">
                <div class="gmap_canvas"><iframe width="1000" height="500" id="gmap_canvas"
                        src="https://maps.google.com/maps?q=R%C4%ABga&t=&z=13&ie=UTF8&iwloc=&output=embed"
                        frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a
                        href="https://123movies-to.org">widows 123movies</a></div>
                <style>
                    .mapouter {
                        position: relative;
                        text-align: right;
                        height: 500px;
                        width: 1000px;
                    }

                    .gmap_canvas {
                        overflow: hidden;
                        background: none !important;
                        height: 500px;
                        width: 1000px;
                    }

                </style>
            </div>
        </div>
    </div>

@endforeach

@endsection
