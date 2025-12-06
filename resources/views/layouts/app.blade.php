<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Score Credit App') }}</title>
    {{-- Tailwind CDN for quick dev; replace with compiled assets for production --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    @include('partials.navbar')

    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
