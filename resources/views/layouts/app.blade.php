<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion Commandes')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header>
        <h1>Gestion des Commandes</h1>
    </header>

    <div class="container">
        @yield('content')
    </div>

    <footer>
        <p>© 2024 - Mon Application Laravel</p>
    </footer>
</body>
</html>
