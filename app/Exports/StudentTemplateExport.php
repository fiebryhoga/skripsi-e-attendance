<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class StudentTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    /**
     * Data Dummy sebagai contoh pengisian
     */
    public function array(): array
    {
        return [
            [
                '2024001',          
                'CONTOH 1 (SILAHKAN DIHAPUS/GANTI)', // Nama diubah menjadi peringatan
                'X-A',              // Kelas diubah sesuai request
                'L',                
                'Islam',            
                '0051234567',       
                '2024',             
                '081234567890'      
            ],
            [
                '2024002',
                'CONTOH 2 (SILAHKAN DIHAPUS/GANTI)', // Nama diubah menjadi peringatan
                'XI-J',             // Kelas diubah sesuai request
                'P',
                'Islam',
                '0059876543',
                '2024',
                '085678901234'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'NIS',
            'NAMA LENGKAP',
            'NAMA KELAS',
            'JENIS KELAMIN (L/P)',
            'AGAMA',
            'NISN',
            'ANGKATAN',
            'NOMOR ORTU',
        ];
    }

    /**
     * Styling Sederhana
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    /**
     * Styling Lanjutan (Warna, Border, Alignment)
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = 'H'; 
                $lastRow = $sheet->getHighestRow();

                // Styling Header (Baris 1)
                $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
                    'font' => [
                        'color' => ['argb' => 'FFFFFFFF'], 
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF4F46E5'], 
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFFFFFFF'],
                        ],
                    ],
                ]);

                // Set tinggi baris header
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Styling Body (Baris 2 sampai akhir)
                $sheet->getStyle('A2:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFCCCCCC'], 
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                // Set tinggi baris data
                for ($i = 2; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(25);
                }
            },
        ];
    }
}