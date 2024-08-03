<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductListExport implements FromView, ShouldAutoSize, WithStyles,WithColumnWidths ,WithHeadings, WithEvents
{
    use Exportable;
    protected $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('file-exports.product-list', [
            'data' => $this->data,
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'C' => 25,
            'D' => 25,
            'F' => 50,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'R' => 25,
        ];
    }

    public function styles(Worksheet $sheet) {
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A3:S3')->getFont()->setBold(true)->getColor()
        ->setARGB('FFFFFF');

        $sheet->getStyle('A3:S3')->getFill()->applyFromArray([
            'fillType' => 'solid',
            'rotation' => 0,
            'color' => ['rgb' => '063C93'],
        ]);

        $sheet->setShowGridlines(false);
        return [
            // Define the style for cells with data
            'A1:S'.$this->data['products']->count() + 3 => [
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
    public function setImage($workSheet) {
        $this->data['products']->each(function($item,$index) use($workSheet) {
            $drawing = new Drawing();
            $drawing->setName($item->name);
            $drawing->setDescription($item->name);
            $drawing->setPath(is_file(storage_path('app/public/product/thumbnail/'.$item->thumbnail))? storage_path('app/public/product/thumbnail/'.$item->thumbnail) : public_path('assets/back-end/img/products.png'));
            $drawing->setHeight(50);
            $drawing->setOffsetX(45);
            $drawing->setOffsetY(70);
            $drawing->setResizeProportional(true);
            $index+=4;
            $drawing->setCoordinates("B$index");
            $drawing->setWorksheet($workSheet);

        });
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:S1') // Adjust the range as per your needs
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle('A3:S'.$this->data['products']->count() + 3) // Adjust the range as per your needs
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle('A2:S2') // Adjust the range as per your needs
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                    $event->sheet->mergeCells('A1:S1');
                    $event->sheet->mergeCells('A2:B2');
                    $event->sheet->mergeCells('C2:S2');
                    $event->sheet->mergeCells('D2:S2');
                    if($this->data['type'] != 'seller'){
                        $event->sheet->mergeCells('F3:G3');
                        $this->data['products']->each(function($item,$index) use($event) {
                            $index+=4;
                            $event->sheet->mergeCells("F$index:G$index");
                        });
                    }
                    $event->sheet->getRowDimension(2)->setRowHeight(100);
                    $event->sheet->getRowDimension(1)->setRowHeight(30);
                    $event->sheet->getRowDimension(3)->setRowHeight(30);
                    $event->sheet->getDefaultRowDimension()->setRowHeight(150);

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
