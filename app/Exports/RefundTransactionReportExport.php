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
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RefundTransactionReportExport implements FromView, ShouldAutoSize, WithStyles,WithColumnWidths ,WithHeadings, WithEvents
{
    use Exportable;
    protected $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('file-exports.refund-transaction-report', [
            'data' => $this->data,
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 30,
            'C' => 40,
        ];
    }

    public function styles(Worksheet $sheet) {
        $sheet->getStyle('A1:A2')->getFont()->setBold(true);
        $sheet->getStyle('A3:k3')->getFont()->setBold(true)->getColor()
            ->setARGB('FFFFFF');


        $sheet->getStyle('A3:K3')->getFill()->applyFromArray([
            'fillType' => 'solid',
            'rotation' => 0,
            'color' => ['rgb' => '063C93'],
        ]);
        $sheet->getStyle('K4:K'.$this->data['transactions']->count() + 3)->getFill()->applyFromArray([
            'fillType' => 'solid',
            'rotation' => 0,
            'color' => ['rgb' => 'FFF9D1'],
        ]);

        $sheet->setShowGridlines(false);
        return [
            // Define the style for cells with data
            'A1:K'.$this->data['transactions']->count() + 3 => [
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
        $this->data['transactions']->each(function($item,$index) use($workSheet) {
            $tempImagePath = null;
            $filePath = 'product/thumbnail/'.$item?->orderDetails?->product?->thumbnail_full_url['key'];
            $fileCheck = fileCheck(disk:'public',path: $filePath);
            if($item?->orderDetails?->product?->thumbnail_full_url['path'] && !$fileCheck){
                $tempImagePath = getTemporaryImageForExport($item?->orderDetails?->product?->thumbnail_full_url['path']);
                $imagePath = getImageForExport($item?->orderDetails?->product?->thumbnail_full_url['path']);
                $drawing = new MemoryDrawing();
                $drawing->setImageResource($imagePath);
            }else{
                $drawing = new Drawing();
                $drawing->setPath(is_file(storage_path('app/public/'.$filePath)) ? storage_path('app/public/'.$filePath) : public_path('assets/back-end/img/products.png'));
            }
            $drawing->setName($item?->orderDetails?->product?->name ?? translate('product_not_found'));
            $drawing->setDescription($item?->orderDetails?->product?->name ?? translate('product_not_found'));
            $drawing->setHeight(50);
            $drawing->setOffsetX(100);
            $drawing->setOffsetY(25);
            $drawing->setResizeProportional(true);
            $index+=4;
            $drawing->setCoordinates("B$index");
            $drawing->setWorksheet($workSheet);
            if($tempImagePath){
                imagedestroy($tempImagePath);
            }
        });
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:K1') // Adjust the range as per your needs
                ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle('A3:K'.$this->data['transactions']->count() + 3) // Adjust the range as per your needs
                ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle('A2:K2') // Adjust the range as per your needs
                ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->mergeCells('A1:k1');
                $event->sheet->mergeCells('A2:B2');
                $event->sheet->mergeCells('C2:K2');
                $event->sheet->getRowDimension(2)->setRowHeight(80);
                $event->sheet->getDefaultRowDimension()->setRowHeight(40);

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
