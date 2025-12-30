@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Header Card --}}
        <div class="mb-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    {{-- Left : Breadcrumb --}}
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col">
                            <x-breadcrumbs.profil current="Ubah Password" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Success Alert --}}
        @if (session('status') === 'password-updated')
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
                    <p class="text-sm text-green-700 mt-1">Password berhasil diperbarui!</p>
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
                                <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                                <circle cx="12" cy="16" r="1"></circle>
                                <path d="M8 11v-4a4 4 0 0 1 8 0v4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-blue-800">Perbarui Password</h3>
                            <p class="text-sm text-blue-600">Pastikan akun Anda menggunakan password yang kuat</p>
                        </div>
                    </div>
                </div>

                {{-- Form Body --}}
                <div class="p-6">
                    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        {{-- Current Password --}}
                        <div>
                            <x-input-label for="current_password" :value="__('Password Saat Ini')" class="mb-2">
                                <span class="text-red-500 ml-1">*</span>
                            </x-input-label>
                            <x-text-input id="current_password" name="current_password" type="password"
                                class="mt-1 block w-full" autocomplete="current-password" required
                                placeholder="Masukkan password saat ini" />
                            <x-input-error class="mt-2" :messages="$errors->updatePassword->get('current_password')" />
                        </div>

                        {{-- New Password --}}
                        <div>
                            <x-input-label for="password" :value="__('Password Baru')" class="mb-2">
                                <span class="text-red-500 ml-1">*</span>
                            </x-input-label>
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                                autocomplete="new-password" required placeholder="Masukkan password baru" />
                            <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter untuk keamanan yang lebih baik</p>
                            <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password')" />
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" class="mb-2">
                                <span class="text-red-500 ml-1">*</span>
                            </x-input-label>
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                                class="mt-1 block w-full" autocomplete="new-password" required
                                placeholder="Masukkan ulang password baru" />
                            <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password_confirmation')" />
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                            <x-primary-button
                                class="inline-flex items-center
               bg-gradient-to-r from-blue-600 to-blue-700
               hover:from-blue-700 hover:to-blue-800
               text-white shadow-md">
                                {{ __('Perbarui Password') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Security Tips Card --}}
            <div class="mt-6 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-lg border border-yellow-200 p-4 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-600" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none">
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M12 8v4"></path>
                                <path d="M12 16h.01"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-yellow-900 mb-2">Tips Keamanan Password</h4>
                        <ul class="text-xs text-yellow-800 space-y-1.5">
                            <li class="flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 flex-shrink-0"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                    <path d="M5 12l5 5l10 -10"></path>
                                </svg>
                                <span>Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 flex-shrink-0"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                    <path d="M5 12l5 5l10 -10"></path>
                                </svg>
                                <span>Minimal 8 karakter untuk keamanan yang lebih baik</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 flex-shrink-0"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                    <path d="M5 12l5 5l10 -10"></path>
                                </svg>
                                <span>Jangan menggunakan informasi pribadi yang mudah ditebak</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 flex-shrink-0"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                    <path d="M5 12l5 5l10 -10"></path>
                                </svg>
                                <span>Perbarui password secara berkala untuk keamanan optimal</span>
                            </li>
                        </ul>
                    </div>
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
