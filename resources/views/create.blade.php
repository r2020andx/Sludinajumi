@extends('layouts.main')

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-10 col-md-6 col-lg-4 border">
        <form action="/" method="post" enctype="multipart/form-data">
            @csrf
            <div class="m-2">
                <input class="form-control" type="text" name="make" placeholder="Marka">
            </div>
            <div class="m-2">
                <input class="form-control" type="text" name="model" placeholder="Modelis">
            </div>
            <div class="m-2">
                <input class="form-control" type="text" name="year" placeholder="Gads">
            </div>
            <div class="m-2">
                <input class="form-control" type="text" name="mileage" placeholder="Nobraukums">
            </div>
            <div class="m-2">
                <input class="form-control" type="text" name="price" placeholder="Cena">
            </div>
            <div class="m-2">
                <input class="form-control" type="text" name="street" placeholder="Adrese">
            </div>
            <div class="m-2">
                <input class="form-control" type="text" name="city" placeholder="Pilsēta">
            </div>
            <div class="m-2 text-center">
                <label for="photos-upload" class="form-label">Attēli</label>
                <input class="form-control" type="file" id="photos-upload" multiple name="photos[]">
            </div>
            <div class="m-2 text-center">
                <button class="btn btn-primary" type="submit">Nosūtīt</button>
            </div>
          </form>
    </div>
</div>
@endsection
