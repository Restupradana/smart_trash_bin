<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-900 dark:text-white leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <!-- Topbar (Navbar) -->
            <nav class="navbar navbar-expand navbar-light bg-white shadow-sm px-4 justify-content-end align-items-center" style="height: 60px;">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff"
                             alt="avatar" width="32" height="32" class="rounded-circle me-2">
                        <strong>{{ Auth::user()->name }}</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </x-slot>

    <!-- Content Area -->
    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-50 dark:bg-gray-800 shadow-xl sm:rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">
                    {{ __('Profile Information') }}
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <span class="block text-sm font-medium text-gray-600 dark:text-gray-300">Name:</span>
                        <div class="mt-1 text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</div>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-gray-600 dark:text-gray-300">Email:</span>
                        <div class="mt-1 text-gray-900 dark:text-gray-100">{{ Auth::user()->email }}</div>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-gray-600 dark:text-gray-300">Registered At:</span>
                        <div class="mt-1 text-gray-900 dark:text-gray-100">
                            {{ Auth::user()->created_at->format('d M Y') }}</div>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-gray-600 dark:text-gray-300">Last Updated:</span>
                        <div class="mt-1 text-gray-900 dark:text-gray-100">
                            {{ Auth::user()->updated_at->diffForHumans() }}</div>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('profile.edit') }}"
                        class="inline-block px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 transition">
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
