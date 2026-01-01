<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Classroom;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        
        
        
        $classroom = Classroom::where('name', $row['nama_kelas'])->first();

        
        
        
        
        return new Student([
            'nis'           => $row['nis'],
            'name'          => $row['nama_lengkap'], 
            'gender'        => $this->mapGender($row['jenis_kelamin_lp'] ?? $row['jenis_kelamin']), 
            'religion'      => $row['agama'],
            'nisn'          => $row['nisn'],
            'angkatan'      => $row['angkatan'],
            'phone_parent'  => $row['nomor_ortu'], 
            'classroom_id'  => $classroom ? $classroom->id : null, 
            'photo'         => null,
        ]);
    }

    private function mapGender($value)
    {
        if (!$value) return null;
        
        $val = strtoupper(substr($value, 0, 1)); 
        return ($val == 'L' || $val == 'P') ? $val : null;
    }

    public function rules(): array
    {
        return [
            
            'nis' => 'required|unique:students,nis',
            'nama_lengkap' => 'required',
            'angkatan' => 'required',
            
        ];
    }
}