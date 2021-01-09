@extends('layouts.main')
@section('content')

<div class="p-3 border">
        <div class="row">
            <div class="col">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>Marka</th>
                            <td>{{ $ad->make }}</td>
                        </tr>
                        <tr>
                            <th>Modelis</th>
                            <td>{{ $ad->model }}</td>
                        </tr>
                        <tr>
                            <th>Gads</th>
                            <td>{{ $ad->year }}</td>
                        </tr>
                        <tr>
                            <th>Cena</th>
                            <td>{{ $ad->price }}</td>
                        </tr>
                        <tr>
                            <th>Adrese</th>
                            <td>{{ $ad->street }}</td>
                        </tr>
                        <tr>
                            <th>Pilsēta</th>
                            <td>{{ $ad->city }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col">
                    <div class="ads gallery">
                        <div class="row">    
                            @foreach ($photos as $photo)
                            @php
                            $photoPath = "/$photosLocation/$photo";
                            @endphp
                            <div class="col-4 m-2">
                            <a href="{{ $photoPath }}" data-caption="{{$ad->make}} {{$ad->model}}"><img class="preview" src="{{ $photoPath }}"></a>
                            </div>
                            @endforeach
                        </div>
                    </div>
            </div>
        @auth
        @if ($ad->owner == Auth::id()) <!-- Ja sludinājums pieder lietotājam -->
        <div class="text-center m-3">
                <a class="btn btn-lg btn-warning" href="/ads/{{ $id }}/edit">Rediģēt</a>
                <a class="btn btn-lg btn-danger" href="/ads/{{ $id }}/delete">Dzēst</a>
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
    <script>
        window.onload = function() {
            baguetteBox.run('.ads')
        }
    </script>
@endsection