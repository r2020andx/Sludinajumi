@extends('layouts.main')
@section('content')
<div class="row justify-content-center">
    <div class="col-sm-10 col-md-6 col-lg-4 border">
        <form action="/{{ $id }}" method="POST">
            @method('PUT')
            @csrf
                <div class="m-2">
                    <input class="form-control" type="text" name="make" placeholder="Marka" value="{{ $ad->make }}">
                </div>
                <div class="m-2">
                    <input class="form-control" type="text" name="model" placeholder="Modelis" value="{{ $ad->model }}">
                </div>
                <div class="m-2">
                    <input class="form-control" type="text" name="year" placeholder="Gads" value="{{ $ad->year }}">
                </div>
                <div class="m-2">
                    <input class="form-control" type="text" name="mileage" placeholder="Nobraukums" value="{{ $ad->mileage }}">
                </div>
                <div class="m-2">
                    <input class="form-control" type="text" name="price" placeholder="Cena" value="{{ $ad->price }}">
                </div>
                <div class="m-2">
                    <input class="form-control" type="text" name="street" placeholder="Adrese" value="{{ $ad->street }}">
                </div>
                <div class="m-2">
                     <input class="form-control" type="text" name="city" placeholder="Pilsēta" value="{{ $ad->city }}">
                <div class="m-2 text-center">
                    <button class="btn btn-success" type="submit">Saglabāt</button>
                </div>
                <div class="m-2">
                    <div class="row">
                        @foreach ($photosFileNames as $photo)
                            <div class="col-3">
                                <img class="" src="{{ $photosPreviewPath }}{{ $photo }}">            
                            </div>
                        @endforeach
                        </div>
                </div>
        </form>
    </div>
</div>
@endsection
