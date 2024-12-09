<?php

namespace App\Exports;

use App\Models\Laporan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; 
use Illuminate\Support\Facades\Storage;

class LaporanExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles, WithTitle, WithDrawings
{
    public function collection()
    {
        $laporans = Laporan::with('user')->get();
    
        return $laporans->map(function ($laporan) {
            return [
                'id' => $laporan->id,
                'username' => $laporan->user ? $laporan->user->username : '-',
                'image' => '',
                'title' => $laporan->title,
                'description' => $laporan->description,
                'lokasi_kejadian' => $laporan->lokasi_kejadian,
                'datetime' => $laporan->datetime,
                'status' => $laporan->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID', 'Username', 'Image','Jenis Kriminalitas', 'Description', 'Lokasi Kejadian', 'Datetime', 'Status'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 20,  
            'C' => 50,
            'D' => 20,
            'E' => 50,
            'F' => 20,
            'G' => 20,
            'H' => 20,
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        $laporans = Laporan::all();

        foreach ($laporans as $index => $laporan) {
            $row = $index + 2;
            $sheet->getRowDimension($row)->setRowHeight(90);
        }

        return [
            1    => ['font' => ['bold' => true]],
            'A1:H1' => [
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
        return 'Laporan Kriminalitas';
    }

    public function drawings()
    {
        $drawings = [];
        $laporans = Laporan::all();
    
        foreach ($laporans as $index => $laporan) {
            if ($laporan->image && Storage::exists('public/' . $laporan->image)) {
                $drawing = new Drawing();
                $drawing->setName('Image');
                $drawing->setDescription('Image from laporan');
                $drawing->setPath(Storage::path('public/' . $laporan->image));
                $drawing->setHeight(90);
    
                $row = $index + 2;
                $drawing->setCoordinates("C{$row}");
    
                $drawings[] = $drawing;
            }
        }
    
        return $drawings;
    }
}
