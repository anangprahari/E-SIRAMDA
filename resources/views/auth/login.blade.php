<x-guest-layout>

    {{-- Error --}}
    @if ($errors->any())
        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Email
            </label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full border-b-2 border-gray-300 focus:border-blue-600 focus:outline-none py-3 text-gray-800 placeholder-gray-400"
                placeholder="email@diskominfo.go.id">
        </div>

        {{-- Password --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Password
            </label>
            <input type="password" name="password" required
                class="w-full border-b-2 border-gray-300 focus:border-blue-600 focus:outline-none py-3 text-gray-800 placeholder-gray-400"
                placeholder="Masukkan password Anda">
        </div>

        {{-- Remember --}}
        <div class="flex items-center">
            <input id="remember" type="checkbox" name="remember"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            <label for="remember" class="ml-2 text-sm text-gray-600">
                Ingat saya
            </label>
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-xl font-semibold shadow-md hover:shadow-lg hover:scale-[1.01] transition-all duration-200">
            Masuk
        </button>
    </form>

</x-guest-layout>
