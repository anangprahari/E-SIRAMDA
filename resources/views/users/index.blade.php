@extends('layouts.app')
@section('title', 'Daftar Pengguna')
@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Header Card --}}
        <div class="mb-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    {{-- Left : Breadcrumb --}}
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col">
                            <x-breadcrumbs.pengguna />
                        </div>
                    </div>

                    {{-- Right : Action --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('users.create') }}"
                            class="inline-flex items-center h-9 px-4
                           bg-gradient-to-r from-blue-600 to-blue-700
                           text-white rounded-lg text-sm font-medium
                           hover:from-blue-700 hover:to-blue-800
                           transition-all duration-300 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah Pengguna
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {{-- Main Table Card --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-blue-100 via-blue-50 to-blue-100 border-b border-blue-200 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none">
                            <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-blue-800">Daftar Pengguna</h3>
                        <p class="text-sm text-blue-600">
                            Menampilkan {{ $users->firstItem() ?? 0 }} – {{ $users->lastItem() ?? 0 }}
                            dari {{ $users->total() }} pengguna
                        </p>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full min-w-max text-sm">
                    <thead class="bg-gradient-to-r from-blue-600 to-blue-700 text-white sticky top-0 z-10">
                        <tr>
                            <th class="px-5 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">No
                            </th>
                            <th class="px-5 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">Nama
                            </th>
                            <th class="px-5 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">
                                Username</th>
                            <th class="px-5 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">Email
                            </th>
                            <th class="px-5 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">
                                Dibuat</th>
                            <th class="px-5 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($users as $index => $user)
                            <tr
                                class="hover:bg-blue-50 transition-colors duration-200 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                <td class="px-5 py-4 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-700 rounded-lg font-semibold text-xs">
                                        {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-xs">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-gray-700 font-mono text-sm whitespace-nowrap">
                                    {{ $user->username }}
                                </td>
                                <td class="px-5 py-4 text-gray-700 whitespace-nowrap">
                                    {{ $user->email }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="text-gray-700">{{ $user->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <a href="{{ route('users.show', $user) }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200 text-xs font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" fill="none">
                                            <circle cx="12" cy="12" r="2"></circle>
                                            <path
                                                d="M22 12c-2.667 4 -6 6 -10 6s-7.333 -2 -10 -6c2.667 -4 6 -6 10 -6s7.333 2 10 6">
                                            </path>
                                        </svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16">
                                    <div class="text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 mx-auto text-blue-300 mb-4"
                                            viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none">
                                            <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                                        </svg>
                                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada data pengguna</h3>
                                        <p class="text-gray-500 mb-6">Mulai dengan menambahkan pengguna pertama Anda</p>
                                        <a href="{{ route('users.create') }}"
                                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" fill="none">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                            Tambah Pengguna Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            @if ($users->hasPages())
                <div class="bg-gradient-to-r from-blue-100 via-blue-50 to-blue-100 border-t border-blue-200 px-6 py-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center text-sm text-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none">
                                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                            </svg>
                            <span>
                                Menampilkan <span class="font-bold text-blue-800">{{ $users->firstItem() }}</span>
                                sampai <span class="font-bold text-blue-800">{{ $users->lastItem() }}</span>
                                dari <span class="font-bold text-blue-800">{{ $users->total() }}</span> data
                            </span>
                        </div>
                        <div class="flex justify-center sm:justify-end">
                            {{ $users->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('page-scripts')
    <script>
        $(document).ready(function() {
            // Smooth scroll on pagination
            $('.pagination a').on('click', function() {
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
            });
        });
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
