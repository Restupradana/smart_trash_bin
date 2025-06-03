<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Pilih Role Anda
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form method="POST" action="{{ route('pilih.role.post') }}">
                    @csrf

                    <p class="mb-4 text-lg text-gray-700 dark:text-gray-300">
                        Silakan pilih role yang ingin Anda gunakan:
                    </p>

                    @foreach ($roles as $role)
                        <div class="mb-3">
                            <label class="inline-flex items-center">
                                <input type="radio" name="role" value="{{ $role }}" required
                                    class="text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <span class="ml-2 capitalize">{{ $role }}</span>
                            </label>
                        </div>
                    @endforeach

                    <div class="mt-6">
                        <x-primary-button>
                            Lanjutkan
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
