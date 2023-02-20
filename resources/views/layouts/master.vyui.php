<!DOCTYPE html>
<html lang="en">
    <head>
        <title>#[echo: env('APP_NAME')] Blade</title>
        <link href="#[echo: asset('vendor/fontawesome/all.css', 1)]" rel="stylesheet" type="text/css" />
        <link href="#[echo: asset('css/app.css', 1)]" rel="stylesheet" type="text/css" />
        <script src="#[echo: asset('js/app.js', 1)]" defer></script>
        <script src="#[echo: asset('js/test.js', 1)]" defer></script>
    </head>
    <body>
        <div class="h-screen flex justify-content-center align-items-center">
            <div>
                #[yield: body]
            </div>
        </div>
        #[yield: footer]
    </body>
</html>