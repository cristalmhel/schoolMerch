<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="background min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="circle red"></div>
            <div class="circle blue"></div>
            <div class="circle green"></div>
            <div class="circle orange"></div>
            <div>
                <a href="/">
                    <img src="image/hellya.JPG" alt="Hero Logo" 
                    class="hero-logo w-32 h-32 rounded-full object-cover">
                </a>
            </div>

            <div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
