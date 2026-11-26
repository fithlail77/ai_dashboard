<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>@yield('title', 'AI Dashboard')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function() {
            var savedTheme = localStorage.getItem('tabler-theme');
            var theme = savedTheme ? savedTheme : 'light';
            document.documentElement.setAttribute('data-bs-theme', theme);
            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.style.colorScheme = theme;
            if (theme === 'dark') {
                document.documentElement.classList.add('theme-dark');
            } else {
                document.documentElement.classList.remove('theme-dark');
            }
        })();
    </script>
    @stack('styles')
</head>

<body class="@yield('body-class', 'd-flex flex-column')">
    @yield('content')
    @stack('scripts')
</body>

</html>
