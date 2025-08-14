<?php

namespace App\Exports;

use App\Models\Proveedor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Carbon\Carbon;

class ProveedoresSimpleExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $filtros;

    public function __construct($filtros = [])
    {
        $this->filtros = $filtros;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Proveedor::with([
            'tramites' => function($q) {
                $q->orderBy('created_at', 'desc');
            },
            'tramites.direcciones.estado',
            'tramites.actividades.actividad',
            'tramites.contactos'
        ]);

        // Aplicar filtros
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

        if (!empty($this->filtros['vencimiento'])) {
            $vencimiento = $this->filtros['vencimiento'];
            $hoy = now();
            
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

        if (!empty($this->filtros['año'])) {
            $query->whereYear('created_at', $this->filtros['año']);
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
                $query->whereHas('tramites.actividades.actividad', function($q) use ($sectorIds) {
                    $q->whereIn('sector_id', $sectorIds);
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
                $query->whereHas('tramites.actividades', function($q) use ($actividadIds) {
                    $q->whereIn('actividad_id', $actividadIds);
                });
            }
        }

        if (!empty($this->filtros['estado_geografico'])) {
            $estadoId = $this->filtros['estado_geografico'];
            $query->whereHas('tramites.direcciones.estado', function($q) use ($estadoId) {
                $q->where('id', $estadoId);
            });
        }

        return $query->orderBy('id', 'desc')->get();
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
            'Estado Padrón',
            'Fecha Alta',
            'Fecha Vencimiento',
            'Estado Geográfico',
            'Municipio',
            'Actividades',
            'Contacto',
            'Teléfono',
            'Correo',
            'Días Restantes'
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
        
        // Obtener actividades
        $actividades = '';
        if ($ultimoTramite && $ultimoTramite->actividades) {
            $actividades = $ultimoTramite->actividades->take(3)->map(function($actividad) {
                return $actividad->actividad->nombre ?? 'N/A';
            })->implode(', ');
        }

        // Calcular días restantes
        $diasRestantes = 'N/A';
        if ($proveedor->fecha_vencimiento_padron) {
            $fechaVencimiento = Carbon::parse($proveedor->fecha_vencimiento_padron);
            $hoy = Carbon::now();
            $diasRestantes = $hoy->diffInDays($fechaVencimiento, false);
            
            if ($diasRestantes < 0) {
                $diasRestantes = 'Vencido (' . abs($diasRestantes) . ' días)';
            } elseif ($diasRestantes <= 30) {
                $diasRestantes = 'Por vencer (' . $diasRestantes . ' días)';
            } else {
                $diasRestantes = $diasRestantes . ' días';
            }
        }

        return [
            $proveedor->id,
            $proveedor->pv_numero ?? 'N/A',
            $proveedor->razon_social ?? 'N/A',
            $proveedor->rfc ?? 'N/A',
            $proveedor->tipo_persona ?? 'N/A',
            $proveedor->estado_padron ?? 'Pendiente',
            $proveedor->fecha_alta_padron ? Carbon::parse($proveedor->fecha_alta_padron)->format('d/m/Y') : 'N/A',
            $proveedor->fecha_vencimiento_padron ? Carbon::parse($proveedor->fecha_vencimiento_padron)->format('d/m/Y') : 'N/A',
            $direccion && $direccion->estado ? $direccion->estado->nombre : 'N/A',
            $direccion ? $direccion->municipio : 'N/A',
            $actividades,
            $contacto ? $contacto->nombre_contacto : 'N/A',
            $contacto ? $contacto->telefono : 'N/A',
            $contacto ? $contacto->correo : 'N/A',
            $diasRestantes
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '9D2449']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10,  // ID
            'B' => 15,  // PV Número
            'C' => 40,  // Razón Social
            'D' => 15,  // RFC
            'E' => 15,  // Tipo Persona
            'F' => 15,  // Estado Padrón
            'G' => 15,  // Fecha Alta
            'H' => 15,  // Fecha Vencimiento
            'I' => 20,  // Estado Geográfico
            'J' => 20,  // Municipio
            'K' => 30,  // Actividades
            'L' => 25,  // Contacto
            'M' => 15,  // Teléfono
            'N' => 25,  // Correo
            'O' => 15,  // Días Restantes
        ];
    }
}
