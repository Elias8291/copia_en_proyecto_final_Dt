<?php

namespace App\Exports;

use App\Models\Proveedor;
use App\Models\Tramite;
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

class DashboardEjecutivoExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, WithDrawings, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Obtener métricas del dashboard
        $totalProveedores = Proveedor::count();
        $activos = Proveedor::where('estado_padron', 'Activo')->count();
        $vencidos = Proveedor::where('estado_padron', 'Vencido')->count();
        $pendientes = Proveedor::where('estado_padron', 'Pendiente')->count();
        $inactivos = Proveedor::where('estado_padron', 'Inactivo')->count();
        
        // Proveedores por vencer en 30 días
        $hoy = Carbon::now();
        $fechaLimite = $hoy->copy()->addDays(30);
        $porVencer30 = Proveedor::where('estado_padron', 'Activo')
            ->whereNotNull('fecha_vencimiento_padron')
            ->where('fecha_vencimiento_padron', '>=', $hoy)
            ->where('fecha_vencimiento_padron', '<=', $fechaLimite)
            ->count();
            
        // Trámites del último mes
        $tramitesUltimoMes = Tramite::where('created_at', '>=', Carbon::now()->subMonth())->count();
        
        return collect([
            ['metrica' => 'Total de Proveedores', 'valor' => $totalProveedores, 'porcentaje' => '100%'],
            ['metrica' => 'Proveedores Activos', 'valor' => $activos, 'porcentaje' => $totalProveedores > 0 ? round(($activos / $totalProveedores) * 100, 1) . '%' : '0%'],
            ['metrica' => 'Proveedores Vencidos', 'valor' => $vencidos, 'porcentaje' => $totalProveedores > 0 ? round(($vencidos / $totalProveedores) * 100, 1) . '%' : '0%'],
            ['metrica' => 'Proveedores Pendientes', 'valor' => $pendientes, 'porcentaje' => $totalProveedores > 0 ? round(($pendientes / $totalProveedores) * 100, 1) . '%' : '0%'],
            ['metrica' => 'Proveedores Inactivos', 'valor' => $inactivos, 'porcentaje' => $totalProveedores > 0 ? round(($inactivos / $totalProveedores) * 100, 1) . '%' : '0%'],
            ['metrica' => 'Por Vencer (30 días)', 'valor' => $porVencer30, 'porcentaje' => $activos > 0 ? round(($porVencer30 / $activos) * 100, 1) . '%' : '0%'],
            ['metrica' => 'Trámites Último Mes', 'valor' => $tramitesUltimoMes, 'porcentaje' => '-'],
        ]);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Métrica',
            'Valor',
            'Porcentaje'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row['metrica'],
            $row['valor'],
            $row['porcentaje']
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            'A:C' => [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'font' => [
                    'size' => 11,
                ],
            ],
            'B:B' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'C:C' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 15,
            'C' => 15,
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Dashboard Ejecutivo';
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
                
                // Insertar filas para el header
                $sheet->insertNewRowBefore(1, 5);
                
                // Agregar el texto del header
                $sheet->setCellValue('D1', 'GOBIERNO DEL ESTADO DE OAXACA');
                $sheet->setCellValue('D2', 'PADRÓN DE PROVEEDORES');
                $sheet->setCellValue('D3', 'Dashboard Ejecutivo - Métricas Generales');
                $sheet->setCellValue('D4', 'Fecha de generación: ' . now()->format('d/m/Y H:i:s'));
                
                // Combinar celdas para el título principal
                $sheet->mergeCells('D1:F1');
                $sheet->mergeCells('D2:F2'); 
                $sheet->mergeCells('D3:F3');
                $sheet->mergeCells('D4:F4');
                
                // Estilos para el header
                $sheet->getStyle('D1:F4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '000000'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                // Tamaños de fuente del header
                $sheet->getStyle('D1')->applyFromArray(['font' => ['size' => 16, 'bold' => true, 'color' => ['rgb' => '000000']]]);
                $sheet->getStyle('D2')->applyFromArray(['font' => ['size' => 14, 'bold' => true, 'color' => ['rgb' => '000000']]]);
                $sheet->getStyle('D3')->applyFromArray(['font' => ['size' => 12, 'color' => ['rgb' => '000000']]]);
                $sheet->getStyle('D4')->applyFromArray(['font' => ['size' => 10, 'color' => ['rgb' => '666666']]]);
                
                // Ajustar altura de las filas del header
                $sheet->getRowDimension('1')->setRowHeight(25);
                $sheet->getRowDimension('2')->setRowHeight(20);
                $sheet->getRowDimension('3')->setRowHeight(18);
                $sheet->getRowDimension('4')->setRowHeight(15);
                $sheet->getRowDimension('5')->setRowHeight(10);
                
                // Estilo para los encabezados de las columnas
                $sheet->getStyle('A6:C6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '000000']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                // Bordes para toda la tabla
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A6:C' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
