<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100">

    <div class="min-h-screen grid grid-cols-2 lg:grid-cols-5 items-stretch">


        {{-- ================================
        KOLOM KIRI : GAMBAR
        ================================ --}}
        <div
            class="hidden lg:flex lg:col-span-3
           relative
           bg-[#f5f9ff]
           -mr-6
           z-10
           overflow-hidden">

            <img src="{{ asset('assets/img/gambarlogin.png') }}" alt="Ilustrasi Login E-SIRAMDA"
                class="w-full h-full object-cover object-center" />
        </div>

        {{-- ================================
        KOLOM KANAN : FORM LOGIN
        ================================ --}}
        <div
            class="relative z-20 flex items-center justify-center px-8 py-12 bg-white lg:col-span-2 lg:rounded-l-[25px] shadow-2xl">

            <div class="w-full max-w-md">

                {{-- Header --}}
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-extrabold tracking-wide text-gray-800">
                        E-SIRAMDA
                    </h1>
                    <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                        Elektronik Sistem Informasi Inventaris<br>
                        Barang Milik Daerah
                    </p>
                </div>

                {{-- Card --}}
                <div class="bg-white rounded-2xl shadow-2xl px-8 py-10 border border-gray-100">

                    {{-- Logo --}}
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('assets/img/backgrounds/logosiramda.png') }}" class="h-16"
                            alt="Logo E-SIRAMDA">
                    </div>

                    {{-- Slot Form --}}
                    {{ $slot }}

                </div>

                {{-- Footer --}}
                <p class="mt-8 text-xs text-center text-gray-400">
                    © {{ date('Y') }} Diskominfo Provinsi Jambi
                </p>

            </div>
        </div>

    </div>

</body>

</html>
