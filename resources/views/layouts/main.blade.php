<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sludinājumi</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/display.css">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous">
    </script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <!-- baguetteBox.js image gallery - https://www.cssscript.com/simple-gallery-lightbox-with-javascript-and-css3-baguettebox-js/ -->
    <link rel="stylesheet" href="/css/baguetteBox.css">
    <!-- Leaflet JS maps - https://leafletjs.com  -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
</head>

<body>
    <div class="container">
            @guest
                @php
                    $authBtnsClass = "nav-link";
                    $logoutBtnClass = "nav-link d-none";
                @endphp
            @endguest
            @auth
                @php
                    $authBtnsClass = "nav-link d-none";
                    $logoutBtnClass = "nav-link";
                @endphp
            @endauth

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/">Sākums</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="/ads/my">Mani sludinājumi</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="/ads/create">Pievienot</a>
                        </li>
                        <li class="nav-item">
                         <a class="{{ $authBtnsClass }}" href="/register">Reģistrēties</a>
                        </li>
                        <li class="nav-item">
                          <a class="{{ $authBtnsClass }}" href="/login">Pierakstīties</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto">     
                        <li class="nav-item">
                            <a class="{{ $logoutBtnClass }}" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">Izrakstīties</a>
                        </li>
                    </ul>
                   
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
                
            </div>
            </nav>
        <main class="my-3">
            @php
                $message = session('message');
            @endphp
            @if(isset($message))
                <div class="text-center alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>{{ $message }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @yield('content')
        </main>
        <footer>
            <hr>
            <a href="https://github.com/ray2020andx/sludinajumi" target="_blank">
            github.com/ray2020andx/Sludinajumi
            </a></p>
        </footer>
    </div>
 <!-- baguetteBox.js image gallery - https://www.cssscript.com/simple-gallery-lightbox-with-javascript-and-css3-baguettebox-js/ -->
 <script src="/js/baguetteBox.js" async></script>
</body>
</html>
