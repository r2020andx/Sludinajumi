@extends('layouts.main')
@section('content')
<form action="/{{ $id }}" method="POST" class="d-flex justify-content-center">
    @method('DELETE')
    @csrf
    <div class="m-3 p-3 border">
        <p class="text-danger text-center">Vai dzēst?</p>
        <p class="text-center">{{ $ad->make }} {{ $ad->model }} {{ $ad->year }}</p>
        <p class="text-center">
             <button class="btn btn-lg btn-danger m-2" type="submit">Dzēst</button>
            <a class="btn btn-lg btn-warning m-2" href="/{{ $id }}">Atcelt</a>
        </p>
    </div>
</form>
@endsection
