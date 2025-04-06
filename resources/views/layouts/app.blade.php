<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Commercial Freelance - @yield('title', 'Dashboard')</title>
    <!-- Utilisez uniquement une version de Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex">
        @include('layouts.navigation')

        <div class="flex-1 p-6 ml-64">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')

            @stack('scripts') <!-- Ajoutez cette ligne pour rendre les scripts poussés -->
        </div>
    </div>

    <!-- Incluez SweetAlert2 ici pour garantir qu'il est chargé après le contenu -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>