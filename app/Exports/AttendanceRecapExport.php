<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AttendanceRecapExport implements FromView, ShouldAutoSize, WithStyles, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.attendance_recap', [
            'students' => $this->data['students'],
            'dates' => $this->data['dates'],
            'classroom' => $this->data['classroom'],
            'subject' => $this->data['subject'],
            'startDate' => $this->data['startDate'],
            'endDate' => $this->data['endDate'],
        ]);
    }

    
    public function styles(Worksheet $sheet)
    {
        return [
            
            1 => ['font' => ['name' => 'Arial', 'size' => 11]],
        ];
    }

    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                
                $totalColumns = 2 + count($this->data['dates']) + 4; 
                
                
                $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalColumns);
                $lastRow = $sheet->getHighestRow();

                
                $sheet->mergeCells('A1:' . $lastColumn . '1'); 
                $sheet->mergeCells('A2:' . $lastColumn . '2'); 
                $sheet->mergeCells('A3:' . $lastColumn . '3'); 
                $sheet->mergeCells('A4:' . $lastColumn . '4'); 
                $sheet->mergeCells('A5:' . $lastColumn . '5'); 

                
                $sheet->getStyle('A1:A4')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                
                
                $sheet->getStyle('A3')->getFont()->setSize(14)->setBold(true);

                
                $sheet->getStyle('A8:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                
                $sheet->getStyle('A8:' . $lastColumn . '8')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'EFEFEF'],
                    ],
                ]);

                
                
                $sheet->getStyle('A9:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                
                $sheet->getStyle('C9:' . $lastColumn . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}