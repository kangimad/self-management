<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if (auth()->user()->hasAnyPermission([
                                'finance-transaction-list',
                                'finance-category-list',
                                'finance-source-list',
                                'finance-balance-list',
                                'finance-allocation-list',
                            ]))
                        <!-- Finance Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = ! open"
                                class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                Finance
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute z-50 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                                @can('finance-transaction-list')
                                    <a href="{{ route('finance.transactions.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Transactions</a>
                                @endcan
                                @can('finance-category-list')
                                    <a href="{{ route('finance.categories.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Categories</a>
                                @endcan
                                @can('finance-source-list')
                                    <a href="{{ route('finance.sources.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sources</a>
                                @endcan
                                @can('finance-balance-list')
                                    <a href="{{ route('finance.balances.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Balances</a>
                                @endcan
                                @can('finance-allocation-list')
                                    <a href="{{ route('finance.allocations.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Allocations</a>
                                @endcan
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
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
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if (auth()->user()->hasAnyPermission([
                        'finance-transaction-list',
                        'finance-category-list',
                        'finance-source-list',
                        'finance-balance-list',
                        'finance-allocation-list',
                    ]))
                <!-- Finance Mobile Menu -->
                <div class="border-t border-gray-200 pt-2">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">Finance</div>
                    @can('finance-transaction-list')
                        <x-responsive-nav-link :href="route('finance.transactions.index')" :active="request()->routeIs('finance.transactions.*')">
                            {{ __('Transactions') }}
                        </x-responsive-nav-link>
                    @endcan
                    @can('finance-category-list')
                        <x-responsive-nav-link :href="route('finance.categories.index')" :active="request()->routeIs('finance.categories.*')">
                            {{ __('Categories') }}
                        </x-responsive-nav-link>
                    @endcan
                    @can('finance-source-list')
                        <x-responsive-nav-link :href="route('finance.sources.index')" :active="request()->routeIs('finance.sources.*')">
                            {{ __('Sources') }}
                        </x-responsive-nav-link>
                    @endcan
                    @can('finance-balance-list')
                        <x-responsive-nav-link :href="route('finance.balances.index')" :active="request()->routeIs('finance.balances.*')">
                            {{ __('Balances') }}
                        </x-responsive-nav-link>
                    @endcan
                    @can('finance-allocation-list')
                        <x-responsive-nav-link :href="route('finance.allocations.index')" :active="request()->routeIs('finance.allocations.*')">
                            {{ __('Allocations') }}
                        </x-responsive-nav-link>
                    @endcan
                </div>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
