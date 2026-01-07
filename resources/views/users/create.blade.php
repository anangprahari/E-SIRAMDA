@extends('layouts.app')
@section('title', 'Tambah Pengguna')
@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Header Card --}}
        <div class="mb-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    {{-- Left : Breadcrumb --}}
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col">
                            <x-breadcrumbs.pengguna current="Tambah Pengguna" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                {{-- Card Header --}}
                <div class="bg-gradient-to-r from-blue-100 via-blue-50 to-blue-100 border-b border-blue-200 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none">
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-blue-800">Tambah Pengguna Baru</h3>
                            <p class="text-sm text-blue-600">Lengkapi formulir di bawah untuk menambah pengguna</p>
                        </div>
                    </div>
                </div>

                {{-- Form Body --}}
                <div class="p-6">
                    <form id="create-user-form" action="{{ route('users.store') }}" method="POST" class="space-y-5">
                        @csrf

                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Masukkan nama lengkap"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                                       focus:border-blue-500 focus:ring-blue-500
                                       @error('name') @enderror">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Username --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Username
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="username" value="{{ old('username') }}"
                                placeholder="Masukkan username"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                                       focus:border-blue-500 focus:ring-blue-500
                                       @error('username') @enderror">
                            @error('username')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                                       focus:border-blue-500 focus:ring-blue-500
                                       @error('email') @enderror">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" placeholder="Masukkan password"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                                       focus:border-blue-500 focus:ring-blue-500
                                       @error('password') @enderror">
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" placeholder="Masukkan ulang password"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                                       focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                            <button type="button" onclick="confirmCreateUser()"
                                class="inline-flex items-center h-10 px-6
                                       bg-gradient-to-r from-blue-600 to-blue-700
                                       text-white rounded-lg text-sm font-medium
                                       hover:from-blue-700 hover:to-blue-800
                                       transition-all duration-300 shadow-md hover:shadow-lg">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Confirm Modal Component --}}
        <x-notifications.confirm-modal />
    </div>
@endsection

@push('page-scripts')
    <script>
        function confirmCreateUser() {
            // Selector yang lebih spesifik untuk menghindari konflik
            const modalElement = document.querySelector('[x-data="confirmModal()"]');
            if (!modalElement) {
                console.error('Confirm modal not found!');
                return;
            }

            const modal = Alpine.$data(modalElement);

            modal.show({
                title: 'Konfirmasi Tambah Pengguna',
                message: 'Apakah Anda yakin ingin menambahkan pengguna baru dengan data yang telah diisi?',
                confirmText: 'Ya, Tambahkan',
                cancelText: 'Batal',
                type: 'info',
                onConfirm: () => {
                    document.getElementById('create-user-form').submit();
                }
            });
        }
    </script>
@endpush

@push('page-styles')
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>
@endpush
