<?php

namespace App\Exports;

use App\Models\Proveedor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Carbon\Carbon;

class ProveedoresExcelCompleto implements FromCollection, WithMapping, WithStyles, WithColumnWidths, WithDrawings, WithEvents
{
    protected $filtros;
    protected $diseno;

    public function __construct($filtros = [])
    {
        $this->filtros = $filtros;
        $columnasSeleccionadas = $this->filtros['columns'] ?? [
            'id', 'pv_numero', 'razon_social', 'rfc', 'tipo_persona', 
            'estado_padron', 'fecha_alta_padron', 'fecha_vencimiento_padron',
            'fecha_inicio', 'estado_geografico', 'municipio', 'actividades', 
            'contacto', 'telefono', 'correo', 'domicilio', 'dias_restantes'
        ];
        
        $this->diseno = new ProveedoresExcelDiseno($filtros, $columnasSeleccionadas);
    }

    public function collection()
    {
        return $this->construirQuery()->get();
    }

    private function construirQuery()
    {
        $query = Proveedor::with([
            'tramites' => function($q) {
                $q->orderBy('created_at', 'desc');
            },
            'tramites.direcciones.estado',
            'tramites.actividades.actividad',
            'tramites.contactos'
        ]);

        if (!empty($this->filtros['search'])) {
            $search = $this->filtros['search'];
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('rfc', 'like', "%{$search}%")
                  ->orWhere('razon_social', 'like', "%{$search}%");
            });
        }

        if (!empty($this->filtros['estado'])) {
            $query->where('estado_padron', $this->filtros['estado']);
        }

        if (!empty($this->filtros['tipo_persona'])) {
            $query->where('tipo_persona', $this->filtros['tipo_persona']);
        }

        if (!empty($this->filtros['año'])) {
            $query->whereYear('fecha_alta_padron', $this->filtros['año']);
        }

        if (!empty($this->filtros['sector'])) {
            $sectorIds = $this->filtros['sector'];
            if (!is_array($sectorIds)) {
                $sectorIds = [$sectorIds];
            }
            $sectorIds = array_filter($sectorIds, function($id) {
                return !empty($id);
            });
            
            if (!empty($sectorIds)) {
                $query->whereHas('tramites', function($tramiteQuery) use ($sectorIds) {
                    $tramiteQuery->whereRaw('id = (SELECT MAX(id) FROM tramites t WHERE t.proveedor_id = tramites.proveedor_id)')
                        ->whereHas('actividades.actividad.sector', function($q) use ($sectorIds) {
                            $q->whereIn('id', $sectorIds);
                        });
                });
            }
        }

        if (!empty($this->filtros['actividad_economica'])) {
            $actividadIds = $this->filtros['actividad_economica'];
            if (!is_array($actividadIds)) {
                $actividadIds = [$actividadIds];
            }
            $actividadIds = array_filter($actividadIds, function($id) {
                return !empty($id);
            });
            
            if (!empty($actividadIds)) {
                $query->whereHas('tramites', function($tramiteQuery) use ($actividadIds) {
                    $tramiteQuery->whereRaw('id = (SELECT MAX(id) FROM tramites t WHERE t.proveedor_id = tramites.proveedor_id)')
                        ->whereHas('actividades', function($q) use ($actividadIds) {
                            $q->whereIn('actividad_id', $actividadIds);
                        });
                });
            }
        }

        if (!empty($this->filtros['estado_geografico'])) {
            $estadoId = $this->filtros['estado_geografico'];
            $query->whereHas('tramites', function($tramiteQuery) use ($estadoId) {
                $tramiteQuery->whereRaw('id = (SELECT MAX(id) FROM tramites t WHERE t.proveedor_id = tramites.proveedor_id)')
                    ->whereHas('direcciones.estado', function($q) use ($estadoId) {
                        $q->where('id', $estadoId);
                    });
            });
        }

        if (!empty($this->filtros['con_historial'])) {
            switch ($this->filtros['con_historial']) {
                case 'si':
                    $query->has('tramites', '>=', 2);
                    break;
                case 'no':
                    $query->has('tramites', '<=', 1);
                    break;
                case 'sin_tramites':
                    $query->doesntHave('tramites');
                    break;
                case 'renovadores':
                    $query->whereHas('tramites', function($q) {
                        $q->where('tipo_tramite', 'Renovacion');
                    }, '>=', 2)
                    ->whereHas('tramites', function($q) {
                        $q->where('tipo_tramite', 'Inscripcion');
                    });
                    break;
            }
        }

        if (!empty($this->filtros['tipo_tramite_año']) || !empty($this->filtros['año_especifico'])) {
            $query->whereHas('tramites', function($q) {
                if (!empty($this->filtros['tipo_tramite_año'])) {
                    $q->where('tipo_tramite', $this->filtros['tipo_tramite_año']);
                }
                if (!empty($this->filtros['año_especifico'])) {
                    $q->whereRaw('YEAR(COALESCE(fecha_finalizacion, fecha_inicio, created_at)) = ?', [$this->filtros['año_especifico']]);
                }
            });
        }

        if (!empty($this->filtros['año_trimestre']) && !empty($this->filtros['trimestre'])) {
            $año = (int) $this->filtros['año_trimestre'];
            $trimestre = (int) $this->filtros['trimestre'];
            
            $rangosTrimestrales = [
                1 => ['inicio' => 1, 'fin' => 3],
                2 => ['inicio' => 4, 'fin' => 6],
                3 => ['inicio' => 7, 'fin' => 9],
                4 => ['inicio' => 10, 'fin' => 12]
            ];
            
            if (isset($rangosTrimestrales[$trimestre])) {
                $mesInicio = $rangosTrimestrales[$trimestre]['inicio'];
                $mesFin = $rangosTrimestrales[$trimestre]['fin'];
                
                $inicioTrimestre = Carbon::create($año, $mesInicio, 1)->startOfMonth();
                $finTrimestre = Carbon::create($año, $mesFin, 1)->endOfMonth();
                
                $query->where(function($q) use ($inicioTrimestre, $finTrimestre) {
                    $q->where('fecha_vencimiento_padron', '>=', $inicioTrimestre)
                      ->where('fecha_alta_padron', '<=', $finTrimestre);
                });
            }
        }

        return $query->orderBy('id', 'desc');
    }

    public function map($proveedor): array
    {
        $ultimoTramite = $proveedor->tramites->first();
        $direccion = $ultimoTramite ? $ultimoTramite->direcciones->first() : null;
        $contacto = $ultimoTramite ? $ultimoTramite->contactos->first() : null;
        
        $actividades = '';
        if ($ultimoTramite && $ultimoTramite->actividades) {
            $actividades = $ultimoTramite->actividades->take(3)->map(function($actividad) {
                return $actividad->actividad->nombre ?? 'N/A';
            })->implode(', ');
        }

        $diasRestantes = 'N/A';
        if ($proveedor->fecha_vencimiento_padron) {
            $fechaVencimiento = Carbon::parse($proveedor->fecha_vencimiento_padron);
            $hoy = Carbon::now();
            $diasRestantes = (int) $hoy->diffInDays($fechaVencimiento, false);
            
            if ($diasRestantes < 0) {
                $diasRestantes = 'Vencido (' . abs($diasRestantes) . ' días)';
            } elseif ($diasRestantes <= 30) {
                $diasRestantes = 'Por vencer (' . $diasRestantes . ' días)';
            } else {
                $diasRestantes = $diasRestantes . ' días';
            }
        }

        $domicilio = 'N/A';
        if ($direccion) {
            $partes = array_filter([
                $direccion->calle,
                $direccion->numero_exterior,
                $direccion->colonia,
                $direccion->municipio,
                $direccion->estado ? $direccion->estado->nombre : null
            ]);
            $domicilio = implode(', ', $partes) ?: 'N/A';
        }

        $fechaInicio = 'N/A';
        if ($proveedor->fecha_vencimiento_padron) {
            $fechaVencimiento = Carbon::parse($proveedor->fecha_vencimiento_padron);
            $fechaInicio = $fechaVencimiento->copy()->subYear()->format('d/m/Y');
        }

        $datosCompletos = [
            'id' => $proveedor->id,
            'pv_numero' => $proveedor->pv_numero ?? 'N/A',
            'razon_social' => $proveedor->razon_social ?? 'N/A',
            'rfc' => $proveedor->rfc ?? 'N/A',
            'tipo_persona' => $proveedor->tipo_persona ?? 'N/A',
            'estado_padron' => $proveedor->estado_padron ?? 'Pendiente',
            'fecha_alta_padron' => $proveedor->fecha_alta_padron ? Carbon::parse($proveedor->fecha_alta_padron)->format('d/m/Y') : 'N/A',
            'fecha_vencimiento_padron' => $proveedor->fecha_vencimiento_padron ? Carbon::parse($proveedor->fecha_vencimiento_padron)->format('d/m/Y') : 'N/A',
            'fecha_inicio' => $fechaInicio,
            'estado_geografico' => $direccion && $direccion->estado ? $direccion->estado->nombre : 'N/A',
            'municipio' => $direccion ? $direccion->municipio : 'N/A',
            'actividades' => $actividades,
            'contacto' => $contacto ? $contacto->nombre_contacto : 'N/A',
            'telefono' => $contacto ? $contacto->telefono : 'N/A',
            'correo' => $contacto ? $contacto->correo : 'N/A',
            'domicilio' => $domicilio,
            'dias_restantes' => $diasRestantes
        ];

        $columnasSeleccionadas = $this->filtros['columns'] ?? array_keys($datosCompletos);

        return array_map(function($columna) use ($datosCompletos) {
            return $datosCompletos[$columna] ?? 'N/A';
        }, $columnasSeleccionadas);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]
        ];
    }

    public function columnWidths(): array
    {
        $columnasSeleccionadas = $this->filtros['columns'] ?? [
            'id', 'pv_numero', 'razon_social', 'rfc', 'tipo_persona', 
            'estado_padron', 'fecha_alta_padron', 'fecha_vencimiento_padron',
            'fecha_inicio', 'estado_geografico', 'municipio', 'actividades', 
            'contacto', 'telefono', 'correo', 'domicilio', 'dias_restantes'
        ];

        $anchosColumnas = [
            'id' => 10,
            'pv_numero' => 15,
            'razon_social' => 40,
            'rfc' => 15,
            'tipo_persona' => 15,
            'estado_padron' => 15,
            'fecha_alta_padron' => 15,
            'fecha_vencimiento_padron' => 15,
            'fecha_inicio' => 15,
            'estado_geografico' => 20,
            'municipio' => 20,
            'actividades' => 30,
            'contacto' => 25,
            'telefono' => 15,
            'correo' => 25,
            'domicilio' => 35,
            'dias_restantes' => 15
        ];

        $anchosDinamicos = [];
        $letra = 'A';
        
        foreach ($columnasSeleccionadas as $columna) {
            $anchosDinamicos[$letra] = $anchosColumnas[$columna] ?? 15;
            $letra++;
        }

        return $anchosDinamicos;
    }

    public function drawings()
    {
        return $this->diseno->drawings();
    }

    public function registerEvents(): array
    {
        return $this->diseno->registerEvents();
    }
}
