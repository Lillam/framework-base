<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ env('APP_NAME') }} Blade</title>
    <link href="{{ asset('vendor/fontawesome/all.css', 1) }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/app.css', 1) }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('js/app.js', 1) }}" defer></script>
    <script src="{{ asset('js/test.js', 1) }}" defer></script>
</head>
    <body>
        <div class="h-screen flex justify-content-center align-items-center">
            @yield('body')
        </div>
    </body>
</html>
