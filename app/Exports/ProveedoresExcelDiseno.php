<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ProveedoresExcelDiseno implements WithDrawings, WithEvents
{
    protected $filtros;
    protected $columnasSeleccionadas;

    public function __construct($filtros = [], $columnasSeleccionadas = [])
    {
        $this->filtros = $filtros;
        $this->columnasSeleccionadas = $columnasSeleccionadas;
    }

    public function drawings()
    {
        $dibujo = new Drawing();
        $dibujo->setName('Logo');
        $dibujo->setDescription('Logo Gobierno Oaxaca');
        $dibujo->setPath(public_path('images/logoColor.png'));
        $dibujo->setHeight(35);
        $dibujo->setCoordinates('A1'); 
        $dibujo->setOffsetX(5);
        $dibujo->setOffsetY(-135);

        return [$dibujo];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $hoja = $event->sheet->getDelegate();
                
                $infoFiltros = $this->generarInfoFiltros();
                $ultimaColumna = $this->obtenerUltimaColumna();
                
                $hoja->insertNewRowBefore(1, 6);
                
                $hoja->setCellValue('B1', 'GOBIERNO DEL ESTADO DE OAXACA');
                $hoja->setCellValue('A2', 'PADRÓN DE PROVEEDORES');
                $hoja->setCellValue('A3', $infoFiltros['titulo']);
                $hoja->setCellValue('A4', 'Fecha de generación: ' . now()->format('d/m/Y'));
                
                $encabezados = [
                    'id' => 'ID',
                    'pv_numero' => 'PV Número',
                    'razon_social' => 'Razón Social',
                    'rfc' => 'RFC',
                    'tipo_persona' => 'Tipo Persona',
                    'estado_padron' => 'Estado Padrón',
                    'fecha_alta_padron' => 'Fecha Alta',
                    'fecha_vencimiento_padron' => 'Fecha Vencimiento',
                    'fecha_inicio' => 'Fecha Inicio',
                    'estado_geografico' => 'Estado Geográfico',
                    'municipio' => 'Municipio',
                    'actividades' => 'Actividades',
                    'contacto' => 'Contacto',
                    'telefono' => 'Teléfono',
                    'correo' => 'Correo',
                    'domicilio' => 'Domicilio',
                    'dias_restantes' => 'Días Restantes'
                ];
                
                $letra = 'A';
                foreach ($this->columnasSeleccionadas as $columna) {
                    $encabezado = $encabezados[$columna] ?? ucfirst(str_replace('_', ' ', $columna));
                    $hoja->setCellValue("{$letra}6", $encabezado);
                    $letra++;
                }
                
                $hoja->getStyle('B1')->getFont()->setBold(true)->setSize(16)->getColor()->setRGB('000000');
                $hoja->getStyle('A2:A4')->getFont()->setBold(true);
                $hoja->getStyle('A2')->getFont()->setSize(14)->getColor()->setRGB('000000');
                $hoja->getStyle('A3')->getFont()->setSize(12)->getColor()->setRGB('666666');
                $hoja->getStyle('A4')->getFont()->setSize(10)->getColor()->setRGB('666666');
                
                $hoja->mergeCells("B1:{$ultimaColumna}1");
                $hoja->mergeCells("A2:{$ultimaColumna}2");
                $hoja->mergeCells("A3:{$ultimaColumna}3");
                $hoja->mergeCells("A4:{$ultimaColumna}4");
                
                $hoja->getStyle("B1:{$ultimaColumna}1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $hoja->getStyle("A2:{$ultimaColumna}4")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $hoja->getRowDimension(1)->setRowHeight(45);
                $hoja->getRowDimension(2)->setRowHeight(25);
                $hoja->getRowDimension(3)->setRowHeight(20);
                $hoja->getRowDimension(4)->setRowHeight(18);
                $hoja->getRowDimension(5)->setRowHeight(10);
                
                $filaColumnas = 6;
                $rangoEncabezados = "A{$filaColumnas}:{$ultimaColumna}{$filaColumnas}";
                
                $hoja->getStyle($rangoEncabezados)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new Color('000000'));
                
                $hoja->getStyle($rangoEncabezados)
                    ->getFont()
                    ->setColor(new Color('FFFFFF'))
                    ->setBold(true)
                    ->setSize(11);
                
                $hoja->getStyle($rangoEncabezados)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                    
                $hoja->getRowDimension(6)->setRowHeight(22);
                
                $ultimaFila = $hoja->getHighestRow();
                if ($ultimaFila > 6) {
                    $hoja->getStyle("A6:{$ultimaColumna}{$ultimaFila}")
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }
                
                if ($ultimaFila > 6) {
                    $rangoDatos = "A7:{$ultimaColumna}{$ultimaFila}";
                    $hoja->getStyle($rangoDatos)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->setStartColor(new Color('FFFFFF'));
                        
                    $hoja->getStyle($rangoDatos)
                        ->getFont()
                        ->setColor(new Color('000000'))
                        ->setBold(false)
                        ->setSize(10);
                }
            },
        ];
    }

    private function generarInfoFiltros()
    {
        $filtrosAplicados = [];
        
        if (!empty($this->filtros['search'])) {
            $filtrosAplicados[] = "Búsqueda: {$this->filtros['search']}";
        }
        
        if (!empty($this->filtros['estado'])) {
            $filtrosAplicados[] = "Estado: {$this->filtros['estado']}";
        }
        
        if (!empty($this->filtros['tipo_persona'])) {
            $filtrosAplicados[] = "Tipo: {$this->filtros['tipo_persona']}";
        }
        
        if (!empty($this->filtros['año'])) {
            $filtrosAplicados[] = "Año: {$this->filtros['año']}";
        }
        
        if (!empty($this->filtros['año_trimestre']) && !empty($this->filtros['trimestre'])) {
            $filtrosAplicados[] = "Período: {$this->filtros['año_trimestre']} Q{$this->filtros['trimestre']}";
        }
        
        if (!empty($this->filtros['proximidad_vencimiento'])) {
            if ($this->filtros['proximidad_vencimiento'] === 'custom' && !empty($this->filtros['dias_personalizados'])) {
                $filtrosAplicados[] = "Próximos a vencer: {$this->filtros['dias_personalizados']} días";
            } else {
                $filtrosAplicados[] = "Próximos a vencer: {$this->filtros['proximidad_vencimiento']} días";
            }
        }
        
        $titulo = empty($filtrosAplicados) ? 'Todos los Proveedores' : implode(' | ', $filtrosAplicados);
        
        return ['titulo' => $titulo];
    }

    private function obtenerUltimaColumna()
    {
        $numColumnas = count($this->columnasSeleccionadas);
        return chr(64 + $numColumnas);
    }
}
