<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Dashboard' }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">


    <link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire --}}
    @livewireStyles
</head>

<body>

    {{-- TOP NAV --}}
    <nav class="fixed top-0 z-50 w-full bg-primary-300 shadow-md ">
        <div class="px-4 py-3 flex items-center justify-between">
            {{-- Sidebar toggle (mobile) --}}
            <div class="left">
                <button data-drawer-target="sidebar" data-drawer-toggle="sidebar" aria-controls="sidebar"
                    class="p-2 text-primary-600 rounded-lg  me-5 hover:bg-primary-600 hover:text-white focus:ring-2 focus:ring-gray-200 transition duration-200">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <span class="text-xl font-semibold text-primary-800">
                    DSDA Material
                </span>
            </div>
            <div class="right">

                <div id="avatarButton" type="button" data-dropdown-toggle="userDropdown"
                    data-dropdown-placement="bottom-start"
                    class="w-10 h-10 rounded-full cursor-pointer bg-primary-600 text-white flex items-center justify-center font-semibold text-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1) . (strpos(auth()->user()->name ?? '', ' ') ? substr(auth()->user()->name, strpos(auth()->user()->name, ' ') + 1, 1) : '')) }}
                </div>

                <!-- Dropdown menu -->
                <div id="userDropdown"
                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                    <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                        <div>{{ auth()->user()->name ?? 'User' }}</div>
                        <div class="font-medium truncate">{{ auth()->user()->email ?? 'user@example.com' }}</div>
                    </div>
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
                        <li>
                            <a href="/profile"
                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Profile</a>
                        </li>
                    </ul>
                    <div class="py-1">
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign
                            out</a>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    {{-- SIDEBAR --}}
    <aside id="sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform duration-300 bg-gradient-to-br from-primary-600 to-primary-500 -translate-x-full">


        <div class="h-full px-4 py-4 overflow-y-auto">

            <x-sidebar />

        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main id="main-content"
        class="p-4 pt-20 transition-all duration-300 bg-gradient-to-br from-primary-100 to-primary-200 min-h-screen">


        {{ $slot }}
    </main>
    @livewireScripts
    @stack('scripts')
</body>

</html>