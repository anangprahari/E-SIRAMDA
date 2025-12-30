@extends('layouts.app')
@section('title', 'Detail Pengguna')
@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Header Card --}}
        <div class="mb-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    {{-- Left : Breadcrumb --}}
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col">
                            <x-breadcrumbs.pengguna current="Detail Pengguna" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Card --}}
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
                            <h3 class="text-lg font-bold text-blue-800">Detail Pengguna</h3>
                            <p class="text-sm text-blue-600">Informasi lengkap pengguna</p>
                        </div>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="p-6">
                    <div class="space-y-5">
                        {{-- Nama Lengkap --}}
                        <div class="flex items-start gap-4 pb-4 border-b border-gray-100">
                            <div class="flex-1">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Nama Lengkap</p>
                                <p class="text-gray-900 font-semibold text-base">{{ $user->name }}</p>
                            </div>
                        </div>

                        {{-- Username --}}
                        <div class="flex items-start gap-4 pb-4 border-b border-gray-100">
                            <div class="flex-1">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Username</p>
                                <p class="text-gray-900 font-semibold text-base font-mono">{{ $user->username }}</p>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="flex items-start gap-4 pb-4 border-b border-gray-100">
                            <div class="flex-1">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Email</p>
                                <p class="text-gray-900 font-semibold text-base">{{ $user->email }}</p>
                            </div>
                        </div>

                        {{-- Tanggal Dibuat --}}
                        <div class="flex items-start gap-4">
                            <div class="flex-1">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Tanggal Dibuat</p>
                                <p class="text-gray-900 font-semibold text-base">
                                    {{ $user->created_at->format('d M Y, H:i') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $user->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection