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

class ReporteTrimestralExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, WithDrawings, WithEvents
{
    protected $año;
    protected $trimestre;
    protected $fechaInicio;
    protected $fechaFin;

    public function __construct($año, $trimestre)
    {
        $this->año = (int) $año;
        $this->trimestre = (int) $trimestre;
        
        // Calcular fechas del trimestre
        $this->calculateTrimesterDates();
    }

    private function calculateTrimesterDates()
    {
        switch ($this->trimestre) {
            case 1: // Q1: Enero - Marzo
                $this->fechaInicio = Carbon::create($this->año, 1, 1);
                $this->fechaFin = Carbon::create($this->año, 3, 31, 23, 59, 59);
                break;
            case 2: // Q2: Abril - Junio
                $this->fechaInicio = Carbon::create($this->año, 4, 1);
                $this->fechaFin = Carbon::create($this->año, 6, 30, 23, 59, 59);
                break;
            case 3: // Q3: Julio - Septiembre
                $this->fechaInicio = Carbon::create($this->año, 7, 1);
                $this->fechaFin = Carbon::create($this->año, 9, 30, 23, 59, 59);
                break;
            case 4: // Q4: Octubre - Diciembre
                $this->fechaInicio = Carbon::create($this->año, 10, 1);
                $this->fechaFin = Carbon::create($this->año, 12, 31, 23, 59, 59);
                break;
            default:
                throw new \InvalidArgumentException('El trimestre debe ser 1, 2, 3 o 4');
        }
    }

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
            'tramites.direcciones.coordenada',
            'tramites.actividades.actividad',
            'tramites.contactos',
            'tramites.datosGenerales'
        ])
        ->where(function($query) {
            // Proveedores que estuvieron activos durante el trimestre
            $query->where(function($q) {
                // Caso 1: Se dieron de alta antes o durante el trimestre Y vencen después del inicio del trimestre
                $q->where('fecha_alta_padron', '<=', $this->fechaFin)
                  ->where(function($subQ) {
                      $subQ->where('fecha_vencimiento_padron', '>=', $this->fechaInicio)
                           ->orWhereNull('fecha_vencimiento_padron');
                  });
            })
            ->orWhere(function($q) {
                // Caso 2: Se dieron de alta durante el trimestre
                $q->whereBetween('fecha_alta_padron', [$this->fechaInicio, $this->fechaFin]);
            });
        })
        ->orderBy('fecha_alta_padron', 'asc')
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
            'PV Número',
            'Razón Social',
            'RFC',
            'Tipo Persona',
            'Estado durante Q' . $this->trimestre,
            'Fecha Alta',
            'Fecha Vencimiento',
            'Estado Geográfico',
            'Municipio',
            'Actividades Principales',
            'Contacto',
            'Teléfono',
            'Correo',
            'Días Activo en Trimestre',
            'Observaciones'
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
        $contacto = $ultimoTramite ? $ultimoTramite->contactos->first() : null;

        // Obtener actividades (máximo 3 principales)
        $actividades = 'Sin actividades';
        if ($ultimoTramite && $ultimoTramite->actividades->isNotEmpty()) {
            $actividadesLista = $ultimoTramite->actividades->take(3)->map(function($actividad) {
                return $actividad->actividad->nombre ?? 'N/A';
            });
            $actividades = $actividadesLista->implode(', ');
        }

        // Determinar estado durante el trimestre
        $fechaAlta = $proveedor->fecha_alta_padron ? Carbon::parse($proveedor->fecha_alta_padron) : null;
        $fechaVencimiento = $proveedor->fecha_vencimiento_padron ? Carbon::parse($proveedor->fecha_vencimiento_padron) : null;
        
        $estadoEnTrimestre = 'No determinado';
        $diasActivoEnTrimestre = 0;
        $observaciones = '';

        if ($fechaAlta) {
            // Calcular período activo dentro del trimestre
            $inicioActividad = $fechaAlta->max($this->fechaInicio);
            $finActividad = $fechaVencimiento ? $fechaVencimiento->min($this->fechaFin) : $this->fechaFin;
            
            if ($inicioActividad <= $finActividad) {
                $diasActivoEnTrimestre = $inicioActividad->diffInDays($finActividad) + 1;
                
                // Determinar estado
                if ($fechaAlta <= $this->fechaFin && (!$fechaVencimiento || $fechaVencimiento >= $this->fechaInicio)) {
                    if (!$fechaVencimiento || $fechaVencimiento > $this->fechaFin) {
                        $estadoEnTrimestre = 'Activo todo el trimestre';
                    } elseif ($fechaVencimiento >= $this->fechaInicio && $fechaVencimiento <= $this->fechaFin) {
                        $estadoEnTrimestre = 'Venció en trimestre';
                        $observaciones = 'Venció el ' . $fechaVencimiento->format('d/m/Y');
                    } else {
                        $estadoEnTrimestre = 'Activo parcial';
                    }
                    
                    if ($fechaAlta >= $this->fechaInicio && $fechaAlta <= $this->fechaFin) {
                        $observaciones .= ($observaciones ? ' | ' : '') . 'Nuevo en Q' . $this->trimestre;
                    }
                }
            }
        }

        return [
            $proveedor->id,
            $proveedor->pv_numero ?? 'N/A',
            $proveedor->razon_social ?? 'N/A',
            $proveedor->rfc ?? 'N/A',
            $proveedor->tipo_persona ?? 'N/A',
            $estadoEnTrimestre,
            $fechaAlta ? $fechaAlta->format('d/m/Y') : 'N/A',
            $fechaVencimiento ? $fechaVencimiento->format('d/m/Y') : 'Sin vencimiento',
            $direccion && $direccion->estado ? $direccion->estado->nombre : 'N/A',
            $direccion->municipio ?? 'N/A',
            $actividades,
            $contacto->nombre_contacto ?? 'Sin contacto',
            $contacto->telefono ?? 'N/A',
            $contacto->correo ?? 'N/A',
            $diasActivoEnTrimestre > 0 ? $diasActivoEnTrimestre . ' días' : '0 días',
            $observaciones ?: 'Normal'
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            'A:P' => [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'font' => [
                    'size' => 10,
                ],
            ],
            'A:A' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'E:E' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'F:F' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'G:G' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'H:H' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'O:O' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 12,  // PV Número
            'C' => 30,  // Razón Social
            'D' => 15,  // RFC
            'E' => 12,  // Tipo Persona
            'F' => 20,  // Estado en Trimestre
            'G' => 12,  // Fecha Alta
            'H' => 15,  // Fecha Vencimiento
            'I' => 15,  // Estado Geográfico
            'J' => 20,  // Municipio
            'K' => 35,  // Actividades
            'L' => 20,  // Contacto
            'M' => 15,  // Teléfono
            'N' => 25,  // Correo
            'O' => 15,  // Días Activo
            'P' => 25,  // Observaciones
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        $trimestres = [
            1 => 'Primer Trimestre (Ene-Mar)',
            2 => 'Segundo Trimestre (Abr-Jun)', 
            3 => 'Tercer Trimestre (Jul-Sep)',
            4 => 'Cuarto Trimestre (Oct-Dic)'
        ];
        
        return 'Q' . $this->trimestre . ' ' . $this->año . ' - ' . $trimestres[$this->trimestre];
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
                
                $trimestres = [
                    1 => 'PRIMER TRIMESTRE (ENERO - MARZO)',
                    2 => 'SEGUNDO TRIMESTRE (ABRIL - JUNIO)', 
                    3 => 'TERCER TRIMESTRE (JULIO - SEPTIEMBRE)',
                    4 => 'CUARTO TRIMESTRE (OCTUBRE - DICIEMBRE)'
                ];
                
                $tituloTrimestre = $trimestres[$this->trimestre] . ' ' . $this->año;
                
                $sheet->setCellValue('D1', 'GOBIERNO DEL ESTADO DE OAXACA');
                $sheet->setCellValue('D2', 'PADRÓN DE PROVEEDORES');
                $sheet->setCellValue('D3', 'REPORTE TRIMESTRAL - ' . $tituloTrimestre);
                $sheet->setCellValue('D4', 'Período: ' . $this->fechaInicio->format('d/m/Y') . ' al ' . $this->fechaFin->format('d/m/Y') . 
                                            ' | Generado: ' . now()->format('d/m/Y H:i:s'));
                
                $sheet->mergeCells('D1:P1');
                $sheet->mergeCells('D2:P2'); 
                $sheet->mergeCells('D3:P3');
                $sheet->mergeCells('D4:P4');
                
                $sheet->getStyle('D1:P4')->applyFromArray([
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
                
                $sheet->getStyle('A6:P6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '000000']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A6:P' . $lastRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ]);

                // Agregar estadísticas al final
                $totalProveedores = $lastRow - 6;
                
                $sheet->setCellValue('A' . ($lastRow + 2), 'RESUMEN ESTADÍSTICO:');
                $sheet->setCellValue('A' . ($lastRow + 3), 'Total de proveedores activos en Q' . $this->trimestre . ' ' . $this->año . ':');
                $sheet->setCellValue('C' . ($lastRow + 3), $totalProveedores);
                $sheet->setCellValue('A' . ($lastRow + 4), 'Período analizado:');
                $sheet->setCellValue('C' . ($lastRow + 4), $this->fechaInicio->format('d/m/Y') . ' - ' . $this->fechaFin->format('d/m/Y'));
                
                $sheet->getStyle('A' . ($lastRow + 2) . ':P' . ($lastRow + 4))->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => 'F0F0F0']],
                ]);
            },
        ];
    }
}
