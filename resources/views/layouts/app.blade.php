<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PARKED</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
</head>
<body>

    <header>

    </header>

        @include('components.dheader')
        {{-- Layout wrapper: sidebar + main content --}}
        <div class="min-h-screen flex bg-gray-100">
            @include('components.sidebar')

            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                <main class="flex-1 overflow-y-auto w-full">
                    @yield('content')
                </main>
            </div>
        </div>


</body>
</html>
