<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="/loader.css" />
    <link rel="stylesheet" media="screen"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700\|Material+Icons" />


        <script src="https://js.stripe.com/v3/"></script>
    <!-- Scripts -->
   

    @vite(['resources/js/main.js'])

    @inertiaHead
</head>

<body class="font-sans antialiased">
    <div id="loading-bg">
        <div class="loading">
            <div class="effect-1 effects"></div>
            <div class="effect-2 effects"></div>
            <div class="effect-3 effects"></div>
        </div>
    </div>
    @inertia


    <script>
        setTimeout(() => {
            document.getElementById("loading-bg").style.display = 'none';
        }, 3000);
        const loaderColor = "#FFFFFF";
        const primaryColor = "#9B9B9B";

        if (loaderColor)
            document.documentElement.style.setProperty(
                "--initial-loader-bg",
                loaderColor
            );
        if (primaryColor)
            document.documentElement.style.setProperty(
                "--initial-loader-color",
                primaryColor
            );
    </script>
</body>

</html>
