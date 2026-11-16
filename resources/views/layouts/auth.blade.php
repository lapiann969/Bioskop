<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Autentikasi') - TIX Bioskop</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
    * {
        font-family: 'Nunito', sans-serif;
    }

    body {
        min-height: 100vh;
        background:
            radial-gradient(circle at 15% 20%, rgba(250, 204, 21, .12), transparent 35%),
            radial-gradient(circle at 85% 80%, rgba(234, 179, 8, .10), transparent 35%),
            #0b0c10;
        color: #fff;
    }

    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 15px;
    }

    .auth-card {
        width: 100%;
        max-width: 460px;
        background: #15161c;
        border: 1px solid #292a33;
        border-radius: 20px;
        padding: 38px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .35);
    }

    .auth-logo {
    color: #facc15;
    font-size: 25px;
    font-weight: 900;
    letter-spacing: 1px;
    text-decoration: none;
}

.auth-logo span {
    color: #ffffff;
}

.auth-logo:hover {
    color: #fde047;
}

    .auth-subtitle {
        color: #a1a1aa;
        font-size: 14px;
    }

    .auth-label {
        color: #e4e4e7;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .auth-input {
        background: #202127;
        border: 1px solid #383943;
        color: #fff;
        border-radius: 10px;
        padding: 12px 14px;
    }

    .auth-input:focus {
        background: #202127;
        color: #fff;
        border-color: #facc15;
        box-shadow: 0 0 0 3px rgba(250, 204, 21, .15);
    }

    .auth-input::placeholder {
        color: #71717a;
    }

    .auth-input.is-invalid {
        border-color: #dc3545;
    }

    .auth-input:-webkit-autofill,
    .auth-input:-webkit-autofill:hover,
    .auth-input:-webkit-autofill:focus {
        -webkit-text-fill-color: #fff;
        box-shadow: 0 0 0 1000px #202127 inset;
    }

    .auth-button {
        background: #facc15;
        color: #111;
        border: none;
        border-radius: 10px;
        padding: 13px;
        font-weight: 800;
        letter-spacing: .5px;
        transition: .2s;
    }

    .auth-button:hover {
        background: #eab308;
        color: #111;
        transform: translateY(-1px);
    }

    .auth-link {
        color: #facc15;
        text-decoration: none;
        font-weight: 700;
    }

    .auth-link:hover {
        color: #fde047;
    }

    .auth-muted {
        color: #a1a1aa;
        font-size: 14px;
    }

    .auth-divider {
        border-color: #34343d;
        margin: 25px 0;
    }

    .password-toggle {
        background: #202127;
        color: #a1a1aa;
        border: 1px solid #383943;
        border-left: none;
    }

    .password-toggle:hover {
        color: #facc15;
        background: #292a33;
    }

    .form-check-input {
        background-color: #202127;
        border-color: #555;
    }

    .form-check-input:checked {
        background-color: #facc15;
        border-color: #facc15;
    }

    .form-check-label {
        color: #a1a1aa;
        font-size: 14px;
    }

    @media (max-width: 480px) {
        .auth-card {
            padding: 28px 22px;
        }
    }
</style>
</head>

<body>

    <main class="auth-wrapper">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
