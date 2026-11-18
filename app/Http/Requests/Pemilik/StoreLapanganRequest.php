<?php

namespace App\Http\Requests\Pemilik;

use Illuminate\Foundation\Http\FormRequest;

class StoreLapanganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_lapangan' => ['required', 'string', 'max:100'],
            'ukuran' => ['nullable', 'string', 'max:50'],
            'jenis_lantai' => ['nullable', 'string', 'max:100'],
            'harga_per_jam' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],
            'deskripsi' => ['nullable', 'string'],
            'gambar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lapangan.required' => 'Nama lapangan wajib diisi.',
            'nama_lapangan.max' => 'Nama lapangan maksimal 100 karakter.',
            'ukuran.max' => 'Ukuran lapangan maksimal 50 karakter.',
            'jenis_lantai.max' => 'Jenis lantai maksimal 100 karakter.',
            'harga_per_jam.required' => 'Harga sewa wajib diisi.',
            'harga_per_jam.numeric' => 'Harga sewa harus berupa angka.',
            'harga_per_jam.min' => 'Harga sewa tidak boleh negatif.',
            'harga_per_jam.max' => 'Harga sewa melebihi batas maksimal.',
            'gambar.image' => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WebP.',
            'gambar.max' => 'Ukuran gambar maksimal 2 MB.',
        ];
    }
}
