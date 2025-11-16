<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Inter font --}}
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Theme --}}
    <script>
        tailwind.config = {
            theme: {
                fontFamily: {
                    sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                },
                extend: {
                    colors: {
                        base: "#0E1217",
                        panel: "#1A1F2B",
                        border: "#4B5563",
                        accent: "#4ADE80",
                        textlight: "#D1D5DB",
                    }
                }
            }
        }
    </script>

    <title>@yield('title', 'Dashboard')</title>
</head>

<body class="bg-base text-textlight">

    <div class="flex">

        {{-- SIDEBAR --}}
        <aside class="w-64 min-h-screen bg-panel border-r border-border">
            @include('components.verticalHeader')
        </aside>

        {{-- PAGE CONTENT --}}
        <main class="flex-1 p-10">
            @yield('content')
        </main>

    </div>

</body>
</html>
