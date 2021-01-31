@extends('layouts.main')
@section('content')

<div class="p-3 border">
        <div class="row">
            <div class="col-lg">
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
                    @auth
                    @if ($ad->owner == Auth::id()) <!-- Ja sludinājums pieder lietotājam -->
                    <div class="text-center m-5">
                            <a class="btn btn btn-warning" href="/{{ $id }}/edit">Rediģēt</a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAdDialog">
                                Dzēst
                            </button>
                    </div>
                    @else
                    <div class="text-center m-5">
                        <a class="p-3 text-decoration-none" href="#">
                            <img src="/img/icons/hand-thumbs-up-fill.svg" width="32" height="32">
                        </a>
                        <a class="p-3 text-decoration-none" href="#">
                          <img src="/img/icons/hand-thumbs-down.svg" width="32" height="32">
                        </a> 
                    </div>
                    @endif
                    @endauth
            </div>
            <div class="col-lg">
                    <div class="ads gallery">
                        <div class="row">    
                        @foreach ($photosFileNames as $photo)
                            <div class="col-md-6 my-2">
                                <div class="preview-container">
                                  <a href="{{$photosResizedPath}}{{$photo}}" data-caption="{{$ad->make}} {{$ad->model}}">
                                    <img class="preview" src="{{$photosPreviewPath}}{{$photo}}">
                                  </a>
                                </div>
                            </div>
                        @endforeach    
                        </div>
                    </div>
            </div>
           
        </div>

        @if ($addressIsValid)
        <hr>
        <div class="row justify-content-center">
            <div id="city-map">

            <script>
                    // Leaflet JS map
                    var lat = "{{ $geoLat }}";
                    var lng = "{{ $getLng }}";
                    var address = "{{ $ad->street }}";
                    var adInfo = "{{ $ad->make }} {{ $ad->model }} - {{ $ad->year }}";

                    var cityMap = L.map('city-map').setView([lat, lng], 15);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
                    }).addTo(cityMap);

                    var marker = L.marker([lat, lng]).addTo(cityMap);

                    marker.bindPopup("<b>"+ adInfo + "</b>" + "<br>" + address).openPopup();
            </script>

            </div>
        </div>
        @endif
</div>
<!-- baguetteBox.js image gallery -->
<script>
    window.onload = function() {
                        baguetteBox.run('.ads')
                    }
</script>

<!-- Dzēšanas apstriprinājuma logs -->
<div class="modal fade" id="deleteAdDialog" tabindex="-1" aria-labelledby="deleteAdDialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteAdDialogTitle">Vai dzēst sludinājumu?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      {{ $ad->make }} {{ $ad->model }} {{ $ad->year }}
      </div>
      <div class="modal-footer">
      <form action="/{{ $id }}" method="POST" class="d-flex justify-content-center">
        @method('DELETE')
        @csrf
        <button type="button" class="btn m-2 btn-secondary" data-bs-dismiss="modal">Atcelt</button>
        <button type="submit" class="btn m-2 btn-danger">Dzēst</button>
      </form> 
      </div>
    </div>
  </div>
</div>

@endsection