<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'TIX Bioskop')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: #080808;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
        }

        a {
            text-decoration: none;
        }

        .section-title {
            color: #ffc629;
            font-weight: 700;
        }

        .movie-card {
            background: #171717;
            border: 1px solid #292929;
            transition: .3s;
        }

        .movie-card:hover {
            transform: translateY(-6px);
            border-color: #ffc629;
        }

        main {
            min-height: 60vh;
        }
    </style>

    @stack('styles')
</head>

<body>

    @include('inc.customer.navbar')

    @yield('hero')

    <main>
        @yield('content')
    </main>

    @include('inc.customer.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>
</html>
