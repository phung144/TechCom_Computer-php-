<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title', 'Authentication')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset('client/assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        body {
            background: linear-gradient(to right, #1e3c72, #2a5298); /* Gradient with computer-themed colors */
            background-image: url('{{ asset('client/assets/images/computer-bg.jpg') }}'); /* Add a computer-related background image */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .card {
            background: rgba(255, 255, 255, 0.9); /* Slightly transparent white background for the card */
            border-radius: 10px;
        }
        .card-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
    </style>
</head>
<body>
    <main id="content" role="main" class="w-100">
        <div class="container">
            @yield('main')
        </div>
    </main>
</body>
</html>
