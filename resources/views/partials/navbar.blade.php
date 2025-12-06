<nav class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('applications.index') }}" class="text-xl font-semibold text-gray-800">Score Credit App</a>
                </div>
                <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-3 items-center">
                    @php
                        $isHome = request()->routeIs('applications.index');
                        $isCreate = request()->routeIs('applications.create');
                    @endphp
                    <a href="{{ route('applications.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ $isHome ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">Home</a>
                    <a href="{{ route('applications.create') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ $isCreate ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">Input Data</a>
                </div>
            </div>

            <div class="flex items-center">
                @auth
                    <div class="hidden sm:flex sm:items-center sm:space-x-4">
                        <span class="text-gray-700">Hi, {{ Auth::user()->username }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">Logout</button>
                        </form>
                    </div>
                @else
                    <div class="hidden sm:flex sm:items-center sm:space-x-4">
                        <a href="{{ route('login') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">Login</a>
                        <a href="{{ route('register') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">Register</a>
                    </div>
                @endauth
                <!-- Mobile menu button -->
                <div class="sm:hidden flex items-center">
                    <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide with JS -->
    <div class="mobile-menu hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('applications.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ $isHome ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">Home</a>
            <a href="{{ route('applications.create') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ $isCreate ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">Input Data</a>
            @auth
                <form action="{{ route('logout') }}" method="POST" class="px-3 py-2">
                    @csrf
                    <button type="submit" class="w-full text-left text-gray-700">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Login</a>
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Register</a>
            @endauth
        </div>
    </div>

    <script>
        // Simple mobile menu toggle
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.querySelector('.mobile-menu-button');
            const menu = document.querySelector('.mobile-menu');
            if (btn) {
                btn.addEventListener('click', () => {
                    menu.classList.toggle('hidden');
                });
            }
        });
    </script>
</nav>
