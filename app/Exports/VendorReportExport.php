<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class VendorReportExport implements FromView, ShouldAutoSize, WithStyles,WithColumnWidths ,WithHeadings, WithEvents
{
    use Exportable;
    protected $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('file-exports.vendor-report', [
            'data' => $this->data,
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 30,
        ];
    }

    public function styles(Worksheet $sheet) {
        $sheet->getStyle('A1:A2')->getFont()->setBold(true);
        $sheet->getStyle('A3:E3')->getFont()->setBold(true)->getColor()
            ->setARGB('FFFFFF');


        $sheet->getStyle('A3:E3')->getFill()->applyFromArray([
            'fillType' => 'solid',
            'rotation' => 0,
            'color' => ['rgb' => '063C93'],
        ]);
        $sheet->getStyle('E4:E'.$this->data['orders']->count() + 3)->getFill()->applyFromArray([
            'fillType' => 'solid',
            'rotation' => 0,
            'color' => ['rgb' => 'FFF9D1'],
        ]);

        $sheet->setShowGridlines(false);
        return [
            // Define the style for cells with data
            'A1:E'.$this->data['orders']->count() + 3 => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'], // Specify the color of the border (optional)
                    ],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:E1') // Adjust the range as per your needs
                ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle('A3:E'.$this->data['orders']->count() + 3) // Adjust the range as per your needs
                ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle('A2:E2') // Adjust the range as per your needs
                ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->mergeCells('A1:E1');
                $event->sheet->mergeCells('A2:B2');
                $event->sheet->mergeCells('C2:E2');
                if ($this->data['vendor'] != 'all'){
                    $event->sheet->mergeCells('C2:E2');
                }
                if ($this->data['vendor'] != 'all'){
                    $event->sheet->mergeCells('D3:E3');
                    $this->data['orders']->each(function($item,$index) use($event) {
                        $index+=4;
                        $event->sheet->mergeCells("D$index:E$index");
                    });
                }
                $event->sheet->getRowDimension(2)->setRowHeight(80);
                $event->sheet->getDefaultRowDimension()->setRowHeight(30);
            },
        ];
    }
    public function headings(): array
    {
        return [
            '1'
        ];
    }
}
