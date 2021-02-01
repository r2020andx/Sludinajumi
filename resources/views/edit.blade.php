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
                            <div class="col-6">
                                <a onclick='setCurrentPhoto("/{{ $photosResizedPath }}{{$photo}}")' data-bs-toggle="modal" href="#photoEditDialog">
                                    <img class="m-1" width="200px" src="/{{ $photosPreviewPath }}{{ $photo }}">
                                </a>           
                            </div>
                        @endforeach
                    </div>
                </div>
        </form>
    </div>
</div>

<!-- Attēlu rediģēšanas logs -->
<script>
    function setCurrentPhoto(photo) {  // Aizsūta uz logu (zemāk) info par tekošo attēla failu
        currentPhoto = photo;
        document.getElementById('modalPhoto').src = photo;
    }
    function enableSubmitButton() {
        if (document.getElementById('newPhotoSelctor') != '') {
            document.getElementById('submitNewPhoto').disabled = false;
        }
    }
    function clearPhotoSelector() {
            document.getElementById('newPhotoSelector').value = '';
            document.getElementById('submitNewPhoto').disabled = true;
    }

</script>
<div class="modal fade" id="photoEditDialog" tabindex="-1" aria-labelledby="photoEditDialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="photoEditDialogTitle">Fotogrāfija</h5>
        <button onclick='clearPhotoSelector()' type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img class="img-fluid" id="modalPhoto">
      </div>
      <div class="modal-footer">
        <div class="row m-auto">
            <div class="col-10">
                <form class="d-flex" action="/{{ $id }}" method="POST">
                @method('PUT')
                @csrf
                    <input oninput='enableSubmitButton()' id="newPhotoSelector" class="form-control" type="file" name="newPhoto">
                    <button id="submitNewPhoto" type="submit" class="btn btn-warning mx-1" disabled>Mainīt</button>
                </form>
            </div>
            <div class="col-2">
                <button class="btn btn-danger">Dzēst</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
