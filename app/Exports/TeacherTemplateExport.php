<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths; 
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TeacherTemplateExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{

    public function collection()
    {
        return collect([
            [
                '198001012010011001',       
                'Contoh Guru (Hapus Ini)',  
                'contoh@sekolah.sch.id',    
                '081234567890',             
                'password123',              
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

    public function columnWidths(): array
    {
        return [
            'A' => 25, 
            'B' => 40, 
            'C' => 35, 
            'D' => 20, 
            'E' => 15, 
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4F46E5'], 
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            
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
                
                
                $sheet->getStyle('A:A')->getNumberFormat()->setFormatCode('@');
                $sheet->getStyle('D:D')->getNumberFormat()->setFormatCode('@');
                
                
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