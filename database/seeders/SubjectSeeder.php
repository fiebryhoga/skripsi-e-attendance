<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        $subjects = [
            ['kode' => 'PAI', 'name' => 'Pendidikan Agama Islam'],
            ['kode' => 'PKN', 'name' => 'Pendidikan Kewarganegaraan'],
            ['kode' => 'BIN', 'name' => 'Bahasa Indonesia'],
            ['kode' => 'MTK-W', 'name' => 'Matematika Wajib'],
            ['kode' => 'SEJ-W', 'name' => 'Sejarah Indonesia'],
            ['kode' => 'ING', 'name' => 'Bahasa Inggris'],
            ['kode' => 'SBD', 'name' => 'Seni Budaya'],
            ['kode' => 'PJK', 'name' => 'Penjaskes'],
            ['kode' => 'PKW', 'name' => 'Prakarya dan Kewirausahaan'],
            ['kode' => 'MTK-P', 'name' => 'Matematika Peminatan'],
            ['kode' => 'BIO', 'name' => 'Biologi'],
            ['kode' => 'FIS', 'name' => 'Fisika'],
            ['kode' => 'KIM', 'name' => 'Kimia'],
            ['kode' => 'GEO', 'name' => 'Geografi'],
            ['kode' => 'SEJ-P', 'name' => 'Sejarah Peminatan'],
            ['kode' => 'SOS', 'name' => 'Sosiologi'],
            ['kode' => 'EKO', 'name' => 'Ekonomi'],
            ['kode' => 'BJW', 'name' => 'Bahasa Jawa'],
        ];

        foreach ($subjects as $sub) {
            Subject::firstOrCreate(['kode' => $sub['kode']], $sub);
        }
    }
}