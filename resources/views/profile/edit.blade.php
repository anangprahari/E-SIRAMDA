@extends('layouts.app')

@section('title', 'Edit Profil ')

@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Header Card --}}
        <div class="mb-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    {{-- Left : Breadcrumb --}}
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col">
                            <x-breadcrumbs.profil />
                        </div>
                    </div>

                    {{-- Right : Action --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('password.edit') }}"
                            class="inline-flex items-center h-9 px-4
               bg-gradient-to-r from-red-600 to-red-700
               text-white rounded-lg text-sm font-medium
               hover:from-red-700 hover:to-red-800
               transition-all duration-300 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none">
                                <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                                <circle cx="12" cy="16" r="1"></circle>
                                <path d="M8 11v-4a4 4 0 0 1 8 0v4"></path>
                            </svg>
                            Ubah Password
                        </a>
                    </div>


                </div>
            </div>
        </div>

        {{-- Main Form Card --}}
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
                            <h3 class="text-lg font-bold text-blue-800">Informasi Profil</h3>
                            <p class="text-sm text-blue-600">Perbarui informasi akun Anda</p>
                        </div>
                    </div>
                </div>

                {{-- Form Body --}}
                <div class="p-6">
                    <form id="profile-form" method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                        @csrf
                        @method('PATCH')

                        {{-- Nama Lengkap --}}
                        <div>
                            <x-input-label for="name" :value="__('Nama Lengkap')" class="mb-2">
                                <span class="text-red-500 ml-1">*</span>
                            </x-input-label>
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name', $user->name)" required autofocus autocomplete="name"
                                placeholder="Masukkan nama lengkap" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        {{-- Username --}}
                        <div>
                            <x-input-label for="username" :value="__('Username')" class="mb-2">
                                <span class="text-red-500 ml-1">*</span>
                            </x-input-label>
                            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full"
                                :value="old('username', $user->username)" required autocomplete="username" placeholder="Masukkan username" />
                            <x-input-error class="mt-2" :messages="$errors->get('username')" />
                        </div>

                        {{-- Email --}}
                        <div>
                            <x-input-label for="email" :value="__('Email')" class="mb-2">
                                <span class="text-red-500 ml-1">*</span>
                            </x-input-label>
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                :value="old('email', $user->email)" required autocomplete="email" placeholder="contoh@email.com" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>
                        {{-- Action Buttons --}}
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                            <button type="button" onclick="confirmUpdateProfile()"
                                class="inline-flex items-center px-6 py-2.5
                   bg-gradient-to-r from-blue-600 to-blue-700
                   hover:from-blue-700 hover:to-blue-800
                   text-white text-sm font-medium rounded-lg
                   transition-all duration-300 shadow-md hover:shadow-lg
                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Confirm Modal Component --}}
    <x-notifications.confirm-modal />
@endsection
@push('page-scripts')
    <script>
        function confirmUpdateProfile() {
            const modalElement = document.querySelector('[x-data="confirmModal()"]');
            if (!modalElement) {
                console.error('Confirm modal not found!');
                return;
            }

            const modal = Alpine.$data(modalElement);

            modal.show({
                title: 'Konfirmasi Perubahan Profil',
                message: 'Apakah Anda yakin ingin menyimpan perubahan profil ini?',
                confirmText: 'Ya, Simpan',
                cancelText: 'Batal',
                type: 'info',
                onConfirm: () => {
                    document.getElementById('profile-form').submit();
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
