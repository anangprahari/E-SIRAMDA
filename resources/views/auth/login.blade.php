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
        <div class="relative">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Password
            </label>

            <input id="password" type="password" name="password" required
                class="w-full border-b-2 border-gray-300 focus:border-blue-600 focus:outline-none py-3 pr-10 text-gray-800 placeholder-gray-400"
                placeholder="Masukkan password Anda">

            {{-- Toggle Password --}}
            <button type="button" onclick="togglePassword()"
                class="absolute right-2 top-10 text-gray-500 hover:text-gray-700 focus:outline-none transition-colors">

                {{-- Eye Icon (Show) --}}
                <svg id="eyeIconShow" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>

                {{-- Eye Slash Icon (Hide) --}}
                <svg id="eyeIconHide" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
            </button>
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

    {{-- JavaScript Toggle Password --}}
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIconShow = document.getElementById('eyeIconShow');
            const eyeIconHide = document.getElementById('eyeIconHide');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIconShow.classList.add('hidden');
                eyeIconHide.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIconShow.classList.remove('hidden');
                eyeIconHide.classList.add('hidden');
            }
        }
    </script>

</x-guest-layout>
