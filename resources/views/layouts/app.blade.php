<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <script>
            function setFavicon() {
                var link = document.querySelector('link[rel="icon"]');
                if (link) {
                    link.href = document.documentElement.classList.contains('dark')
                        ? '{{ asset('Radio-dz-blanc.png') }}'
                        : '{{ asset('Radio-dz.png') }}';
                }
            }

            (function() {
                try {
                    var isDark = localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
                    document.documentElement.classList.toggle('dark', isDark);
                    setFavicon();
                } catch(e) {}
            })();

            window.toggleDarkMode = function() {
                var html = document.documentElement;
                var isDark = !html.classList.contains('dark');
                html.classList.toggle('dark', isDark);
                try { localStorage.setItem('dark-mode', isDark ? 'true' : 'false'); } catch(e) {}
                setFavicon();
                var ids = ['sun-icon', 'sun-icon-mobile'];
                var mids = ['moon-icon', 'moon-icon-mobile'];
                for (var i = 0; i < ids.length; i++) {
                    var sun = document.getElementById(ids[i]);
                    var moon = document.getElementById(mids[i]);
                    if (sun && moon) {
                        if (isDark) { sun.classList.remove('hidden'); moon.classList.add('hidden'); }
                        else { sun.classList.add('hidden'); moon.classList.remove('hidden'); }
                    }
                }
                var t = document.getElementById('dark-mode-text');
                if (t) t.textContent = isDark ? 'Mode clair' : 'Mode sombre';
            };
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/png" href="{{ asset('Radio-dz.png') }}">
        <!-- Scripts -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>

    </body>
</html>
