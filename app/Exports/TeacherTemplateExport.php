<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths; // <--- Untuk lebar kolom
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TeacherTemplateExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    /**
     * Isi Baris ke-2 dengan CONTOH DATA
     */
    public function collection()
    {
        return collect([
            [
                '198001012010011001',       // NIP
                'Contoh Guru (Hapus Ini)',  // Nama
                'contoh@sekolah.sch.id',    // Email
                '081234567890',             // HP
                'password123',              // Password
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'NIP (Wajib)', 
            'Nama Lengkap',
            'Email',
            'No. WhatsApp',
            'Password',
        ];
    }

    /**
     * Atur Lebar Kolom Manual
     */
    public function columnWidths(): array
    {
        return [
            'A' => 25, // NIP
            'B' => 40, // Nama Lengkap (Lebih Lebar)
            'C' => 35, // Email (Lebih Lebar)
            'D' => 20, // No HP
            'E' => 15, // Password
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style Baris 1 (Header): Background Biru, Teks Putih
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4F46E5'], 
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            // Style Baris 2 (Contoh Data): Warna Abu-abu & Miring (Supaya user sadar ini contoh)
            2 => [
                'font' => ['italic' => true, 'color' => ['argb' => 'FF555555']],
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                // Format Kolom NIP (A) dan HP (D) jadi TEXT agar angka 0 aman
                $sheet->getStyle('A:A')->getNumberFormat()->setFormatCode('@');
                $sheet->getStyle('D:D')->getNumberFormat()->setFormatCode('@');
                
                // Tambahkan Border Tipis ke seluruh sel yang ada datanya
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}