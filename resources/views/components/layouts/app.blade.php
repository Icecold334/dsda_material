<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Dashboard' }}</title>

    <link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire --}}
    @livewireStyles
</head>

<body class="bg-gradient-to-br from-primary-100 to-primary-200 min-h-screen">

    {{-- TOP NAV --}}
    <nav class="fixed top-0 z-50 w-full bg-primary-300 shadow-md ">
        <div class="px-4 py-3 flex items-center ">
            {{-- Sidebar toggle (mobile) --}}
            <button data-drawer-target="sidebar" data-drawer-toggle="sidebar" aria-controls="sidebar"
                class="p-2 text-primary-600 rounded-lg md:hidden me-5 hover:bg-primary-600 hover:text-white focus:ring-2 focus:ring-gray-200 transition duration-200">
                <i class="fa-solid fa-bars"></i>
            </button>

            <span class="text-xl font-semibold text-primary-800">
                DSDA Material
            </span>
        </div>
    </nav>

    {{-- SIDEBAR --}}
    <aside id="sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform -translate-x-full bg-gradient-to-br from-primary-600 to-primary-500  md:translate-x-0">

        <div class="h-full px-4 py-4 overflow-y-auto">

            <x-navbar />

        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="p-4 md:ml-64 pt-20">
        {{ $slot }}
    </main>

    @livewireScripts
</body>

</html>