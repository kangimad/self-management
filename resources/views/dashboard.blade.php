<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Welcome, ') }}{{ auth()->user()->name }}!</h3>

                    <div class="mb-4">
                        <h4 class="font-medium text-gray-700">Your Roles:</h4>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @forelse(auth()->user()->roles as $role)
                                <span
                                    class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">{{ $role->name }}</span>
                            @empty
                                <span class="text-gray-500">No roles assigned</span>
                            @endforelse
                        </div>
                    </div>

                    @if (auth()->user()->hasAnyPermission([
                                'finance-transaction-list',
                                'finance-category-list',
                                'finance-source-list',
                                'finance-balance-list',
                                'finance-allocation-list',
                            ]))
                        <div class="mt-6">
                            <h4 class="font-medium text-gray-700 mb-3">Finance Module</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @can('finance-transaction-list')
                                    <a href="{{ route('finance.transactions.index') }}"
                                        class="block p-4 bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg transition-colors">
                                        <h5 class="font-semibold text-green-800">Transactions</h5>
                                        <p class="text-sm text-green-600">Manage financial transactions</p>
                                    </a>
                                @endcan

                                @can('finance-category-list')
                                    <a href="{{ route('finance.categories.index') }}"
                                        class="block p-4 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-colors">
                                        <h5 class="font-semibold text-blue-800">Categories</h5>
                                        <p class="text-sm text-blue-600">Manage transaction categories</p>
                                    </a>
                                @endcan

                                @can('finance-source-list')
                                    <a href="{{ route('finance.sources.index') }}"
                                        class="block p-4 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg transition-colors">
                                        <h5 class="font-semibold text-purple-800">Sources</h5>
                                        <p class="text-sm text-purple-600">Manage finance sources</p>
                                    </a>
                                @endcan

                                @can('finance-balance-list')
                                    <a href="{{ route('finance.balances.index') }}"
                                        class="block p-4 bg-yellow-50 hover:bg-yellow-100 border border-yellow-200 rounded-lg transition-colors">
                                        <h5 class="font-semibold text-yellow-800">Balances</h5>
                                        <p class="text-sm text-yellow-600">View account balances</p>
                                    </a>
                                @endcan

                                @can('finance-allocation-list')
                                    <a href="{{ route('finance.allocations.index') }}"
                                        class="block p-4 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition-colors">
                                        <h5 class="font-semibold text-red-800">Allocations</h5>
                                        <p class="text-sm text-red-600">Manage budget allocations</p>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @endif

                    @hasrole('admin')
                        <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                            <h4 class="font-semibold text-amber-800">Admin Panel</h4>
                            <p class="text-sm text-amber-600">You have administrator privileges</p>
                        </div>
                    @endhasrole
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
