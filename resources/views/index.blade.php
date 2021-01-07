@extends('layouts.main')

@section('content')
<hr>
<div class="row">
    @if ($noAdsMessage != "")
    <div class="display-2 text-center text-danger">
        {{ $noAdsMessage }}
    </div>
    @else
    @foreach($ads as $ad)
        <div class="col-4 my-2">
            <div class="p-3 border">
                <div class="row">
                    <div class="col">
                        <div>{{ $ad->make }} {{ $ad->model }}</div>
                        <div>{{ $ad->year }}</div>
                        <div>{{ $ad->price }}</div>
                        <div>{{ $ad->street }}</div>
                        <div>{{ $ad->city }}</div>
                    </div>
                    <div class="col">
                        <a href="/ads/{{ $ad->id }}">
                            <img class="img-fluid"
                                src="$ad->photosFolder">
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                       <div class="text-muted">Reitings: {{ $ad->rating }}</div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <a class="btn btn-secondary m-3" href="/ads/{{ $ad->id }}">Apskatīt</a>
                </div>
            </div>
        </div>
    @endforeach
    @endif

</div>

@endsection
