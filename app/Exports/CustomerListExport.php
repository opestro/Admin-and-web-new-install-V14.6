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

class CustomerListExport implements FromView, ShouldAutoSize, WithStyles,WithColumnWidths ,WithHeadings, WithEvents
{
    use Exportable;
    protected $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('file-exports.customer-list', [
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
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A4:H4')->getFont()->setBold(true)->getColor()
        ->setARGB('FFFFFF');

        $sheet->getStyle('A4:H4')->getFill()->applyFromArray([
            'fillType' => 'solid',
            'rotation' => 0,
            'color' => ['rgb' => '063C93'],
        ]);
        $sheet->getStyle('G5:H'.$this->data['customers']->count() + 4)->getFill()->applyFromArray([
            'fillType' => 'solid',
            'rotation' => 0,
            'color' => ['rgb' => 'FFF9D1'],
        ]);

        $sheet->setShowGridlines(false);
        return [
            // Define the style for cells with data
            'A1:H'.$this->data['customers']->count() + 4 => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'], // Specify the color of the border (optional)
                    ],
                ],
            ],
        ];
    }
    public function setImage($workSheet) {
        $this->data['customers']->each(function($item,$index) use($workSheet) {
            $drawing = new Drawing();
            $drawing->setName($item->f_name);
            $drawing->setDescription($item->f_name);
            $drawing->setPath(file_exists(storage_path('app/public/profile/'.$item->image))? storage_path('app/public/profile/'.$item->image) : public_path('assets/back-end/img/total-customer.png'));
            $drawing->setHeight(50);
            $drawing->setOffsetX(50);
            $drawing->setOffsetY(15);
            $drawing->setResizeProportional(true);
            $index+=5;
            $drawing->setCoordinates("B$index");
            $drawing->setWorksheet($workSheet);

        });
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:H1') // Adjust the range as per your needs
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle('A4:H'.$this->data['customers']->count() + 4) // Adjust the range as per your needs
                                        ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle('A2:H3') // Adjust the range as per your needs
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                    $event->sheet->mergeCells('A1:H1');
                    $event->sheet->mergeCells('A2:B2');
                    $event->sheet->mergeCells('C2:H2');
                    $event->sheet->mergeCells('A3:B3');
                    $event->sheet->mergeCells('C3:H3');
                    $event->sheet->mergeCells('D2:H2');
                    $event->sheet->getRowDimension(2)->setRowHeight(60);
                    $event->sheet->getRowDimension(1)->setRowHeight(30);
                    $event->sheet->getRowDimension(3)->setRowHeight(30);
                    $event->sheet->getRowDimension(4)->setRowHeight(30);
                    $event->sheet->getDefaultRowDimension()->setRowHeight(50);

                    $workSheet = $event->sheet->getDelegate();
                    $this->setImage($workSheet);
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
