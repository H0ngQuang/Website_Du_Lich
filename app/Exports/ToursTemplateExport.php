<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ToursTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function headings(): array
    {
        return [
            'ten_tour',
            'diem_den',
            'khu_vuc',
            'so_luong',
            'gia_nguoi_lon',
            'gia_tre_em',
            'ngay_bat_dau',
            'ngay_ket_thuc',
            'mo_ta',
        ];
    }

    public function array(): array
    {
        // 1 dòng dữ liệu mẫu
        return [
            [
                'Tour Hà Nội - Sapa 3 ngày',
                'Sapa, Lào Cai',
                'b',
                30,
                2500000,
                1500000,
                '01/04/2026',
                '03/04/2026',
                'Tour tham quan Sapa với cảnh đẹp núi rừng Tây Bắc',
            ],
            [
                'Tour Đà Nẵng - Hội An',
                'Đà Nẵng, Hội An',
                't',
                25,
                3000000,
                1800000,
                '10/04/2026',
                '13/04/2026',
                'Tour khám phá Đà Nẵng và phố cổ Hội An',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style header row: bold, background color
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2E86C1'],
            ],
        ]);

        // Thêm comment hướng dẫn cho cột khu_vuc
        $sheet->getComment('C1')->getText()->createTextRun('b = Miền Bắc, t = Miền Trung, n = Miền Nam');
        $sheet->getComment('G1')->getText()->createTextRun('Định dạng: dd/mm/yyyy');
        $sheet->getComment('H1')->getText()->createTextRun('Định dạng: dd/mm/yyyy');

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 20,
            'C' => 12,
            'D' => 12,
            'E' => 15,
            'F' => 15,
            'G' => 18,
            'H' => 18,
            'I' => 45,
        ];
    }
}
