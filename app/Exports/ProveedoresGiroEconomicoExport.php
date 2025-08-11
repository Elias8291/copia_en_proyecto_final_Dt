<?php

namespace App\Exports;

use App\Models\Proveedor;
use App\Models\Actividad;
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

class ProveedoresGiroEconomicoExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, WithDrawings, WithEvents
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
            'tramites.actividades.actividad'
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
            'Actividades Económicas',
            'Sector Principal',
            'Fecha Alta',
            'Vigencia'
        ];
    }

    /**
     * @param mixed $proveedor
     * @return array
     */
    public function map($proveedor): array
    {
        $ultimoTramite = $proveedor->tramites->first();
        
        // Obtener actividades económicas
        $actividades = [];
        $sectorPrincipal = 'N/A';
        
        if ($ultimoTramite && $ultimoTramite->actividades->isNotEmpty()) {
            $actividades = $ultimoTramite->actividades->map(function($actividad) {
                return $actividad->actividad->nombre ?? 'Actividad no encontrada';
            });
            
            // Determinar sector principal basado en la primera actividad
            $primeraActividad = $ultimoTramite->actividades->first();
            if ($primeraActividad && $primeraActividad->actividad) {
                $codigo = $primeraActividad->actividad->codigo ?? '';
                
                // Clasificar por sector según código SCIAN
                if (strpos($codigo, '11') === 0) {
                    $sectorPrincipal = 'Agricultura, cría y explotación';
                } elseif (strpos($codigo, '21') === 0) {
                    $sectorPrincipal = 'Minería';
                } elseif (strpos($codigo, '22') === 0) {
                    $sectorPrincipal = 'Electricidad, agua y gas';
                } elseif (strpos($codigo, '23') === 0) {
                    $sectorPrincipal = 'Construcción';
                } elseif (strpos($codigo, '31') === 0 || strpos($codigo, '32') === 0 || strpos($codigo, '33') === 0) {
                    $sectorPrincipal = 'Industrias manufactureras';
                } elseif (strpos($codigo, '43') === 0) {
                    $sectorPrincipal = 'Comercio al por mayor';
                } elseif (strpos($codigo, '46') === 0) {
                    $sectorPrincipal = 'Comercio al por menor';
                } elseif (strpos($codigo, '48') === 0 || strpos($codigo, '49') === 0) {
                    $sectorPrincipal = 'Transportes y almacenes';
                } elseif (strpos($codigo, '51') === 0) {
                    $sectorPrincipal = 'Información en medios masivos';
                } elseif (strpos($codigo, '52') === 0) {
                    $sectorPrincipal = 'Servicios financieros';
                } elseif (strpos($codigo, '53') === 0) {
                    $sectorPrincipal = 'Servicios inmobiliarios';
                } elseif (strpos($codigo, '54') === 0) {
                    $sectorPrincipal = 'Servicios profesionales';
                } elseif (strpos($codigo, '56') === 0) {
                    $sectorPrincipal = 'Servicios de apoyo a negocios';
                } elseif (strpos($codigo, '61') === 0) {
                    $sectorPrincipal = 'Servicios educativos';
                } elseif (strpos($codigo, '62') === 0) {
                    $sectorPrincipal = 'Servicios de salud';
                } elseif (strpos($codigo, '71') === 0) {
                    $sectorPrincipal = 'Servicios de esparcimiento';
                } elseif (strpos($codigo, '72') === 0) {
                    $sectorPrincipal = 'Servicios de alojamiento';
                } elseif (strpos($codigo, '81') === 0) {
                    $sectorPrincipal = 'Otros servicios';
                } else {
                    $sectorPrincipal = 'Sector no clasificado';
                }
            }
        }

        $actividadesTexto = $actividades->isNotEmpty() ? $actividades->implode(', ') : 'Sin actividades registradas';

        return [
            $proveedor->id,
            $proveedor->razon_social ?? 'N/A',
            $proveedor->rfc ?? 'N/A',
            $proveedor->tipo_persona ?? 'N/A',
            $proveedor->estado_padron ?? 'Pendiente',
            $actividadesTexto,
            $sectorPrincipal,
            $proveedor->fecha_alta_padron ? Carbon::parse($proveedor->fecha_alta_padron)->format('d/m/Y') : 'N/A',
            $proveedor->fecha_vencimiento_padron ? Carbon::parse($proveedor->fecha_vencimiento_padron)->format('d/m/Y') : 'N/A'
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            'A:I' => [
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
            'H:H' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'I:I' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
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
            'F' => 35,  // Actividades Económicas
            'G' => 25,  // Sector Principal
            'H' => 12,  // Fecha Alta
            'I' => 12,  // Vigencia
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Proveedores por Giro';
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
                $sheet->setCellValue('D3', 'Proveedores por Giro Económico');
                $sheet->setCellValue('D4', 'Fecha de generación: ' . now()->format('d/m/Y H:i:s'));
                
                $sheet->mergeCells('D1:I1');
                $sheet->mergeCells('D2:I2'); 
                $sheet->mergeCells('D3:I3');
                $sheet->mergeCells('D4:I4');
                
                $sheet->getStyle('D1:I4')->applyFromArray([
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
                
                $sheet->getStyle('A6:I6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '000000']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A6:I' . $lastRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ]);
            },
        ];
    }
}
