<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;

use App\Models\Packinglist;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class InstoreReportExport implements FromView, ShouldAutoSize, WithEvents, WithStyles
{
    public function view(): View
    { 
        $packinglist = Packinglist::find();
        return view('pages.fabric-status.instore-report',[
            'packinglists' => Packinglist::all()
        ]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastColumn = $event->sheet->getHighestColumn();
                $lastRow = $event->sheet->getHighestRow();

                $range = 'A1:' . $lastColumn . $lastRow;

                $event->sheet->getStyle($range)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '#000000'],
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()
                                ->getStyle('A1:Q1', 'A2:Q2')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                                ->setVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            }
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')->getFont()->setBold(true);
    }
}