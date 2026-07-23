<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <script>
            (function() {
                try {
                    var isDark = localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
                    if (isDark) { document.documentElement.classList.add('dark'); }
                    setFavicon();
                } catch(e) {}
            })();

            function setFavicon() {
                var link = document.querySelector('link[rel="icon"]');
                if (link) {
                    link.href = document.documentElement.classList.contains('dark')
                        ? '{{ asset('Radio-dz-blanc.png') }}'
                        : '{{ asset('Radio-dz.png') }}';
                }
            }
        </script>

        <link rel="icon" type="image/png" href="{{ asset('Radio-dz.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <style>
            body {
                background-image: url('/algiers.jpg');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                background-repeat: no-repeat;
            }

        /* Light mode → overlay فاتح */
            .bg-overlay {
                min-height: 100vh;
                background: rgba(220, 230, 255, 0.40);
                backdrop-filter: blur(1px);
                transition: background 0.4s ease;
            }

            
            html.dark .bg-overlay {
                background: rgba(8, 12, 28, 0.70);
            }

            #particles-canvas {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 0;
                pointer-events: none;
            }

            .content-wrapper {
                position: relative;
                z-index: 1;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">

        <!-- Particles -->
        <canvas id="particles-canvas"></canvas>

        <!-- Overlay -->
        <div class="bg-overlay">
            <div class="content-wrapper min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <script>
            const canvas = document.getElementById('particles-canvas');
            const ctx = canvas.getContext('2d');
            let particles = [];
            const PARTICLE_COUNT = 80;

            function resize() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }

            function isDark() {
                return document.documentElement.classList.contains('dark');
            }

            function randomParticle() {
                return {
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    r: Math.random() * 1.8 + 0.5,
                    dx: (Math.random() - 0.5) * 0.4,
                    dy: (Math.random() - 0.5) * 0.4,
                    alpha: Math.random() * 0.5 + 0.2,
                };
            }

            function drawLine(p1, p2, dist, maxDist) {
                const opacity = (1 - dist / maxDist) * 0.3;
                ctx.beginPath();
                ctx.moveTo(p1.x, p1.y);
                ctx.lineTo(p2.x, p2.y);
                ctx.strokeStyle = isDark()
                    ? `rgba(99, 160, 255, ${opacity})`
                    : `rgba(37, 99, 235, ${opacity})`;
                ctx.lineWidth = 0.6;
                ctx.stroke();
            }

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                const maxDist = 130;
                const color = isDark() ? '99, 160, 255' : '37, 99, 235';

                for (let i = 0; i < particles.length; i++) {
                    const p = particles[i];
                    p.x += p.dx;
                    p.y += p.dy;
                    if (p.x < 0 || p.x > canvas.width) p.dx *= -1;
                    if (p.y < 0 || p.y > canvas.height) p.dy *= -1;

                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(${color}, ${p.alpha})`;
                    ctx.fill();

                    for (let j = i + 1; j < particles.length; j++) {
                        const p2 = particles[j];
                        const dist = Math.hypot(p.x - p2.x, p.y - p2.y);
                        if (dist < maxDist) drawLine(p, p2, dist, maxDist);
                    }
                }
                requestAnimationFrame(animate);
            }

            window.addEventListener('resize', resize);
            resize();
            particles = Array.from({ length: PARTICLE_COUNT }, randomParticle);
            animate();
        </script>
    </body>
</html>