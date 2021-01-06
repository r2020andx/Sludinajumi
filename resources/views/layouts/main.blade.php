<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sludinājumi</title>
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

</head>

<body>
    <div class="container">
        <nav class="navbar d-flex my-3">

            @guest
                @php
                    $authBtnsClass = "btn btn-outline-primary";
                    $logoutBtnClass = "btn invisible";
                @endphp
            @endguest
            @auth
                @php
                    $authBtnsClass = "btn invisible";
                    $logoutBtnClass = "btn btn-outline-danger";
                @endphp
            @endauth

            <a class="btn btn-outline-primary" href="/">Sākums</a>
            <a class="btn btn-outline-primary" href="/ads/my">Mani sludinājumi</a>
            <a class="btn btn-outline-success" href="/ads/create">Pievienot sludinājumu</a>
            <a class="{{ $authBtnsClass }}" href="/register">Reģistrēties</a>
            <a class="{{ $authBtnsClass }}" href="/login">Pierakstīties</a>
            <a class="{{ $logoutBtnClass }}" href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                Izrakstīties
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </nav>
        <main>
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
            <a href="https://github.com/ray2020andx/auto_slud" target="_blank">
            github.com/ray2020andx/auto_slud
            </a></p>
        </footer>
    </div>

</body>

</html>
