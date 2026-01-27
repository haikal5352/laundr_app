<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white font-black text-lg shadow-lg shadow-blue-200 group-hover:scale-110 transition-transform duration-300">
                            U9
                        </div>
                        <div class="text-left hidden sm:block">
                            <h1 class="text-xl font-black text-slate-800 leading-none tracking-tight">Laundry</h1>
                            <p class="text-[0.6rem] font-bold text-blue-600 tracking-[0.3em] uppercase mt-0.5">U9</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="false">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('reviews.index')" :active="request()->routeIs('reviews.*')">
                        ⭐ {{ __('Ulasan') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Notifications & Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 sm:gap-4">
                @auth
                <!-- Notification Bell -->
                @php
                    $unreadCount = Auth::user()->unreadNotifications->count();
                @endphp
                <x-dropdown align="right" width="96">
                    <x-slot name="trigger">
                        <button class="relative p-2 text-slate-500 hover:text-blue-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="p-3 border-b border-slate-100">
                            <h3 class="font-bold text-slate-800">🔔 Notifikasi</h3>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            @forelse(Auth::user()->notifications->take(10) as $notification)
                                <a href="{{ $notification->data['link'] ?? '#' }}" 
                                   onclick="markAsRead('{{ $notification->id }}')"
                                   class="block px-4 py-3 hover:bg-slate-50 border-b border-slate-50 {{ $notification->read_at ? 'opacity-60' : 'bg-blue-50' }}">
                                    <p class="font-bold text-sm text-slate-800">{{ $notification->data['title'] ?? 'Notifikasi' }}</p>
                                    <p class="text-xs text-slate-500">{{ $notification->data['message'] ?? '' }}</p>
                                    <p class="text-xs text-slate-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </a>
                            @empty
                                <div class="px-4 py-6 text-center text-slate-400">
                                    <p class="text-2xl mb-2">🔕</p>
                                    <p class="text-sm">Belum ada notifikasi</p>
                                </div>
                            @endforelse
                        </div>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <div class="p-2 border-t border-slate-100 text-center">
                                <form action="{{ route('notifications.markAllRead') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs text-blue-600 hover:underline">Tandai semua dibaca</button>
                                </form>
                            </div>
                        @endif
                    </x-slot>
                </x-dropdown>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-full shadow-lg shadow-blue-200 transition duration-300 ease-in-out transform hover:-translate-y-0.5">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                <!-- Guest: Show Login/Register buttons -->
                <a href="{{ route('login') }}" class="text-sm text-slate-600 hover:text-blue-600 font-medium">Login</a>
                <a href="{{ route('register') }}" class="text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-full shadow-lg shadow-blue-200 transition">Daftar</a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="false">
                🏠 {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @else
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="mt-3 space-y-1 px-4">
                <a href="{{ route('login') }}" class="block py-2 text-slate-600 hover:text-blue-600">Login</a>
                <a href="{{ route('register') }}" class="block py-2 text-blue-600 font-bold">Daftar</a>
            </div>
        </div>
        @endauth
    </div>
</nav>