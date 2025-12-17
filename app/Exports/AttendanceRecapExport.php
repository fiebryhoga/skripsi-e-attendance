<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents; // Untuk Merge Cells
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

    // Styling CSS-like untuk Excel
    public function styles(Worksheet $sheet)
    {
        return [
            // Style default untuk seluruh sheet (Font Arial 11)
            1 => ['font' => ['name' => 'Arial', 'size' => 11]],
        ];
    }

    // Event untuk melakukan Merge Cells dan Border yang kompleks
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Hitung Total Kolom (No + Nama + Jumlah Tanggal + 4 Total)
                $totalColumns = 2 + count($this->data['dates']) + 4; 
                
                // Konversi angka ke Huruf Kolom (Misal: 10 -> J)
                $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalColumns);
                $lastRow = $sheet->getHighestRow();

                // 1. MERGE HEADER (JUDUL)
                $sheet->mergeCells('A1:' . $lastColumn . '1'); // PEMERINTAH...
                $sheet->mergeCells('A2:' . $lastColumn . '2'); // DINAS PENDIDIKAN...
                $sheet->mergeCells('A3:' . $lastColumn . '3'); // SMA NEGERI 1 MALANG...
                $sheet->mergeCells('A4:' . $lastColumn . '4'); // JUDUL LAPORAN
                $sheet->mergeCells('A5:' . $lastColumn . '5'); // SPASI

                // Style Header Utama
                $sheet->getStyle('A1:A4')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                
                // Style Header SMA NEGERI 1 MALANG (Lebih Besar)
                $sheet->getStyle('A3')->getFont()->setSize(14)->setBold(true);

                // 2. BORDER UNTUK TABEL (Mulai baris 8 sampai akhir)
                $sheet->getStyle('A8:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // 3. HEADER TABEL (BOLD & CENTER & WARNA ABU)
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

                // 4. ALIGNMENT ISI TABEL
                // Kolom No (A) Center
                $sheet->getStyle('A9:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Kolom Nilai (Mulai dari C sampai Akhir) Center
                $sheet->getStyle('C9:' . $lastColumn . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}