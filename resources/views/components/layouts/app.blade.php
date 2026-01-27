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

<body class="overflow-x-hidden">

    {{-- TOP NAV --}}
    <nav class="fixed top-0 z-50 w-full bg-primary-300 shadow-md ">
        <div class="px-4 py-3 flex items-center justify-between">
            {{-- Sidebar toggle --}}
            <div class="left flex items-center">
                <button id="sidebar-toggle"
                    class="p-2 text-primary-600 rounded-lg  me-5 hover:bg-primary-600 hover:text-white focus:ring-2 focus:ring-gray-200 transition duration-200">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <span class="text-xl font-semibold text-primary-800 hidden sm:inline">
                    DSDA Material
                </span>
            </div>
            <div class="right">

                <div id="avatarButton" type="button" data-dropdown-toggle="userDropdown"
                    data-dropdown-placement="bottom-start"
                    class="w-10 h-10 rounded-full cursor-pointer bg-primary-600 text-white flex items-center justify-center font-semibold text-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1) . (strpos(auth()->user()->name ?? '', ' ') ?
                    substr(auth()->user()->name, strpos(auth()->user()->name, ' ') + 1, 1) : '')) }}
                </div>

                <!-- Dropdown menu -->
                <div id="userDropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44">
                    <div class="px-4 py-3 text-sm text-gray-900 ">
                        <div>{{ auth()->user()->name ?? 'User' }}</div>
                        <div class="font-medium truncate">{{ auth()->user()->email ?? 'user@example.com' }}</div>
                    </div>
                    <ul class="py-2 text-sm text-gray-700 " aria-labelledby="avatarButton">
                        <li>
                            <a href="{{ route('profile.edit') }}" wire:navigate
                                class="block px-4 py-2 hover:bg-gray-100 :bg-gray-600 :text-white">Profile</a>
                        </li>
                    </ul>
                    <div class="py-1">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign
                            out</a>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    {{-- SIDEBAR BACKDROP (mobile only) --}}
    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden" onclick="closeSidebarMobile()">
    </div>

    {{-- WRAPPER FOR SIDEBAR AND MAIN CONTENT --}}
    <div class="flex pt-14">
        {{-- SIDEBAR --}}
        {{-- Mobile: fixed overlay, Desktop: sticky sidebar that pushes content --}}
        <aside id="sidebar" class="w-64 h-[calc(100vh-3.5rem)] shrink-0 transition-all duration-300 bg-gradient-to-br from-primary-600 to-primary-500 overflow-hidden sidebar-closed
                   fixed md:sticky top-14 left-0 z-40 md:z-auto self-start">
            <div class="h-full px-4 py-4 overflow-y-auto w-64">
                <x-sidebar />
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <main id="main-content"
            class="flex-1 w-full p-4 transition-all duration-300 bg-gradient-to-br from-primary-100 to-primary-200 min-h-[calc(100vh-3.5rem)]">
            <div class="pt-2 sm:pt-4">
                {{ $slot }}
            </div>
        </main>
    </div>


    @livewireScripts
    @stack('scripts')
</body>

</html>