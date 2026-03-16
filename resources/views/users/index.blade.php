@extends('layouts.app')
@section('title', 'Daftar Pengguna')

@section('content')
    <div class="container mx-auto px-4 py-6">

        {{-- Header Card --}}
        <div class="mb-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    {{-- Breadcrumb --}}
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col">
                            <x-breadcrumbs.pengguna />
                        </div>
                    </div>

                    {{-- Tombol Tambah Pengguna --}}
                    <div class="flex flex-wrap items-center gap-2">
                        @if (auth()->user()->isSuperUser())
                            <a href="{{ route('users.create') }}"
                                class="inline-flex items-center h-9 px-4
                           bg-gradient-to-r from-blue-600 to-blue-700
                           text-white rounded-lg text-sm font-medium
                           hover:from-blue-700 hover:to-blue-800
                           transition-all duration-300 shadow-md">

                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" fill="none">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>

                                Tambah Pengguna
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- Card Table --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">

            {{-- Header --}}
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
                            <th class="px-5 py-3 text-center">No</th>
                            <th class="px-5 py-3 text-left">Nama</th>
                            <th class="px-5 py-3 text-left">Role</th>
                            <th class="px-5 py-3 text-left">Username</th>
                            <th class="px-5 py-3 text-left">Email</th>
                            <th class="px-5 py-3 text-left">Dibuat</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">

                        @forelse($users as $index => $user)

                            <tr class="hover:bg-blue-50 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">

                                {{-- No --}}
                                <td class="px-5 py-4 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold">
                                        {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
                                    </span>
                                </td>

                                {{-- Nama --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">

                                        <div
                                            class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-xs">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>

                                        <div>
                                            <div class="font-medium text-gray-900">
                                                {{ $user->name }}
                                            </div>

                                            {{-- Badge Status --}}
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium mt-1
                            {{ $user->isAktif() ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </div>

                                    </div>
                                </td>

                                {{-- ✅ Role --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if ($user->isSuperUser())
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                            Super User
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                            User
                                        </span>
                                    @endif
                                </td>

                                {{-- Username --}}
                                <td class="px-5 py-4 text-gray-700 font-mono">
                                    {{ $user->username }}
                                </td>

                                {{-- Email --}}
                                <td class="px-5 py-4 text-gray-700">
                                    {{ $user->email }}
                                </td>

                                {{-- Created --}}
                                <td class="px-5 py-4">
                                    <div>{{ $user->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->created_at->format('H:i') }}</div>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-4 text-center whitespace-nowrap">

                                    <div class="flex items-center justify-center gap-2">

                                        {{-- Detail --}}
                                        <a href="{{ route('users.show', $user) }}"
                                            class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 text-xs font-medium">

                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" fill="none">
                                                <circle cx="12" cy="12" r="2"></circle>
                                                <path
                                                    d="M22 12c-2.667 4 -6 6 -10 6s-7.333 -2 -10 -6c2.667 -4 6 -6 10 -6s7.333 2 10 6">
                                                </path>
                                            </svg>

                                            Detail
                                        </a>

                                        {{-- Toggle Status (Super User) --}}
                                        @if (auth()->user()->isSuperUser() && auth()->id() !== $user->id)
                                            <form action="{{ route('users.toggleStatus', $user->id) }}" method="POST"
                                                onsubmit="return confirmToggleStatus(this,'{{ $user->name }}','{{ $user->status }}')">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-medium
                                        {{ $user->isAktif()
                                            ? 'bg-red-100 text-red-700 hover:bg-red-200'
                                            : 'bg-green-100 text-green-700 hover:bg-green-200' }}">

                                                    @if ($user->isAktif())
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                            fill="none">
                                                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                                                            <path d="M9 12l2 2l4 -4"></path>
                                                        </svg>
                                                        Nonaktifkan
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                            fill="none">
                                                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                                                            <path d="M10 10l4 4m0 -4l-4 4"></path>
                                                        </svg>
                                                        Aktifkan
                                                    @endif

                                                </button>

                                            </form>
                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada data pengguna</h3>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if ($users->hasPages())
                <div class="px-6 py-4">
                    {{ $users->withQueryString()->links() }}
                </div>
            @endif

        </div>
        {{-- Confirm Modal Component --}}
        <x-notifications.confirm-modal />
    </div>
@endsection

@push('page-scripts')
    <script>
        function confirmToggleStatus(form, name, status) {
            const action = status === 'aktif' ? 'menonaktifkan' : 'mengaktifkan';
            const type = status === 'aktif' ? 'danger' : 'info';
            const confirmText = status === 'aktif' ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan';

            const modalElement = document.querySelector('[x-data="confirmModal()"]');
            if (!modalElement) {
                console.error('Confirm modal not found!');
                return false;
            }

            const modal = Alpine.$data(modalElement);

            modal.show({
                title: 'Konfirmasi ' + (status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan') + ' Pengguna',
                message: `Apakah Anda yakin ingin ${action} akun pengguna "${name}"?`,
                confirmText: confirmText,
                cancelText: 'Batal',
                type: type,
                onConfirm: () => {
                    form.submit();
                }
            });

            return false; // Mencegah form submit langsung
        }

        $(document).ready(function() {
            $('.pagination a').on('click', function() {
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
            });
        });
    </script>
@endpush
