<?php

namespace App\Imports;

use App\Models\User;
use App\Enums\UserRole; // <--- 1. WAJIB IMPORT INI
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow; 
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class TeachersImport implements ToModel, WithStartRow, WithValidation, SkipsEmptyRows
{
    public function startRow(): int
    {
        return 2; 
    }

    public function model(array $row)
    {
        if (!isset($row[0]) || trim($row[0]) == '') {
            return null;
        }

        return new User([
            'nip'      => trim($row[0]),
            'name'     => $row[1],
            'email'    => $row[2],
            'phone'    => $this->formatPhone($row[3]),
            'password' => Hash::make($row[4]),
            
            // --- PERBAIKAN DI SINI ---
            // Jangan pakai string 'teacher', tapi pakai Enum
            // Kita bungkus dalam array [] karena kolom di database tipe JSON
            'roles'    => [UserRole::GURU_MAPEL->value], 
        ]);
    }

    public function rules(): array
    {
        return [
            '0' => 'required|unique:users,nip', 
            '1' => 'required', 
            '2' => 'required|email', 
            '3' => 'required', 
            '4' => 'required|min:8', 
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '0' => 'NIP',
            '1' => 'Nama Lengkap',
            '2' => 'Email',
            '3' => 'No. WhatsApp',
            '4' => 'Password',
        ];
    }

    private function formatPhone($phone)
    {
        if(empty($phone)) return null;
        return preg_replace('/[^0-9]/', '', $phone);
    }
}