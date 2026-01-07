{{-- Confirm Modal Component --}}
{{--
    Komponen modal konfirmasi yang reusable
    Menggunakan Alpine.js untuk state management
    
    Props yang bisa dikustomisasi melalui Alpine.js:
    - title: Judul modal
    - message: Pesan konfirmasi
    - confirmText: Teks tombol konfirmasi
    - cancelText: Teks tombol batal
    - type: 'danger' | 'warning' | 'info'
--}}

<div x-data="confirmModal()" x-cloak>
    {{-- Modal Backdrop --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @click.self="close()">
        {{-- Modal Content --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden" @click.away="close()">
            <div class="p-6">
                {{-- Icon & Title --}}
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center"
                        :class="{
                            'bg-red-100': type === 'danger',
                            'bg-yellow-100': type === 'warning',
                            'bg-blue-100': type === 'info'
                        }">
                        {{-- Danger Icon --}}
                        <svg x-show="type === 'danger'" class="w-6 h-6 text-red-600" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>

                        {{-- Warning Icon --}}
                        <svg x-show="type === 'warning'" class="w-6 h-6 text-yellow-600" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>

                        {{-- Info Icon --}}
                        <svg x-show="type === 'info'" class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>

                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-bold text-gray-900" x-text="title"></h3>
                        <p class="mt-2 text-sm text-gray-600" x-text="message"></p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                <button @click="close()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                    x-text="cancelText"></button>

                <button @click="confirm()"
                    class="px-4 py-2 text-sm font-medium text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors"
                    :class="{
                        'bg-red-600 hover:bg-red-700 focus:ring-red-500': type === 'danger',
                        'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500': type === 'warning',
                        'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500': type === 'info'
                    }"
                    x-text="confirmText"></button>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmModal() {
        return {
            open: false,
            title: 'Konfirmasi',
            message: 'Apakah Anda yakin?',
            confirmText: 'Ya, Lanjutkan',
            cancelText: 'Batal',
            type: 'warning', // 'danger', 'warning', 'info'
            onConfirm: null,

            show(options = {}) {
                this.title = options.title || 'Konfirmasi';
                this.message = options.message || 'Apakah Anda yakin?';
                this.confirmText = options.confirmText || 'Ya, Lanjutkan';
                this.cancelText = options.cancelText || 'Batal';
                this.type = options.type || 'warning';
                this.onConfirm = options.onConfirm || null;
                this.open = true;
            },

            close() {
                this.open = false;
            },

            confirm() {
                if (this.onConfirm && typeof this.onConfirm === 'function') {
                    this.onConfirm();
                }
                this.close();
            }
        }
    }
</script>
