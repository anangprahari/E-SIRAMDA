<?php

namespace App\Http\Requests\Aset;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Aset;
use Illuminate\Validation\Rule;

class UpdateAsetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $asetId = $this->route('aset')->id ?? null;

        return [
            'akun_id' => 'required|exists:akuns,id',
            'kelompok_id' => 'required|exists:kelompoks,id',
            'jenis_id' => 'required|exists:jenis,id',
            'objek_id' => 'required|exists:objeks,id',
            'rincian_objek_id' => 'required|exists:rincian_objeks,id',
            'sub_rincian_objek_id' => 'required|exists:sub_rincian_objeks,id',
            'sub_sub_rincian_objek_id' => 'required|exists:sub_sub_rincian_objeks,id',
            'nama_bidang_barang' => 'required|string|max:255',
            'register' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($asetId) {
                    // Untuk aset rusak berat, skip validasi uniqueness
                    if ($this->keadaan_barang === 'RB') {
                        return;
                    }

                    // Validasi unique untuk kombinasi kode_barang + register (exclude current record)
                    $query = Aset::where('kode_barang', $this->kode_barang)
                        ->where('register', $value);

                    if ($asetId) {
                        $query->where('id', '!=', $asetId);
                    }

                    if ($query->exists()) {
                        $fail('Kombinasi kode barang dan register sudah digunakan.');
                    }
                }
            ],
            'nama_jenis_barang' => 'required|string|max:255',
            'merk_type' => 'nullable|string|max:255',
            'no_sertifikat' => 'nullable|string|max:255',
            'no_plat_kendaraan' => 'nullable|string|max:255',
            'no_pabrik' => 'nullable|string|max:255',
            'no_casis' => 'nullable|string|max:255',
            'bahan' => 'nullable|string|max:255',
            'asal_perolehan' => 'required|string|max:255',
            'tahun_perolehan' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'ukuran_barang_konstruksi' => 'nullable|string|max:255',
            'satuan' => 'required|string|max:100',
            'keadaan_barang' => ['required', Rule::in(['B', 'KB', 'RB'])],
            'jumlah_barang' => 'required|integer|min:1|max:100',
            'harga_satuan' => 'required|numeric|min:0',
            'bukti_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bukti_berita' => 'nullable|mimes:pdf|max:10240',
            'lokasi_barang' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'ruangan' => [
                'nullable',
                Rule::in(config('ruangan')),
            ],
            'kode_barang' => 'required|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'akun_id.required' => 'Akun harus dipilih',
            'kelompok_id.required' => 'Kelompok harus dipilih',
            'jenis_id.required' => 'Jenis harus dipilih',
            'objek_id.required' => 'Objek harus dipilih',
            'rincian_objek_id.required' => 'Rincian objek harus dipilih',
            'sub_rincian_objek_id.required' => 'Sub rincian objek harus dipilih',
            'sub_sub_rincian_objek_id.required' => 'Sub sub rincian objek harus dipilih',
            'register.unique' => 'Register sudah digunakan',
            'kode_barang.unique' => 'Kode barang sudah digunakan',
            'tahun_perolehan.digits' => 'Tahun perolehan harus 4 digit',
            'tahun_perolehan.max' => 'Tahun perolehan tidak boleh melebihi tahun sekarang',
            'jumlah_barang.max' => 'Jumlah barang maksimal 100',
            'bukti_barang.max' => 'Ukuran file gambar maksimal 2MB',
            'bukti_berita.max' => 'Ukuran file PDF maksimal 10MB',
        ];
    }
}
