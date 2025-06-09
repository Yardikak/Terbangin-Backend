<nav x-data="{ open: false }" class="bg-blue-500 border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex space-x-0">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('airplane-air-plane-fly-svgrepo-com.png') }}" class="block h-9 w-auto" alt="Logo Pesawat" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="flex space-x-0">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-black hover:bg-yellow-300 {{ request()->routeIs('dashboard') ? 'bg-yellow-500' : '' }} px-4 py-2">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('flights.index')" :active="request()->routeIs('flights.*')" class="text-black hover:bg-yellow-300 {{ request()->routeIs('flights.*') ? 'bg-yellow-500' : '' }} px-4 py-2">
                        {{ __('Flights') }}
                    </x-nav-link>
                    <x-nav-link :href="route('history.index')" :active="request()->routeIs('history.*')" class="text-black hover:bg-yellow-300 {{ request()->routeIs('history.*') ? 'bg-yellow-500' : '' }} px-4 py-2">
                        {{ __('History') }}
                    </x-nav-link>
                    <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')" class="text-black hover:bg-yellow-300 {{ request()->routeIs('payments.*') ? 'bg-yellow-500' : '' }} px-4 py-2">
                        {{ __('Payments') }}
                    </x-nav-link>
                    <x-nav-link :href="route('promo.index')" :active="request()->routeIs('promo.*')" class="text-black hover:bg-yellow-300 {{ request()->routeIs('promo.*') ? 'bg-yellow-500' : '' }} px-4 py-2">
                        {{ __('Promo') }}
                    </x-nav-link>
                    <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')" class="text-black hover:bg-yellow-300 {{ request()->routeIs('tickets.*') ? 'bg-yellow-500' : '' }} px-4 py-2">
                        {{ __('Tickets') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-black hover:bg-yellow-300 {{ request()->routeIs('dashboard') ? 'bg-yellow-500' : '' }} px-4 py-2">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('flights.index')" :active="request()->routeIs('flights.*')" class="text-black hover:bg-yellow-300 {{ request()->routeIs('flights.*') ? 'bg-yellow-500' : '' }} px-4 py-2">
                {{ __('Flights') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('history.index')" :active="request()->routeIs('history.*')" class="text-black hover:bg-yellow-300 {{ request()->routeIs('history.*') ? 'bg-yellow-500' : '' }} px-4 py-2">
                {{ __('History') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')" class="text-black hover:bg-yellow-300 {{ request()->routeIs('payments.*') ? 'bg-yellow-500' : '' }} px-4 py-2">
                {{ __('Payments') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('promo.index')" :active="request()->routeIs('promo.*')" class="text-black hover:bg-yellow-300 {{ request()->routeIs('promo.*') ? 'bg-yellow-500' : '' }} px-4 py-2">
                {{ __('Promo') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')" class="text-black hover:bg-yellow-300 {{ request()->routeIs('tickets.*') ? 'bg-yellow-500' : '' }} px-4 py-2">
                {{ __('Tickets') }}
            </x-responsive-nav-link>
        </div>
    </div>
</nav>
