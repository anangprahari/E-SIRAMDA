{{-- Footer - langsung full width, otomatis menyesuaikan margin parent --}}
<footer class="bg-white border-t border-gray-200 mt-auto">
    <div class="px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-gray-600">

            {{-- Left --}}
            <div class="flex items-center gap-1">
                <span>©</span>
                <span x-text="new Date().getFullYear()"></span>
                <span class="font-semibold text-gray-800">E-SIRAMDA</span>
            </div>

            {{-- Right --}}
            <div class="flex flex-col text-center sm:text-right">
                <span class="font-medium text-gray-700">
                    DINAS KOMUNIKASI DAN INFORMATIKA PROVINSI JAMBI
                </span>
                <span class="text-xs text-gray-500">
                    Sub Bagian Keuangan dan Aset
                </span>
            </div>

        </div>
    </div>
</footer>
