@extends('layouts.main')

@section('content')
<form action="/ads" method="post">
  @csrf
  <input class="form-control" type="text" name="make" placeholder="Marka">
  <input class="form-control" type="text" name="model" placeholder="Modelis">
  <input class="form-control" type="text" name="year" placeholder="Gads">
  <input class="form-control" type="text" name="mileage" placeholder="Nobraukums">
  <input class="form-control" type="text" name="price" placeholder="Cena">
  <input class="form-control" type="text" name="street" placeholder="Adrese">
  <input class="form-control" type="text" name="city" placeholder="Pilsēta">
  
  <button class="btn btn-primary btn-xl" type="submit">Nosūtīt</button>

</form>
@endsection