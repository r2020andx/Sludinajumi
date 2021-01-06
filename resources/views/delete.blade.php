@extends('layouts.main')
@section('content')
<form action="/ads/{{ $id }}" method="POST">
    @method('DELETE')
    @csrf
    <div class="row justify-content-center">
        <button class="btn btn-lg btn-danger m-2" type="submit">Dzēst</button>
        <a class="btn btn-lg btn-warning m-2" href="/ads/{{ $id }}">Atcelt</a>
    </div>


</form>
@endsection
