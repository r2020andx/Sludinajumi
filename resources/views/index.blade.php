@php
use \app\Http\Controllers\AdController;
@endphp
@extends('layouts.main')

@section('content')
<div class="row">
    @if ($noAdsMessage != "")
    <div class="display-2 text-center text-danger">
        {{ $noAdsMessage }}
    </div>
    @else
    @foreach($ads as $ad)
        <div class="col-lg-6 col-xl-4 my-2">
            <div class="p-3 border">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-sm">
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
                            <th>Pilsēta</th>
                            <td>{{ $ad->city }}</td>
                        </tr>
                        <tr>
                            <th>Adrese</th>
                            <td>{{ $ad->street }}</td>
                        </tr>
                         </table>
                    </div>
                    <div class="row">
                    <div class="col-12">
                    @php
                        $firstPhoto = AdController::getPhotosFileNames($ad->id, TRUE);
                    @endphp
                        <div class="preview-container">
                            <a href="/{{ $ad->id }}">
                                <img class="preview" src="{{ $photosBasePathPublic }}{{ $ad->photosFolder }}{{ $photosPreviewPath }}{{ $firstPhoto }}">
                            </a>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                       <div class="text-center text-muted m-2">Skatījumi: {{ $ad->views }}</div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <a class="btn w-50 btn-secondary m-1" href="/{{ $ad->id }}">Apskatīt</a>
                </div>
            </div>
        </div>
    @endforeach
    @endif

</div>

@endsection
