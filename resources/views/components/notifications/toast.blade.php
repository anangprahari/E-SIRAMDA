{{-- Toast Notification Component --}}
{{-- 
    Komponen ini akan otomatis mendeteksi session flash messages (success/error)
    dan menampilkan toast notification di pojok kanan atas
--}}

@if (session('success') || session('error'))
    <div x-data="{
        show: true,
        type: '{{ session('success') ? 'success' : 'error' }}',
        message: '{{ session('success') ?? session('error') }}'
    }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
        x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed top-20 right-4 z-50 w-full max-w-sm" role="alert"
        style="display: none;">
        <div class="rounded-lg shadow-2xl overflow-hidden border"
            :class="{
                'bg-gradient-to-r from-green-50 to-emerald-50 border-green-200': type === 'success',
                'bg-gradient-to-r from-red-50 to-pink-50 border-red-200': type === 'error'
            }">
            <div class="p-4">
                <div class="flex items-start">
                    {{-- Icon --}}
                    <div class="flex-shrink-0">
                        <svg x-show="type === 'success'" class="w-6 h-6 text-green-600" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <svg x-show="type === 'error'" class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>

                    {{-- Content --}}
                    <div class="ml-3 flex-1">
                        <h4 class="text-sm font-bold"
                            :class="{
                                'text-green-800': type === 'success',
                                'text-red-800': type === 'error'
                            }">
                            <span x-show="type === 'success'">Berhasil!</span>
                            <span x-show="type === 'error'">Error!</span>
                        </h4>
                        <p class="text-sm mt-1"
                            :class="{
                                'text-green-700': type === 'success',
                                'text-red-700': type === 'error'
                            }"
                            x-text="message"></p>
                    </div>

                    {{-- Close Button --}}
                    <button @click="show = false" class="ml-3 flex-shrink-0 rounded-lg p-1.5 transition-colors"
                        :class="{
                            'text-green-600 hover:bg-green-100': type === 'success',
                            'text-red-600 hover:bg-red-100': type === 'error'
                        }">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="h-1 w-full overflow-hidden"
                :class="{
                    'bg-green-100': type === 'success',
                    'bg-red-100': type === 'error'
                }">
                <div x-show="show" class="h-full"
                    :class="{
                        'bg-green-500': type === 'success',
                        'bg-red-500': type === 'error'
                    }"
                    x-transition:leave="transition-all ease-linear duration-5000" x-transition:leave-start="w-full"
                    x-transition:leave-end="w-0"></div>
            </div>
        </div>
    </div>
@endif
