<?php

namespace App\Http\Requests\AsetLancar;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class AsetLancarRequest extends FormRequest
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
        return [
            'rekening_uraian_id' => 'required|exists:rekening_uraians,id',
            'nama_kegiatan' => 'nullable|string|max:255',
            'uraian_kegiatan' => 'nullable|string',
            'uraian_jenis_barang' => 'nullable|string',
            'saldo_awal_unit' => 'nullable|integer|min:0',
            'saldo_awal_harga_satuan' => 'nullable|numeric|min:0',
            'mutasi_tambah_unit' => 'nullable|integer|min:0',
            'mutasi_tambah_harga_satuan' => 'nullable|numeric|min:0',
            'mutasi_kurang_unit' => 'nullable|integer|min:0',
            'mutasi_kurang_nilai_total' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'rekening_uraian_id.required' => 'Rekening uraian harus dipilih.',
            'nama_kegiatan.required' => 'Nama kegiatan harus diisi.',
            'saldo_awal_unit.min' => 'Unit saldo awal tidak boleh negatif.',
            'saldo_awal_harga_satuan.min' => 'Harga satuan saldo awal tidak boleh negatif.',
            'mutasi_tambah_unit.min' => 'Unit mutasi tambah tidak boleh negatif.',
            'mutasi_tambah_harga_satuan.min' => 'Harga satuan mutasi tambah tidak boleh negatif.',
            'mutasi_kurang_unit.min' => 'Unit mutasi kurang tidak boleh negatif.',
            'mutasi_kurang_nilai_total.min' => 'Nilai total mutasi kurang tidak boleh negatif.',
        ];
    }

    /**
     * Configure the validator instance with additional business rules.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $this->validateBusinessRules($validator);
        });
    }

    /**
     * Custom business rules validation.
     */
    protected function validateBusinessRules(Validator $validator): void
    {
        $saldoAwalUnit = $this->input('saldo_awal_unit', 0);
        $saldoAwalHargaSatuan = $this->input('saldo_awal_harga_satuan', 0);
        $mutasiTambahUnit = $this->input('mutasi_tambah_unit', 0);
        $mutasiTambahHargaSatuan = $this->input('mutasi_tambah_harga_satuan', 0);

        // Validasi: harus ada harga satuan
        if ($saldoAwalHargaSatuan == 0 && $mutasiTambahHargaSatuan == 0) {
            $validator->errors()->add(
                'harga_satuan',
                'Harga satuan harus diisi, baik di Saldo Awal atau Mutasi Tambah.'
            );
        }

        // Validasi: jika ada unit saldo awal, harus ada harga satuan saldo awal
        if ($saldoAwalUnit > 0 && $saldoAwalHargaSatuan == 0) {
            $validator->errors()->add(
                'saldo_awal_harga_satuan',
                'Jika ada unit saldo awal, harga satuan saldo awal harus diisi.'
            );
        }

        // Validasi: jika ada unit mutasi tambah, harus ada harga satuan mutasi tambah
        if ($mutasiTambahUnit > 0 && $mutasiTambahHargaSatuan == 0) {
            $validator->errors()->add(
                'mutasi_tambah_harga_satuan',
                'Jika ada unit mutasi tambah, harga satuan mutasi tambah harus diisi.'
            );
        }
    }
}
