<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; 
use Illuminate\Support\Facades\Storage;

class ReportExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $reports = Report::with('user', 'laporan')->get();
    
        return $reports->map(function ($report) {
            return [
                'id' => $report->id,
                'username' => $report->user ? $report->user->username : '-',
                'title' => $report->laporan ? $report->laporan->title : '-',
                'description' => $report->description,
                'datetime' => $report->datetime,
                'status' => $report->laporan ? $report->laporan->status : '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID', 'Username', 'Jenis Kriminalitas', 'Description', 'Datetime', 'Status'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 20,  
            'C' => 20,
            'D' => 50,
            'E' => 20,
            'E' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $reports = Report::all();

        foreach ($reports as $index => $report) {
            $row = $index + 2;
            $sheet->getRowDimension($row)->setRowHeight(90);
        }

        return [
            1    => ['font' => ['bold' => true]],
            'A1:F1' => [
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                ],
                'font' => [
                    'color' => ['argb' => 'FFFFFF'],
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['argb' => '007bff'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Report Kriminalitas';
    }
}
