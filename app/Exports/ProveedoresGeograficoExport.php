<?php

namespace App\Exports;

use App\Models\Proveedor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;

class ProveedoresGeograficoExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, WithDrawings, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Proveedor::with([
            'tramites' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'tramites.direcciones.estado',
            'tramites.direcciones.coordenada'
        ])
        ->orderBy('estado_padron', 'asc')
        ->orderBy('razon_social', 'asc')
        ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Razón Social',
            'RFC',
            'Tipo Persona',
            'Estado Padrón',
            'Estado',
            'Municipio',
            'Localidad',
            'Código Postal',
            'Latitud',
            'Longitud'
        ];
    }

    /**
     * @param mixed $proveedor
     * @return array
     */
    public function map($proveedor): array
    {
        $ultimoTramite = $proveedor->tramites->first();
        $direccion = $ultimoTramite ? $ultimoTramite->direcciones->first() : null;
        $coordenada = $direccion ? $direccion->coordenada : null;

        return [
            $proveedor->id,
            $proveedor->razon_social ?? 'N/A',
            $proveedor->rfc ?? 'N/A',
            $proveedor->tipo_persona ?? 'N/A',
            $proveedor->estado_padron ?? 'Pendiente',
            $direccion && $direccion->estado ? $direccion->estado->nombre : 'N/A',
            $direccion->municipio ?? 'N/A',
            $direccion->localidad ?? 'N/A',
            $direccion->codigo_postal ?? 'N/A',
            $coordenada->latitud ?? 'N/A',
            $coordenada->longitud ?? 'N/A'
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            'A:K' => [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'font' => [
                    'size' => 10,
                ],
            ],
            'A:A' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'D:D' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'E:E' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'I:I' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'J:J' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'K:K' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 25,  // Razón Social
            'C' => 15,  // RFC
            'D' => 12,  // Tipo Persona
            'E' => 12,  // Estado Padrón
            'F' => 15,  // Estado
            'G' => 20,  // Municipio
            'H' => 20,  // Localidad
            'I' => 12,  // Código Postal
            'J' => 12,  // Latitud
            'K' => 12,  // Longitud
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Distribución Geográfica';
    }

    /**
     * @return array
     */
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo Gobierno de Oaxaca');
        $drawing->setPath(public_path('images/logoColor.png'));
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(20);
        $drawing->setOffsetY(-75);

        return $drawing;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                $sheet->insertNewRowBefore(1, 5);
                
                $sheet->setCellValue('D1', 'GOBIERNO DEL ESTADO DE OAXACA');
                $sheet->setCellValue('D2', 'PADRÓN DE PROVEEDORES');
                $sheet->setCellValue('D3', 'Distribución Geográfica de Proveedores');
                $sheet->setCellValue('D4', 'Fecha de generación: ' . now()->format('d/m/Y H:i:s'));
                
                $sheet->mergeCells('D1:K1');
                $sheet->mergeCells('D2:K2'); 
                $sheet->mergeCells('D3:K3');
                $sheet->mergeCells('D4:K4');
                
                $sheet->getStyle('D1:K4')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                
                $sheet->getStyle('D1')->applyFromArray(['font' => ['size' => 16, 'bold' => true, 'color' => ['rgb' => '000000']]]);
                $sheet->getStyle('D2')->applyFromArray(['font' => ['size' => 14, 'bold' => true, 'color' => ['rgb' => '000000']]]);
                $sheet->getStyle('D3')->applyFromArray(['font' => ['size' => 12, 'color' => ['rgb' => '000000']]]);
                $sheet->getStyle('D4')->applyFromArray(['font' => ['size' => 10, 'color' => ['rgb' => '666666']]]);
                
                $sheet->getRowDimension('1')->setRowHeight(25);
                $sheet->getRowDimension('2')->setRowHeight(20);
                $sheet->getRowDimension('3')->setRowHeight(18);
                $sheet->getRowDimension('4')->setRowHeight(15);
                $sheet->getRowDimension('5')->setRowHeight(10);
                
                $sheet->getStyle('A6:K6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '000000']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A6:K' . $lastRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ]);
            },
        ];
    }
}
