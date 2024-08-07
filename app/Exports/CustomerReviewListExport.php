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

class CustomerReviewListExport implements FromView, ShouldAutoSize, WithStyles,WithColumnWidths ,WithHeadings, WithEvents
{
    use Exportable;
    protected $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('file-exports.customer-review-list', [
            'data' => $this->data,
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 30,
            'G'=> 30
        ];
    }

    public function styles(Worksheet $sheet) {
        $sheet->getStyle('A1:A2')->getFont()->setBold(true);
        $sheet->getStyle('A3:G3')->getFont()->setBold(true)->getColor()
        ->setARGB('FFFFFF');

        $sheet->getStyle('A3:G3')->getFill()->applyFromArray([
            'fillType' => 'solid',
            'rotation' => 0,
            'color' => ['rgb' => '063C93'],
        ]);

        $sheet->setShowGridlines(false);

        return [
            // Define the style for cells with data
            'A1:G'.$this->data['reviews']->count() + 3 => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'], // Specify the color of the border (optional)
                    ],
                ],
                'alignment' => [
                    'wrapText' => true,
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
                $event->sheet->getStyle('A3:G'.$this->data['reviews']->count() + 3) // Adjust the range as per your needs
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle('A2:G2') // Adjust the range as per your needs
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                if(isset($this->data['data-from']) && $this->data['data-from'] == 'vendor'){
                    $event->sheet->mergeCells('F3:G3');
                    $this->data['reviews']->each(function($item,$index) use($event) {
                        $index+=4;
                        $event->sheet->mergeCells("F$index:G$index");
                    });
                }

                    $event->sheet->mergeCells('A1:G1');
                    $event->sheet->mergeCells('A2:B2');
                    $event->sheet->mergeCells('C2:G2');
                    $event->sheet->getRowDimension(2)->setRowHeight(100);
                    $event->sheet->getDefaultRowDimension()->setRowHeight(30);
                    $workSheet = $event->sheet->getDelegate();
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
