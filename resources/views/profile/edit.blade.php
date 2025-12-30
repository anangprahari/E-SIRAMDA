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

        {{-- Success Alert --}}
        @if (session('status') === 'profile-updated')
            <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-lg shadow-md p-4 flex items-start animate-fade-in"
                role="alert" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none">
                        <path d="M5 12l5 5l10 -10"></path>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="text-sm font-bold text-green-800">Berhasil!</h4>
                    <p class="text-sm text-green-700 mt-1">Profil berhasil diperbarui!</p>
                </div>
                <button type="button" class="ml-3 flex-shrink-0 text-green-600 hover:text-green-800" @click="show = false">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        @endif

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
                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
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
                            <x-primary-button
                                class="inline-flex items-center
               bg-gradient-to-r from-blue-600 to-blue-700
               hover:from-blue-700 hover:to-blue-800
               text-white shadow-md">
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

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
