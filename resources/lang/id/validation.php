<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'accepted' => ':attribute harus diterima.',
    'active_url' => ':attribute bukan URL yang valid.',
    'after' => ':attribute harus tanggal setelah :date.',
    'after_or_equal' => ':attribute harus tanggal setelah atau sama dengan :date.',
    'alpha' => ':attribute hanya boleh berisi huruf.',
    'alpha_dash' => ':attribute hanya boleh berisi huruf, angka, strip, dan underscore.',
    'alpha_num' => ':attribute hanya boleh berisi huruf dan angka.',
    'array' => ':attribute harus berupa array.',
    'before' => ':attribute harus tanggal sebelum :date.',
    'before_or_equal' => ':attribute harus tanggal sebelum atau sama dengan :date.',
    'between' => [
        'numeric' => ':attribute harus antara :min sampai :max.',
        'file' => ':attribute harus antara :min sampai :max KB.',
        'string' => ':attribute harus antara :min sampai :max karakter.',
        'array' => ':attribute harus antara :min sampai :max item.',
    ],
    'boolean' => ':attribute harus bernilai true atau false.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'date' => ':attribute bukan tanggal yang valid.',
    'digits' => ':attribute harus terdiri dari :digits digit.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'exists' => ':attribute yang dipilih tidak valid.',
    'file' => ':attribute harus berupa file.',
    'filled' => ':attribute wajib diisi.',
    'image' => ':attribute harus berupa file gambar.',
    'in' => ':attribute yang dipilih tidak valid.',
    'integer' => ':attribute harus berupa bilangan bulat.',
    'max' => [
        'numeric' => ':attribute tidak boleh lebih dari :max.',
        'file' => 'Ukuran :attribute maksimal :max KB.',
        'string' => ':attribute maksimal :max karakter.',
        'array' => ':attribute maksimal :max item.',
    ],
    'min' => [
        'numeric' => ':attribute minimal :min.',
        'file' => 'Ukuran :attribute minimal :min KB.',
        'string' => ':attribute minimal :min karakter.',
        'array' => ':attribute minimal :min item.',
    ],
    'mimes' => ':attribute harus berformat :values.',
    'mimetypes' => ':attribute harus bertipe :values.',
    'numeric' => ':attribute harus berupa angka.',
    'required' => ':attribute wajib diisi.',
    'required_if' => ':attribute wajib diisi.',
    'string' => ':attribute harus berupa teks.',
    'unique' => ':attribute sudah digunakan.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'custom' => [

        // =============================
        // ASET TETAP
        // =============================
        'bukti_barang' => [
            'image' => 'Bukti barang harus berupa gambar.',
            'mimes' => 'Bukti barang harus berformat JPG, JPEG, PNG, atau GIF.',
            'max' => 'Ukuran file bukti barang maksimal 2 MB.',
        ],

        'bukti_berita' => [
            'mimes' => 'Bukti berita harus berupa file PDF.',
            'mimetypes' => 'Bukti berita harus berupa file PDF.',
            'max' => 'Ukuran file bukti berita maksimal 10 MB.',
        ],

        // =============================
        // MUTASI ASET
        // =============================
        'berita_acara' => [
            'required' => 'Berita acara wajib diunggah.',
            'mimes' => 'Berita acara harus berformat PDF.',
            'mimetypes' => 'Berita acara harus berformat PDF.',
            'max' => 'Ukuran berita acara maksimal 10 MB.',
        ],

        // =============================
        // USER & PROFIL
        // =============================
        'username' => [
            'unique' => 'Username sudah digunakan.',
            'alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip, dan underscore.',
        ],

        'email' => [
            'unique' => 'Email sudah digunakan.',
            'email' => 'Format email tidak valid.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'akun_id' => 'Akun',
        'kelompok_id' => 'Kelompok',
        'jenis_id' => 'Jenis',
        'objek_id' => 'Objek',
        'rincian_objek_id' => 'Rincian objek',
        'sub_rincian_objek_id' => 'Sub rincian objek',
        'sub_sub_rincian_objek_id' => 'Sub sub rincian objek',
        'nama_bidang_barang' => 'Nama bidang barang',
        'nama_jenis_barang' => 'Nama jenis barang',
        'register' => 'Register',
        'kode_barang' => 'Kode barang',
        'asal_perolehan' => 'Asal perolehan',
        'tahun_perolehan' => 'Tahun perolehan',
        'jumlah_barang' => 'Jumlah barang',
        'harga_satuan' => 'Harga satuan',
        'ruangan' => 'Ruangan',
        'berita_acara' => 'Berita acara',
        'bukti_barang' => 'Bukti barang',
        'bukti_berita' => 'Bukti berita',
    ],

];
