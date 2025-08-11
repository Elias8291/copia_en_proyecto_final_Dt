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

class ProveedoresPorVencerExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, WithDrawings, WithEvents
{
    protected $diasVencer;

    public function __construct($diasVencer = 30)
    {
        $this->diasVencer = (int) $diasVencer;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $hoy = Carbon::now();
        $fechaLimite = $hoy->copy()->addDays((int) $this->diasVencer);

        return Proveedor::with([
            'tramites' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'tramites.actividades.actividad'
        ])
        ->where('estado_padron', 'Activo') // Solo proveedores activos
        ->whereNotNull('fecha_vencimiento_padron')
        ->where('fecha_vencimiento_padron', '>=', $hoy)
        ->where('fecha_vencimiento_padron', '<=', $fechaLimite)
        ->orderBy('fecha_vencimiento_padron', 'asc')
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
            'Giro (Actividades Económicas)',
            'Inicio Vigencia',
            'Fecha Vencimiento',
            'Días Restantes',
            'Estado Vigencia'
        ];
    }

    /**
     * @param mixed $proveedor
     * @return array
     */
    public function map($proveedor): array
    {
        $ultimoTramite = $proveedor->tramites->first();
        
        // Obtener actividades económicas del último trámite
        $giro = 'Sin actividades registradas';
        if ($ultimoTramite && $ultimoTramite->actividades->isNotEmpty()) {
            $actividades = $ultimoTramite->actividades->map(function($actividad) {
                return $actividad->actividad->nombre ?? 'Actividad no encontrada';
            });
            $giro = $actividades->join(', ');
        }

        $fechaVencimiento = Carbon::parse($proveedor->fecha_vencimiento_padron);
        $fechaInicio = $fechaVencimiento->copy()->subYear();
        $hoy = Carbon::now();
        $diasRestantes = $hoy->diffInDays($fechaVencimiento, false);

        // Formatear tiempo restante de manera más legible
        $tiempoRestanteFormateado = 'N/A';
        $estadoVigencia = 'No definido';
        
        if ($diasRestantes < 0) {
            // Ya vencido - mostrar tiempo transcurrido
            $diasVencido = abs($diasRestantes);
            if ($diasVencido >= 365) {
                $anos = floor($diasVencido / 365);
                $tiempoRestanteFormateado = $anos . ($anos == 1 ? ' año vencido' : ' años vencidos');
            } elseif ($diasVencido >= 30) {
                $meses = floor($diasVencido / 30);
                $tiempoRestanteFormateado = $meses . ($meses == 1 ? ' mes vencido' : ' meses vencidos');
            } else {
                $tiempoRestanteFormateado = $diasVencido . ($diasVencido == 1 ? ' día vencido' : ' días vencidos');
            }
            $estadoVigencia = 'Vencido';
        } else {
            // Aún vigente - mostrar tiempo restante
            if ($diasRestantes >= 365) {
                $anos = floor($diasRestantes / 365);
                $diasExtra = $diasRestantes % 365;
                $mesesExtra = floor($diasExtra / 30);
                if ($mesesExtra > 0) {
                    $tiempoRestanteFormateado = $anos . ($anos == 1 ? ' año' : ' años') . ', ' . $mesesExtra . ($mesesExtra == 1 ? ' mes' : ' meses');
                } else {
                    $tiempoRestanteFormateado = $anos . ($anos == 1 ? ' año' : ' años');
                }
            } elseif ($diasRestantes >= 30) {
                $meses = floor($diasRestantes / 30);
                $diasExtra = $diasRestantes % 30;
                if ($diasExtra > 0) {
                    $tiempoRestanteFormateado = $meses . ($meses == 1 ? ' mes' : ' meses') . ', ' . $diasExtra . ($diasExtra == 1 ? ' día' : ' días');
                } else {
                    $tiempoRestanteFormateado = $meses . ($meses == 1 ? ' mes' : ' meses');
                }
            } elseif ($diasRestantes >= 2) {
                // Más de 2 días - mostrar solo días
                $tiempoRestanteFormateado = floor($diasRestantes) . ' días';
            } elseif ($diasRestantes >= 1) {
                // Entre 1 y 2 días - mostrar días y horas
                $diasEnteros = floor($diasRestantes);
                $horasRestantes = $hoy->diffInHours($fechaVencimiento, false) % 24;
                if ($horasRestantes > 0) {
                    $tiempoRestanteFormateado = $diasEnteros . ' día, ' . $horasRestantes . ($horasRestantes == 1 ? ' hora' : ' horas');
                } else {
                    $tiempoRestanteFormateado = $diasEnteros . ' día';
                }
            } else {
                // Menos de un día - mostrar horas y minutos
                $horasRestantes = $hoy->diffInHours($fechaVencimiento, false);
                $minutosRestantes = $hoy->diffInMinutes($fechaVencimiento, false) % 60;
                
                if ($horasRestantes > 0) {
                    if ($minutosRestantes > 0) {
                        $tiempoRestanteFormateado = $horasRestantes . ($horasRestantes == 1 ? ' hora' : ' horas') . ', ' . $minutosRestantes . ($minutosRestantes == 1 ? ' minuto' : ' minutos');
                    } else {
                        $tiempoRestanteFormateado = $horasRestantes . ($horasRestantes == 1 ? ' hora' : ' horas');
                    }
                } elseif ($minutosRestantes > 0) {
                    $tiempoRestanteFormateado = $minutosRestantes . ($minutosRestantes == 1 ? ' minuto' : ' minutos');
                } else {
                    $tiempoRestanteFormateado = 'Vence ahora';
                }
            }
            
            // Determinar estado de vigencia
            if ($diasRestantes <= 0) {
                $estadoVigencia = 'Vence hoy';
            } elseif ($diasRestantes <= 7) {
                $estadoVigencia = 'Vence esta semana';
            } elseif ($diasRestantes <= 30) {
                $estadoVigencia = 'Por vencer';
            } else {
                $estadoVigencia = 'Vigente';
            }
        }

        return [
            $proveedor->id,
            $proveedor->razon_social ?? 'N/A',
            $proveedor->rfc ?? 'N/A',
            $proveedor->tipo_persona ?? 'N/A',
            $proveedor->estado_padron ?? 'Pendiente',
            $giro,
            $fechaInicio->format('d/m/Y'),
            $fechaVencimiento->format('d/m/Y'),
            $tiempoRestanteFormateado, // Usar el tiempo formateado en lugar de días decimales
            $estadoVigencia
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para toda la hoja (excluyendo header)
            'A:J' => [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'font' => [
                    'size' => 10,
                ],
            ],
            // Centrar columnas específicas
            'A:A' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // ID
            'D:D' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Tipo Persona
            'E:E' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Estado Padrón
            'G:G' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Inicio Vigencia
            'H:H' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Fecha Vencimiento
            'I:I' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Días Restantes
            'J:J' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Estado Vigencia
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 40,  // Razón Social
            'C' => 15,  // RFC
            'D' => 12,  // Tipo Persona
            'E' => 12,  // Estado Padrón
            'F' => 50,  // Giro (Actividades Económicas)
            'G' => 15,  // Inicio Vigencia
            'H' => 15,  // Fecha Vencimiento
            'I' => 12,  // Días Restantes
            'J' => 18,  // Estado Vigencia
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Proveedores Activos por Vencer';
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
        $drawing->setHeight(60); // Más pequeño
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(20);
        $drawing->setOffsetY(-75); // Más hacia abajo para centrarlo en el header

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
                
                // Insertar filas para el header (espacio mínimo)
                $sheet->insertNewRowBefore(1, 5);
                
                // Agregar el texto del header (más a la derecha para dar espacio al logo)
                $sheet->setCellValue('D1', 'GOBIERNO DEL ESTADO DE OAXACA');
                $sheet->setCellValue('D2', 'PADRÓN DE PROVEEDORES');
                $sheet->setCellValue('D3', 'Proveedores Activos por Vencer en ' . $this->diasVencer . ' días');
                $sheet->setCellValue('D4', 'Fecha de generación: ' . now()->format('d/m/Y H:i:s'));
                
                // Combinar celdas para el título principal (desde D hasta J)
                $sheet->mergeCells('D1:J1');
                $sheet->mergeCells('D2:J2'); 
                $sheet->mergeCells('D3:J3');
                $sheet->mergeCells('D4:J4');
                
                // Estilos para el header
                $sheet->getStyle('D1:J4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '000000'], // Negro
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                // Estilo específico para el título principal
                $sheet->getStyle('D1')->applyFromArray([
                    'font' => [
                        'size' => 16,
                        'bold' => true,
                        'color' => ['rgb' => '000000'],
                    ],
                ]);
                
                $sheet->getStyle('D2')->applyFromArray([
                    'font' => [
                        'size' => 14,
                        'bold' => true,
                        'color' => ['rgb' => '000000'],
                    ],
                ]);
                
                $sheet->getStyle('D3')->applyFromArray([
                    'font' => [
                        'size' => 12,
                        'color' => ['rgb' => '000000'],
                    ],
                ]);
                
                $sheet->getStyle('D4')->applyFromArray([
                    'font' => [
                        'size' => 10,
                        'color' => ['rgb' => '666666'],
                    ],
                ]);
                
                // Ajustar altura de las filas del header
                $sheet->getRowDimension('1')->setRowHeight(25);
                $sheet->getRowDimension('2')->setRowHeight(20);
                $sheet->getRowDimension('3')->setRowHeight(18);
                $sheet->getRowDimension('4')->setRowHeight(15);
                $sheet->getRowDimension('5')->setRowHeight(10); // Solo una fila de espacio
                
                // Estilo para los encabezados de las columnas (ahora en la fila 6)
                $sheet->getStyle('A6:J6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '000000'] // Negro
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                // Bordes para toda la tabla
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A6:J' . $lastRow)->applyFromArray([
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
