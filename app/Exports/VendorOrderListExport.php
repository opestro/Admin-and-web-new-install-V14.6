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
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class VendorOrderListExport implements FromView, ShouldAutoSize, WithStyles,WithColumnWidths ,WithHeadings, WithEvents
{
    use Exportable;
    protected $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('file-exports.vendor-order-list', [
            'data' => $this->data,
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
        ];
    }

    public function styles(Worksheet $sheet) {
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A4:G4')->getFont()->setBold(true)->getColor()
            ->setARGB('FFFFFF');

        $sheet->getStyle('A4:G4')->getFill()->applyFromArray([
            'fillType' => 'solid',
            'rotation' => 0,
            'color' => ['rgb' => '063C93'],
        ]);

        $sheet->getStyle('G5:G'.$this->data['orders']->count() + 4)->getFill()->applyFromArray([
            'fillType' => 'solid',
            'rotation' => 0,
            'color' => ['rgb' => 'D6BC00'],
        ]);

        $sheet->setShowGridlines(false);

        return [
            'A1:G'.$this->data['orders']->count() + 4 => [
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
                $event->sheet->getStyle('A1:G1') // Adjust the range as per your needs
                ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle('A4:G'.$this->data['orders']->count() + 4) // Adjust the range as per your needs
                ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle('A2:G3') // Adjust the range as per your needs
                ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->mergeCells('A1:G1');
                $event->sheet->mergeCells('A2:B2');
                $event->sheet->mergeCells('C2:G2');
                $event->sheet->mergeCells('A3:B3');
                $event->sheet->mergeCells('C3:G3');
                $event->sheet->mergeCells('D2:G2');
                $event->sheet->getRowDimension(2)->setRowHeight(110);
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
