{{-- Toast Notification Component --}}
{{-- 
    Digunakan untuk:
    1. Session flash Laravel (success / error)
    2. Event JS: window.dispatchEvent(new CustomEvent('toast'))
--}}

<div x-data="toastNotification()" x-init="init()" x-show="show" x-transition
    class="fixed top-20 right-4 z-50 w-full max-w-sm" style="display: none;" role="alert">
    <div class="rounded-lg shadow-2xl overflow-hidden border"
        :class="{
            'bg-gradient-to-r from-green-50 to-emerald-50 border-green-200': type === 'success',
            'bg-gradient-to-r from-red-50 to-pink-50 border-red-200': type === 'error',
            'bg-gradient-to-r from-blue-50 to-sky-50 border-blue-200': type === 'info'
        }">
        <div class="p-4">
            <div class="flex items-start">
                {{-- Icon --}}
                <div class="flex-shrink-0">
                    <svg x-show="type === 'success'" class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                    <svg x-show="type === 'error'" class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                    </svg>

                    <svg x-show="type === 'info'" class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01" />
                    </svg>
                </div>

                {{-- Content --}}
                <div class="ml-3 flex-1">
                    <h4 class="text-sm font-bold text-gray-800" x-text="title"></h4>
                    <p class="text-sm mt-1 text-gray-700" x-text="message"></p>
                </div>

                {{-- Close --}}
                <button @click="close()" class="ml-3 text-gray-500 hover:text-gray-700">
                    ✕
                </button>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="h-1 w-full bg-gray-200">
            <div class="h-full"
                :class="{
                    'bg-green-500': type === 'success',
                    'bg-red-500': type === 'error',
                    'bg-blue-500': type === 'info'
                }"
                x-transition:leave="transition-all ease-linear duration-5000" x-transition:leave-start="w-full"
                x-transition:leave-end="w-0"></div>
        </div>
    </div>
</div>

<script>
    function toastNotification() {
        return {
            show: false,
            type: 'info',
            title: '',
            message: '',
            timeout: null,

            init() {
                // 🔔 Listener dari JavaScript
                window.addEventListener('toast', (e) => {
                    this.open(
                        e.detail.type || 'info',
                        e.detail.message || '',
                        e.detail.title || null
                    );
                });

                // 🔔 Session flash Laravel
                @if (session('success'))
                    this.open('success', @json(session('success')));
                @elseif (session('error'))
                    this.open('error', @json(session('error')));
                @endif
            },

            open(type, message, title = null) {
                this.type = type;
                this.message = message;
                this.title = title ?? this.getDefaultTitle(type);
                this.show = true;

                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => this.close(), 5000);
            },

            close() {
                this.show = false;
            },

            getDefaultTitle(type) {
                if (type === 'success') return 'Berhasil';
                if (type === 'error') return 'Terjadi Kesalahan';
                return 'Informasi';
            }
        };
    }
</script>
