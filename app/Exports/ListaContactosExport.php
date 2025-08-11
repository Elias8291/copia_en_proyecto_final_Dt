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

class ListaContactosExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, WithDrawings, WithEvents
{
    protected $tipoLista;

    public function __construct($tipoLista = 'todos')
    {
        $this->tipoLista = $tipoLista; // 'todos', 'por_vencer', 'activos', 'vencidos'
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
            'tramites.datosGenerales',
            'tramites.contactos'
        ]);

        switch ($this->tipoLista) {
            case 'por_vencer':
                $hoy = Carbon::now();
                $fechaLimite = $hoy->copy()->addDays(30);
                $query->where('estado_padron', 'Activo')
                      ->whereNotNull('fecha_vencimiento_padron')
                      ->where('fecha_vencimiento_padron', '>=', $hoy)
                      ->where('fecha_vencimiento_padron', '<=', $fechaLimite);
                break;
                
            case 'activos':
                $query->where('estado_padron', 'Activo');
                break;
                
            case 'vencidos':
                $query->where('estado_padron', 'Vencido');
                break;
                
            default:
                // Todos los proveedores
                break;
        }

        return $query->orderBy('estado_padron', 'asc')
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
            'Estado Padrón',
            'Nombre Contacto',
            'Cargo',
            'Teléfono',
            'Correo Electrónico',
            'Teléfono Empresa',
            'Correo Empresa',
            'Fecha Vencimiento',
            'Prioridad Contacto'
        ];
    }

    /**
     * @param mixed $proveedor
     * @return array
     */
    public function map($proveedor): array
    {
        $ultimoTramite = $proveedor->tramites->first();
        $datosGenerales = $ultimoTramite ? $ultimoTramite->datosGenerales->first() : null;
        $contacto = $ultimoTramite ? $ultimoTramite->contactos->first() : null;

        // Determinar prioridad de contacto
        $prioridadContacto = 'Normal';
        if ($proveedor->fecha_vencimiento_padron && $proveedor->estado_padron === 'Activo') {
            $fechaVencimiento = Carbon::parse($proveedor->fecha_vencimiento_padron);
            $diasRestantes = Carbon::now()->diffInDays($fechaVencimiento, false);
            
            if ($diasRestantes <= 7) {
                $prioridadContacto = 'URGENTE';
            } elseif ($diasRestantes <= 15) {
                $prioridadContacto = 'Alta';
            } elseif ($diasRestantes <= 30) {
                $prioridadContacto = 'Media';
            }
        } elseif ($proveedor->estado_padron === 'Vencido') {
            $prioridadContacto = 'CRÍTICA';
        }

        return [
            $proveedor->id,
            $proveedor->razon_social ?? 'N/A',
            $proveedor->rfc ?? 'N/A',
            $proveedor->estado_padron ?? 'Pendiente',
            $contacto->nombre_contacto ?? 'Sin contacto',
            $contacto->cargo ?? 'N/A',
            $contacto->telefono ?? 'N/A',
            $contacto->correo ?? 'N/A',
            $datosGenerales->telefono ?? 'N/A',
            $datosGenerales->email ?? 'N/A',
            $proveedor->fecha_vencimiento_padron ? Carbon::parse($proveedor->fecha_vencimiento_padron)->format('d/m/Y') : 'N/A',
            $prioridadContacto
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            'A:L' => [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'font' => [
                    'size' => 10,
                ],
            ],
            'A:A' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'D:D' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'K:K' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'L:L' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
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
            'D' => 12,  // Estado Padrón
            'E' => 20,  // Nombre Contacto
            'F' => 18,  // Cargo
            'G' => 15,  // Teléfono
            'H' => 25,  // Correo Electrónico
            'I' => 15,  // Teléfono Empresa
            'J' => 25,  // Correo Empresa
            'K' => 15,  // Fecha Vencimiento
            'L' => 15,  // Prioridad
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        switch ($this->tipoLista) {
            case 'por_vencer':
                return 'Contactos Por Vencer';
            case 'activos':
                return 'Contactos Activos';
            case 'vencidos':
                return 'Contactos Vencidos';
            default:
                return 'Lista Completa Contactos';
        }
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
                
                $tituloReporte = match($this->tipoLista) {
                    'por_vencer' => 'Lista de Contactos - Proveedores por Vencer',
                    'activos' => 'Lista de Contactos - Proveedores Activos',
                    'vencidos' => 'Lista de Contactos - Proveedores Vencidos',
                    default => 'Lista Completa de Contactos'
                };
                
                $sheet->setCellValue('D1', 'GOBIERNO DEL ESTADO DE OAXACA');
                $sheet->setCellValue('D2', 'PADRÓN DE PROVEEDORES');
                $sheet->setCellValue('D3', $tituloReporte);
                $sheet->setCellValue('D4', 'Fecha de generación: ' . now()->format('d/m/Y H:i:s'));
                
                $sheet->mergeCells('D1:L1');
                $sheet->mergeCells('D2:L2'); 
                $sheet->mergeCells('D3:L3');
                $sheet->mergeCells('D4:L4');
                
                $sheet->getStyle('D1:L4')->applyFromArray([
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
                
                $sheet->getStyle('A6:L6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '000000']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A6:L' . $lastRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ]);
            },
        ];
    }
}
