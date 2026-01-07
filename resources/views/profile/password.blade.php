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
                    <form id="password-form" method="POST" action="{{ route('password.update') }}" class="space-y-5">
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
                            <button type="button" onclick="confirmUpdatePassword()"
                                class="inline-flex items-center px-6 py-2.5
                   bg-gradient-to-r from-blue-600 to-blue-700
                   hover:from-blue-700 hover:to-blue-800
                   text-white text-sm font-medium rounded-lg
                   transition-all duration-300 shadow-md hover:shadow-lg
                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Perbarui Password
                            </button>
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
        {{-- Confirm Modal Component --}}
        <x-notifications.confirm-modal />
    </div>
@endsection
@push('page-scripts')
    <script>
        function confirmUpdatePassword() {
            const modalElement = document.querySelector('[x-data="confirmModal()"]');
            if (!modalElement) {
                console.error('Confirm modal not found!');
                return;
            }

            const modal = Alpine.$data(modalElement);

            modal.show({
                title: 'Konfirmasi Ubah Password',
                message: 'Apakah Anda yakin ingin mengubah password akun ini? Pastikan Anda mengingat password baru yang telah diisi.',
                confirmText: 'Ya, Ubah Password',
                cancelText: 'Batal',
                type: 'warning',
                onConfirm: () => {
                    document.getElementById('password-form').submit();
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
