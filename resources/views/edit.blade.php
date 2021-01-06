@extends('layouts.main')

@section('content')
<form action="/ads/{{ $id }}" method="POST">
    @method('PUT')
    @csrf
    @foreach($ad as $data)
        <input class="form-control" type="text" name="make" placeholder="Marka" value="{{ $data->make }}">
        <input class="form-control" type="text" name="model" placeholder="Modelis" value="{{ $data->model }}">
        <input class="form-control" type="text" name="year" placeholder="Gads" value="{{ $data->year }}">
        <input class="form-control" type="text" name="mileage" placeholder="Nobraukums" value="{{ $data->mileage }}">
        <input class="form-control" type="text" name="price" placeholder="Cena" value="{{ $data->price }}">
        <input class="form-control" type="text" name="street" placeholder="Adrese" value="{{ $data->street }}">
        <input class="form-control" type="text" name="city" placeholder="Pilsēta" value="{{ $data->city }}">
    @endforeach
    <button class="btn btn-success btn-xl" type="submit">Saglabāt</button>

</form>
@endsection
