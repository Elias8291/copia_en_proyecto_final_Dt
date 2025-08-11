<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ProveedoresTrimestralesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithCustomStartCell, WithTitle
{
    protected $proveedores;
    protected $año;
    protected $trimestre;
    protected $fechas;

    public function __construct($proveedores, $año, $trimestre, $fechas)
    {
        $this->proveedores = $proveedores;
        $this->año = $año;
        $this->trimestre = $trimestre;
        $this->fechas = $fechas;
    }

    public function collection()
    {
        return $this->proveedores;
    }

    public function headings(): array
    {
        return [
            'No.',
            'Razón Social',
            'RFC',
            'Tipo de Persona',
            'Estado del Padrón',
            'Fecha de Registro',
            'Fecha de Vencimiento',
            'Estado Geográfico',
            'Municipio',
            'Actividades Económicas',
            'Sectores Económicos',
            'Estatus en Trimestre'
        ];
    }

    public function map($proveedor): array
    {
        static $contador = 0;
        $contador++;

        // Obtener último trámite y dirección
        $ultimoTramite = $proveedor->tramites->first();
        $direccion = $ultimoTramite ? $ultimoTramite->direcciones->first() : null;
        
        // Determinar estado en el trimestre
        $estatusEnTrimestre = $this->determinarEstatusEnTrimestre($proveedor);
        
        return [
            $contador,
            $proveedor->razon_social ?? 'N/A',
            $proveedor->rfc ?? 'N/A',
            $proveedor->tipo_persona ?? 'N/A',
            $proveedor->estado_padron ?? 'N/A',
            $proveedor->created_at ? $proveedor->created_at->format('d/m/Y') : 'N/A',
            $proveedor->fecha_vencimiento_padron ? $proveedor->fecha_vencimiento_padron->format('d/m/Y') : 'Sin fecha',
            $direccion && $direccion->estado ? $direccion->estado->nombre : 'N/A',
            $direccion ? ($direccion->municipio ?? 'N/A') : 'N/A',
            $proveedor->actividades->pluck('nombre')->implode(', ') ?: 'N/A',
            $proveedor->sectores->pluck('nombre')->implode(', ') ?: 'N/A',
            $estatusEnTrimestre
        ];
    }

    private function determinarEstatusEnTrimestre($proveedor)
    {
        $fechaInicio = $this->fechas['inicio'];
        $fechaFin = $this->fechas['fin'];
        $fechaCreacion = $proveedor->created_at->format('Y-m-d');
        $fechaVencimiento = $proveedor->fecha_vencimiento_padron ? $proveedor->fecha_vencimiento_padron->format('Y-m-d') : null;

        // Si fue creado durante el trimestre
        if ($fechaCreacion >= $fechaInicio && $fechaCreacion <= $fechaFin) {
            if (!$fechaVencimiento) {
                return 'Registrado en trimestre (Sin vencimiento)';
            } elseif ($fechaVencimiento >= $fechaInicio) {
                if ($fechaVencimiento <= $fechaFin) {
                    return 'Registrado y vencido en trimestre';
                } else {
                    return 'Registrado en trimestre (Activo)';
                }
            }
        }

        // Si ya existía antes del trimestre
        if ($fechaCreacion < $fechaInicio) {
            if (!$fechaVencimiento) {
                return 'Activo durante todo el trimestre (Sin vencimiento)';
            } elseif ($fechaVencimiento >= $fechaInicio && $fechaVencimiento <= $fechaFin) {
                return 'Vencido durante el trimestre';
            } elseif ($fechaVencimiento > $fechaFin) {
                return 'Activo durante todo el trimestre';
            } elseif ($fechaVencimiento < $fechaInicio) {
                return 'Ya vencido antes del trimestre';
            }
        }

        return 'Activo en trimestre';
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function title(): string
    {
        return "Trimestre {$this->trimestre} - {$this->año}";
    }

    public function styles(Worksheet $sheet)
    {
        // Título principal
        $sheet->setCellValue('A1', "REPORTE TRIMESTRAL DE PROVEEDORES");
        $sheet->setCellValue('A2', "Año: {$this->año} - Trimestre: {$this->trimestre}");
        $sheet->setCellValue('A3', "Período: {$this->fechas['inicio']} al {$this->fechas['fin']}");
        $sheet->setCellValue('A4', "Generado el: " . now()->format('d/m/Y H:i:s'));

        // Estilos del título
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '9d2449']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);

        $sheet->getStyle('A2:A4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => '666666']
            ]
        ]);

        // Combinar celdas del título
        $sheet->mergeCells('A1:L1');
        
        // Estilo de los encabezados
        $sheet->getStyle('A6:L6')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '9d2449']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Auto-ajustar columnas
        foreach (range('A', 'L') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Altura de las filas
        $sheet->getRowDimension(6)->setRowHeight(25);

        // Estilo para las filas de datos
        $lastRow = $sheet->getHighestRow();
        if ($lastRow > 6) {
            $sheet->getStyle("A7:L{$lastRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);

            // Alternar colores de filas
            for ($row = 7; $row <= $lastRow; $row++) {
                if (($row - 7) % 2 == 1) {
                    $sheet->getStyle("A{$row}:L{$row}")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F8F9FA']
                        ]
                    ]);
                }
            }
        }

        return [];
    }
}
