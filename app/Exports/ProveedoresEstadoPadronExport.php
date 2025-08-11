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

class ProveedoresEstadoPadronExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, WithDrawings, WithEvents
{
    protected $estadoFiltro;

    public function __construct($estadoFiltro = null)
    {
        $this->estadoFiltro = $estadoFiltro;
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
            'tramites.actividades.actividad'
        ]);

        if ($this->estadoFiltro) {
            $query->where('estado_padron', $this->estadoFiltro);
        }

        return $query->orderBy('estado_padron', 'asc')
                     ->orderBy('fecha_vencimiento_padron', 'asc')
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
            'Fecha Alta',
            'Fecha Vencimiento',
            'Días Transcurridos/Restantes',
            'Actividades Principales'
        ];
    }

    /**
     * @param mixed $proveedor
     * @return array
     */
    public function map($proveedor): array
    {
        $ultimoTramite = $proveedor->tramites->first();
        
        // Obtener actividades principales (máximo 3)
        $actividades = 'Sin actividades';
        if ($ultimoTramite && $ultimoTramite->actividades->isNotEmpty()) {
            $actividadesLista = $ultimoTramite->actividades->take(3)->map(function($actividad) {
                return $actividad->actividad->nombre ?? 'N/A';
            });
            $actividades = $actividadesLista->implode(', ');
        }

        // Calcular tiempo transcurrido/restante
        $tiempoCalculado = 'N/A';
        if ($proveedor->fecha_vencimiento_padron) {
            $fechaVencimiento = Carbon::parse($proveedor->fecha_vencimiento_padron);
            $ahora = Carbon::now();
            $diferenciaDias = $ahora->diffInDays($fechaVencimiento, false);

            if ($diferenciaDias < 0) {
                // Vencido
                $diasVencidos = abs($diferenciaDias);
                if ($diasVencidos >= 30) {
                    $meses = floor($diasVencidos / 30);
                    $tiempoCalculado = $meses . ($meses == 1 ? ' mes vencido' : ' meses vencidos');
                } else {
                    $tiempoCalculado = $diasVencidos . ($diasVencidos == 1 ? ' día vencido' : ' días vencidos');
                }
            } else {
                // Vigente
                if ($diferenciaDias >= 30) {
                    $meses = floor($diferenciaDias / 30);
                    $tiempoCalculado = $meses . ($meses == 1 ? ' mes restante' : ' meses restantes');
                } else {
                    $tiempoCalculado = $diferenciaDias . ($diferenciaDias == 1 ? ' día restante' : ' días restantes');
                }
            }
        }

        return [
            $proveedor->id,
            $proveedor->razon_social ?? 'N/A',
            $proveedor->rfc ?? 'N/A',
            $proveedor->tipo_persona ?? 'N/A',
            $proveedor->estado_padron ?? 'Pendiente',
            $proveedor->fecha_alta_padron ? Carbon::parse($proveedor->fecha_alta_padron)->format('d/m/Y') : 'N/A',
            $proveedor->fecha_vencimiento_padron ? Carbon::parse($proveedor->fecha_vencimiento_padron)->format('d/m/Y') : 'N/A',
            $tiempoCalculado,
            $actividades
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
            'F:F' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'G:G' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
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
            'F' => 12,  // Fecha Alta
            'G' => 15,  // Fecha Vencimiento
            'H' => 20,  // Tiempo
            'I' => 30,  // Actividades
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        $titulo = 'Proveedores por Estado';
        if ($this->estadoFiltro) {
            $titulo .= ' - ' . $this->estadoFiltro;
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
                
                $tituloReporte = $this->estadoFiltro ? 
                    'Proveedores con Estado: ' . $this->estadoFiltro :
                    'Proveedores por Estado del Padrón';
                
                $sheet->setCellValue('D1', 'GOBIERNO DEL ESTADO DE OAXACA');
                $sheet->setCellValue('D2', 'PADRÓN DE PROVEEDORES');
                $sheet->setCellValue('D3', $tituloReporte);
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
