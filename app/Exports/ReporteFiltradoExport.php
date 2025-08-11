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

class ReporteFiltradoExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, WithDrawings, WithEvents
{
    protected $filtros;
    protected $tipoReporte;
    protected $columnasSeleccionadas;

    public function __construct($filtros = [], $tipoReporte = 'completo', $columnasSeleccionadas = [])
    {
        $this->filtros = $filtros;
        $this->tipoReporte = $tipoReporte;
        $this->columnasSeleccionadas = $columnasSeleccionadas;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Construir query con filtros
        $query = Proveedor::with([
            'tramites' => function($q) {
                $q->orderBy('created_at', 'desc');
            },
            'tramites.direcciones.estado',
            'tramites.direcciones.coordenada',
            'tramites.actividades.actividad',
            'tramites.contactos',
            'tramites.datosGenerales'
        ]);

        // Aplicar los mismos filtros que en el controlador
        if (!empty($this->filtros['estado_padron'])) {
            $query->where('estado_padron', $this->filtros['estado_padron']);
        }

        if (!empty($this->filtros['tipo_persona'])) {
            $query->where('tipo_persona', $this->filtros['tipo_persona']);
        }

        if (!empty($this->filtros['estado_geografico'])) {
            $estadoIds = $this->filtros['estado_geografico'];
            if (!is_array($estadoIds)) {
                $estadoIds = [$estadoIds];
            }
            $estadoIds = array_filter($estadoIds, function($id) {
                return !empty($id);
            });
            if (!empty($estadoIds)) {
                $query->whereHas('tramites.direcciones.estado', function($q) use ($estadoIds) {
                    $q->whereIn('id', $estadoIds);
                });
            }
        }

        if (!empty($this->filtros['municipio'])) {
            $query->whereHas('tramites.direcciones', function($q) {
                $q->where('municipio', 'like', '%' . $this->filtros['municipio'] . '%');
            });
        }

        if (!empty($this->filtros['sector'])) {
            $sectorIds = $this->filtros['sector'];
            if (!is_array($sectorIds)) {
                $sectorIds = [$sectorIds];
            }
            $query->whereHas('tramites.actividades.actividad', function($q) use ($sectorIds) {
                $q->whereIn('sector_id', $sectorIds);
            });
        }

        if (!empty($this->filtros['actividad_economica'])) {
            $actividadIds = $this->filtros['actividad_economica'];
            if (!is_array($actividadIds)) {
                $actividadIds = [$actividadIds];
            }
            $query->whereHas('tramites.actividades', function($q) use ($actividadIds) {
                $q->whereIn('actividad_id', $actividadIds);
            });
        }

        if (!empty($this->filtros['fecha_alta_desde'])) {
            $query->where('fecha_alta_padron', '>=', $this->filtros['fecha_alta_desde']);
        }

        if (!empty($this->filtros['fecha_alta_hasta'])) {
            $query->where('fecha_alta_padron', '<=', $this->filtros['fecha_alta_hasta']);
        }

        if (!empty($this->filtros['fecha_vencimiento_desde'])) {
            $query->where('fecha_vencimiento_padron', '>=', $this->filtros['fecha_vencimiento_desde']);
        }

        if (!empty($this->filtros['fecha_vencimiento_hasta'])) {
            $query->where('fecha_vencimiento_padron', '<=', $this->filtros['fecha_vencimiento_hasta']);
        }

        if (!empty($this->filtros['search']) || !empty($this->filtros['buscar'])) {
            $buscar = $this->filtros['search'] ?? $this->filtros['buscar'];
            $query->where(function($q) use ($buscar) {
                $q->where('id', 'like', "%{$buscar}%")
                  ->orWhere('rfc', 'like', "%{$buscar}%")
                  ->orWhere('razon_social', 'like', "%{$buscar}%");
            });
        }

        // Filtro por vencimiento
        if (!empty($this->filtros['vencimiento'])) {
            $vencimiento = $this->filtros['vencimiento'];
            $hoy = Carbon::now();
            
            switch ($vencimiento) {
                case 'vencido':
                    $query->where('fecha_vencimiento_padron', '<', $hoy);
                    break;
                case 'por_vencer':
                    $query->whereBetween('fecha_vencimiento_padron', [$hoy, $hoy->copy()->addDays(30)]);
                    break;
                case 'sin_fecha':
                    $query->whereNull('fecha_vencimiento_padron');
                    break;
            }
        }

        // Filtro por año
        if (!empty($this->filtros['año'])) {
            $query->whereYear('created_at', $this->filtros['año']);
        }

        if (!empty($this->filtros['dias_vencer'])) {
            $dias = (int) $this->filtros['dias_vencer'];
            $hoy = Carbon::now();
            $fechaLimite = $hoy->copy()->addDays($dias);
            
            $query->where('estado_padron', 'Activo')
                  ->whereNotNull('fecha_vencimiento_padron')
                  ->where('fecha_vencimiento_padron', '>=', $hoy)
                  ->where('fecha_vencimiento_padron', '<=', $fechaLimite);
        }

        return $query->orderBy('updated_at', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Si es personalizado, usar solo las columnas seleccionadas
        if ($this->tipoReporte === 'personalizado' && !empty($this->columnasSeleccionadas)) {
            $todosLosHeadings = [
                'id' => 'ID',
                'pv_numero' => 'PV Número',
                'razon_social' => 'Razón Social',
                'rfc' => 'RFC',
                'tipo_persona' => 'Tipo Persona',
                'estado_padron' => 'Estado Padrón',
                'fecha_alta' => 'Fecha Alta',
                'fecha_vencimiento' => 'Fecha Vencimiento',
                'estado_geografico' => 'Estado Geográfico',
                'municipio' => 'Municipio',
                'actividades' => 'Actividades Económicas',
                'sectores' => 'Sectores Económicos',
                'contacto' => 'Contacto',
                'telefono' => 'Teléfono',
                'correo' => 'Correo'
            ];

            $headingsSeleccionados = [];
            foreach ($this->columnasSeleccionadas as $columna) {
                if (isset($todosLosHeadings[$columna])) {
                    $headingsSeleccionados[] = $todosLosHeadings[$columna];
                }
            }
            return $headingsSeleccionados;
        }

        // Lógica existente para otros tipos de reporte
        $headingsBase = [
            'ID',
            'PV Número',
            'Razón Social',
            'RFC',
            'Tipo Persona',
            'Estado Padrón',
        ];

        // Agregar columnas según tipo de reporte
        switch ($this->tipoReporte) {
            case 'geografico':
                return array_merge($headingsBase, [
                    'Estado',
                    'Municipio',
                    'Localidad',
                    'Código Postal',
                    'Latitud',
                    'Longitud'
                ]);

            case 'contactos':
                return array_merge($headingsBase, [
                    'Nombre Contacto',
                    'Cargo',
                    'Teléfono Contacto',
                    'Correo Contacto',
                    'Teléfono Empresa',
                    'Correo Empresa'
                ]);

            case 'actividades':
                return array_merge($headingsBase, [
                    'Actividades Económicas',
                    'Sector Principal',
                    'Fecha Alta',
                    'Fecha Vencimiento'
                ]);

            case 'vencimientos':
                return array_merge($headingsBase, [
                    'Fecha Alta',
                    'Fecha Vencimiento',
                    'Tiempo Restante',
                    'Estado Vigencia'
                ]);

            default: // completo
                return array_merge($headingsBase, [
                    'Fecha Alta',
                    'Fecha Vencimiento',
                    'Estado',
                    'Municipio',
                    'Actividades',
                    'Contacto',
                    'Teléfono',
                    'Correo'
                ]);
        }
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
        $contacto = $ultimoTramite ? $ultimoTramite->contactos->first() : null;
        $datosGenerales = $ultimoTramite ? $ultimoTramite->datosGenerales->first() : null;

        // Obtener actividades
        $actividades = 'Sin actividades';
        if ($ultimoTramite && $ultimoTramite->actividades->isNotEmpty()) {
            $actividadesLista = $ultimoTramite->actividades->map(function($actividad) {
                return $actividad->actividad->nombre ?? 'N/A';
            });
            $actividades = $actividadesLista->implode(', ');
        }

        // Obtener sectores únicos
        $sectores = 'Sin sectores';
        if ($ultimoTramite && $ultimoTramite->actividades->isNotEmpty()) {
            $sectoresLista = $ultimoTramite->actividades->map(function($actividad) {
                return $actividad->actividad->sector->nombre ?? 'N/A';
            })->unique()->filter(function($valor) {
                return $valor !== 'N/A';
            });
            
            if ($sectoresLista->isNotEmpty()) {
                $sectores = $sectoresLista->implode(', ');
            }
        }

        // Si es personalizado, usar solo las columnas seleccionadas
        if ($this->tipoReporte === 'personalizado' && !empty($this->columnasSeleccionadas)) {
            $todosLosDatos = [
                'id' => $proveedor->id,
                'pv_numero' => $proveedor->pv_numero ?? 'N/A',
                'razon_social' => $proveedor->razon_social ?? 'N/A',
                'rfc' => $proveedor->rfc ?? 'N/A',
                'tipo_persona' => $proveedor->tipo_persona ?? 'N/A',
                'estado_padron' => $proveedor->estado_padron ?? 'Pendiente',
                'fecha_alta' => $proveedor->fecha_alta_padron ? Carbon::parse($proveedor->fecha_alta_padron)->format('d/m/Y') : 'N/A',
                'fecha_vencimiento' => $proveedor->fecha_vencimiento_padron ? Carbon::parse($proveedor->fecha_vencimiento_padron)->format('d/m/Y') : 'N/A',
                'estado_geografico' => $direccion && $direccion->estado ? $direccion->estado->nombre : 'N/A',
                'municipio' => $direccion->municipio ?? 'N/A',
                'actividades' => substr($actividades, 0, 100) . (strlen($actividades) > 100 ? '...' : ''),
                'sectores' => substr($sectores, 0, 100) . (strlen($sectores) > 100 ? '...' : ''),
                'contacto' => $contacto->nombre_contacto ?? 'Sin contacto',
                'telefono' => $contacto->telefono ?? 'N/A',
                'correo' => $contacto->correo ?? 'N/A'
            ];

            $datosSeleccionados = [];
            foreach ($this->columnasSeleccionadas as $columna) {
                if (isset($todosLosDatos[$columna])) {
                    $datosSeleccionados[] = $todosLosDatos[$columna];
                }
            }
            return $datosSeleccionados;
        }

        // Calcular tiempo restante (para reportes de vencimientos)
        $tiempoRestante = 'N/A';
        $estadoVigencia = 'No definido';
        if ($proveedor->fecha_vencimiento_padron) {
            $fechaVencimiento = Carbon::parse($proveedor->fecha_vencimiento_padron);
            $ahora = Carbon::now();
            $diasRestantes = $ahora->diffInDays($fechaVencimiento, false);

            if ($diasRestantes < 0) {
                $diasVencidos = abs($diasRestantes);
                if ($diasVencidos >= 30) {
                    $meses = floor($diasVencidos / 30);
                    $tiempoRestante = $meses . ($meses == 1 ? ' mes vencido' : ' meses vencidos');
                } else {
                    $tiempoRestante = $diasVencidos . ($diasVencidos == 1 ? ' día vencido' : ' días vencidos');
                }
                $estadoVigencia = 'Vencido';
            } else {
                if ($diasRestantes >= 30) {
                    $meses = floor($diasRestantes / 30);
                    $tiempoRestante = $meses . ($meses == 1 ? ' mes restante' : ' meses restantes');
                } else {
                    $tiempoRestante = $diasRestantes . ($diasRestantes == 1 ? ' día restante' : ' días restantes');
                }
                
                if ($diasRestantes <= 7) {
                    $estadoVigencia = 'Vence esta semana';
                } elseif ($diasRestantes <= 30) {
                    $estadoVigencia = 'Por vencer';
                } else {
                    $estadoVigencia = 'Vigente';
                }
            }
        }

        $baseData = [
            $proveedor->id,
            $proveedor->pv_numero ?? 'N/A',
            $proveedor->razon_social ?? 'N/A',
            $proveedor->rfc ?? 'N/A',
            $proveedor->tipo_persona ?? 'N/A',
            $proveedor->estado_padron ?? 'Pendiente',
        ];

        // Retornar datos según tipo de reporte
        switch ($this->tipoReporte) {
            case 'geografico':
                return array_merge($baseData, [
                    $direccion && $direccion->estado ? $direccion->estado->nombre : 'N/A',
                    $direccion->municipio ?? 'N/A',
                    $direccion->localidad ?? 'N/A',
                    $direccion->codigo_postal ?? 'N/A',
                    $coordenada->latitud ?? 'N/A',
                    $coordenada->longitud ?? 'N/A'
                ]);

            case 'contactos':
                return array_merge($baseData, [
                    $contacto->nombre_contacto ?? 'Sin contacto',
                    $contacto->cargo ?? 'N/A',
                    $contacto->telefono ?? 'N/A',
                    $contacto->correo ?? 'N/A',
                    $datosGenerales->telefono ?? 'N/A',
                    $datosGenerales->email ?? 'N/A'
                ]);

            case 'actividades':
                return array_merge($baseData, [
                    $actividades,
                    'Por clasificar', // Sector principal
                    $proveedor->fecha_alta_padron ? Carbon::parse($proveedor->fecha_alta_padron)->format('d/m/Y') : 'N/A',
                    $proveedor->fecha_vencimiento_padron ? Carbon::parse($proveedor->fecha_vencimiento_padron)->format('d/m/Y') : 'N/A'
                ]);

            case 'vencimientos':
                return array_merge($baseData, [
                    $proveedor->fecha_alta_padron ? Carbon::parse($proveedor->fecha_alta_padron)->format('d/m/Y') : 'N/A',
                    $proveedor->fecha_vencimiento_padron ? Carbon::parse($proveedor->fecha_vencimiento_padron)->format('d/m/Y') : 'N/A',
                    $tiempoRestante,
                    $estadoVigencia
                ]);

            default: // completo
                return array_merge($baseData, [
                    $proveedor->fecha_alta_padron ? Carbon::parse($proveedor->fecha_alta_padron)->format('d/m/Y') : 'N/A',
                    $proveedor->fecha_vencimiento_padron ? Carbon::parse($proveedor->fecha_vencimiento_padron)->format('d/m/Y') : 'N/A',
                    $direccion && $direccion->estado ? $direccion->estado->nombre : 'N/A',
                    $direccion->municipio ?? 'N/A',
                    substr($actividades, 0, 50) . (strlen($actividades) > 50 ? '...' : ''),
                    $contacto->nombre_contacto ?? 'Sin contacto',
                    $contacto->telefono ?? 'N/A',
                    $contacto->correo ?? 'N/A'
                ]);
        }
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            'A:Z' => [
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
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        // Anchos adaptativos según tipo de reporte
        switch ($this->tipoReporte) {
            case 'geografico':
                return [
                    'A' => 8, 'B' => 12, 'C' => 25, 'D' => 15, 'E' => 12, 'F' => 12,
                    'G' => 15, 'H' => 20, 'I' => 20, 'J' => 12, 'K' => 12, 'L' => 12
                ];

            case 'contactos':
                return [
                    'A' => 8, 'B' => 12, 'C' => 25, 'D' => 15, 'E' => 12, 'F' => 12,
                    'G' => 20, 'H' => 18, 'I' => 15, 'J' => 25, 'K' => 15, 'L' => 25
                ];

            default:
                return [
                    'A' => 8, 'B' => 12, 'C' => 25, 'D' => 15, 'E' => 12, 'F' => 12,
                    'G' => 12, 'H' => 15, 'I' => 15, 'J' => 20, 'K' => 30, 'L' => 20, 'M' => 15, 'N' => 25
                ];
        }
    }

    /**
     * @return string
     */
    public function title(): string
    {
        $titulo = 'Reporte Filtrado';
        
        if (!empty($this->filtros['estado_padron'])) {
            $titulo .= ' - ' . $this->filtros['estado_padron'];
        }
        
        return $titulo;
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
                
                // Título dinámico basado en filtros
                $tituloReporte = 'Reporte de Proveedores';
                $filtrosAplicados = [];
                
                if (!empty($this->filtros['estado_padron'])) {
                    $filtrosAplicados[] = 'Estado: ' . $this->filtros['estado_padron'];
                }
                if (!empty($this->filtros['tipo_persona'])) {
                    $filtrosAplicados[] = 'Tipo: ' . $this->filtros['tipo_persona'];
                }
                if (!empty($this->filtros['municipio'])) {
                    $filtrosAplicados[] = 'Municipio: ' . $this->filtros['municipio'];
                }
                if (!empty($this->filtros['sector'])) {
                    $sectorCount = is_array($this->filtros['sector']) ? count($this->filtros['sector']) : 1;
                    $filtrosAplicados[] = 'Sectores: ' . $sectorCount . ' seleccionados';
                }
                if (!empty($this->filtros['actividad_economica'])) {
                    $count = is_array($this->filtros['actividad_economica']) ? count($this->filtros['actividad_economica']) : 1;
                    $filtrosAplicados[] = 'Actividades: ' . $count . ' seleccionadas';
                }
                if (!empty($this->filtros['dias_vencer'])) {
                    $filtrosAplicados[] = 'Por vencer en ' . $this->filtros['dias_vencer'] . ' días';
                }
                
                if (!empty($filtrosAplicados)) {
                    $tituloReporte .= ' - ' . implode(', ', $filtrosAplicados);
                }
                
                $sheet->setCellValue('D1', 'GOBIERNO DEL ESTADO DE OAXACA');
                $sheet->setCellValue('D2', 'PADRÓN DE PROVEEDORES');
                $sheet->setCellValue('D3', $tituloReporte);
                $sheet->setCellValue('D4', 'Fecha de generación: ' . now()->format('d/m/Y H:i:s'));
                
                // Determinar rango de celdas según tipo de reporte
                $ultimaColumna = match($this->tipoReporte) {
                    'geografico' => 'L',
                    'contactos' => 'L',
                    default => 'N'
                };
                
                $sheet->mergeCells('D1:' . $ultimaColumna . '1');
                $sheet->mergeCells('D2:' . $ultimaColumna . '2'); 
                $sheet->mergeCells('D3:' . $ultimaColumna . '3');
                $sheet->mergeCells('D4:' . $ultimaColumna . '4');
                
                $sheet->getStyle('D1:' . $ultimaColumna . '4')->applyFromArray([
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
                
                $sheet->getStyle('A6:' . $ultimaColumna . '6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '000000']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A6:' . $ultimaColumna . $lastRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ]);
            },
        ];
    }
}
