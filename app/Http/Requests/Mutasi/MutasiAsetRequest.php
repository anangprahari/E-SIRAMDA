<?php

namespace App\Http\Requests\Mutasi;

use App\Models\MutasiAset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class MutasiAsetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'aset_id' => 'required|exists:asets,id',
            'ruangan_tujuan' => [
                'required',
                'string',
                Rule::in(config('ruangan')),
            ],
            'lokasi_tujuan' => 'nullable|string|max:255',
            'tanggal_mutasi' => 'required|date|before_or_equal:today',
            'nomor_surat' => 'required|string|max:100|unique:mutasi_asets,nomor_surat',
            'berita_acara' => 'required|file|mimes:pdf|max:10240',
            'keterangan' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'aset_id.required' => 'Aset harus dipilih.',
            'aset_id.exists' => 'Aset tidak ditemukan.',
            'ruangan_tujuan.required' => 'Ruangan tujuan harus dipilih.',
            'ruangan_tujuan.in' => 'Ruangan tujuan tidak valid.',
            'lokasi_tujuan.max' => 'Lokasi tujuan maksimal 255 karakter.',
            'tanggal_mutasi.required' => 'Tanggal mutasi harus diisi.',
            'tanggal_mutasi.before_or_equal' => 'Tanggal mutasi tidak boleh melebihi hari ini.',
            'nomor_surat.required' => 'Nomor surat harus diisi.',
            'nomor_surat.unique' => 'Nomor surat sudah digunakan.',
            'berita_acara.required' => 'Berita acara harus diupload.',
            'berita_acara.mimes' => 'Berita acara harus berformat PDF.',
            'berita_acara.max' => 'Ukuran berita acara maksimal 10MB.',
        ];
    }
}
