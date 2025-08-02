<?php

namespace App\Exports;

use App\Services\Proveedores\BusquedaProveedorService;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;

class ProveedoresExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithCustomStartCell, WithDrawings
{
    private array $filtros;
    private BusquedaProveedorService $busquedaService;
    private array $data;
    private int $totalFilas;
    private ?array $columnasSeleccionadas;
    private array $columnasTitulos;

    public function __construct(array $filtros, BusquedaProveedorService $busquedaService, ?array $columnasSeleccionadas = null)
    {
        $this->filtros = $filtros;
        $this->busquedaService = $busquedaService;
        $this->columnasSeleccionadas = $columnasSeleccionadas;
        
        // Definir mapeo de títulos de columnas
        $this->columnasTitulos = [
            'rfc' => 'RFC',
            'razon_social' => 'Razón Social',
            'tipo_persona' => 'Tipo Persona',
            'estado_padron' => 'Estado Padrón',
            'fecha_vencimiento' => 'Fecha Vencimiento',
            'nombre_contacto' => 'Nombre Contacto',
            'correo' => 'Correo',
            'numero_proveedor' => 'Número Proveedor',
            'fecha_registro' => 'Fecha Registro'
        ];
        
        $this->data = $this->busquedaService->obtenerParaExportacion($filtros, $columnasSeleccionadas);
        $this->totalFilas = count($this->data);
    }

    public function array(): array
    {
        return $this->data;
    }

    public function startCell(): string
    {
        return 'A6'; // Empezar en la fila 6 para dejar espacio para el título
    }

    private function getLastColumn(): string
    {
        $totalColumnas = count($this->headings());
        // Convertir número a letra de columna (A=1, B=2, etc.)
        return chr(64 + $totalColumnas);
    }

    public function headings(): array
    {
        if ($this->columnasSeleccionadas) {
            return array_map(function($columna) {
                return $this->columnasTitulos[$columna] ?? $columna;
            }, $this->columnasSeleccionadas);
        }
        
        return [
            'RFC',
            'Razón Social',
            'Tipo Persona',
            'Estado Padrón',
            'Fecha Vencimiento',
            'Nombre Contacto',
            'Correo',
            'Número Proveedor',
            'Fecha Registro'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Título principal
        $sheet->setCellValue('A1', 'GOBIERNO DEL ESTADO DE OAXACA');
        $sheet->setCellValue('A2', 'PADRÓN DE PROVEEDORES');
        
        // Determinar el período según filtros
        $periodo = $this->obtenerPeriodoTitulo();
        $sheet->setCellValue('A3', $periodo);
        
        // Fusionar celdas para el título (solo hasta la columna I)
        $lastCol = $this->getLastColumn();
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->mergeCells("A3:{$lastCol}3");
        
        // Información final
        $filaFinal = 6 + $this->totalFilas + 3; // 6 (inicio) + datos + 3 espacios
        $sheet->setCellValue('A' . $filaFinal, 'Área responsable de integrar la información: Dirección de Recursos Materiales');
        $sheet->setCellValue('A' . ($filaFinal + 1), 'Fecha de corte: ' . Carbon::now()->format('d/m/Y H:i'));
        
        $lastCol = $this->getLastColumn();
        $sheet->mergeCells('A' . $filaFinal . ':' . $lastCol . $filaFinal);
        $sheet->mergeCells('A' . ($filaFinal + 1) . ':' . $lastCol . ($filaFinal + 1));

        return [
            // Estilos del título
            '1:3' => [
                'font' => [
                    'bold' => true,
                    'size' => 14,
                    'color' => ['rgb' => '000000']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
            // Estilo para los encabezados de datos (fila 6)
            '6:6' => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '000000']
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
            ],
            // Estilo para las filas de datos
            'A7:' . $this->getLastColumn() . (6 + $this->totalFilas) => [
                'font' => [
                    'size' => 10,
                    'color' => ['rgb' => '000000']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
            // Estilo para información final
            $filaFinal . ':' . ($filaFinal + 1) => [
                'font' => [
                    'bold' => true,
                    'size' => 10,
                    'color' => ['rgb' => '000000']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]
        ];
    }

    private function obtenerPeriodoTitulo(): string
    {
        if (!empty($this->filtros['trimestre'])) {
            $año = $this->filtros['año'] ?? Carbon::now()->year;
            $trimestres = [
                'Q1' => 'PRIMER TRIMESTRE',
                'Q2' => 'SEGUNDO TRIMESTRE', 
                'Q3' => 'TERCER TRIMESTRE',
                'Q4' => 'CUARTO TRIMESTRE'
            ];
            
            return ($trimestres[$this->filtros['trimestre']] ?? 'TRIMESTRE') . ' ' . $año;
        }
        
        if (!empty($this->filtros['año'])) {
            return 'AÑO ' . $this->filtros['año'];
        }
        
        if (!empty($this->filtros['fecha_inicio']) || !empty($this->filtros['fecha_fin'])) {
            $inicio = !empty($this->filtros['fecha_inicio']) ? 
                Carbon::parse($this->filtros['fecha_inicio'])->format('d/m/Y') : 'INICIO';
            $fin = !empty($this->filtros['fecha_fin']) ? 
                Carbon::parse($this->filtros['fecha_fin'])->format('d/m/Y') : 'FIN';
            
            return "PERÍODO: {$inicio} - {$fin}";
        }
        
        return 'REPORTE GENERAL ' . Carbon::now()->year;
    }

    public function columnWidths(): array
    {
        $anchos = [
            'rfc' => 15,
            'razon_social' => 40,
            'tipo_persona' => 15,
            'estado_padron' => 15,
            'fecha_vencimiento' => 18,
            'nombre_contacto' => 30,
            'correo' => 35,
            'numero_proveedor' => 18,
            'fecha_registro' => 20,
        ];
        
        $columnas = $this->columnasSeleccionadas ?: array_keys($anchos);
        $result = [];
        
        foreach ($columnas as $index => $columna) {
            $letra = chr(65 + $index); // A, B, C, etc.
            $result[$letra] = $anchos[$columna] ?? 15;
        }
        
        return $result;
    }

    public function title(): string
    {
        $titulo = 'Proveedores Oaxaca';
        
        if (!empty($this->filtros['trimestre'])) {
            $año = $this->filtros['año'] ?? Carbon::now()->year;
            $titulo .= " {$this->filtros['trimestre']} {$año}";
        } elseif (!empty($this->filtros['año'])) {
            $titulo .= " {$this->filtros['año']}";
        } else {
            $titulo .= ' ' . Carbon::now()->year;
        }
        
        return $titulo;
    }

    public function drawings()
    {
        $logoPath = public_path('images/logo_administracion.png');
        
        // Verificar que el logo existe
        if (!file_exists($logoPath)) {
            return [];
        }

        $drawing = new Drawing();
        $drawing->setName('Logo Administración');
        $drawing->setDescription('Logo del Gobierno del Estado de Oaxaca');
        $drawing->setPath($logoPath);
        $drawing->setHeight(80); // Altura en píxeles
        $drawing->setWidth(120); // Ancho en píxeles
        
        // Posicionar el logo en las coordenadas deseadas
        // Colocar en la esquina superior derecha del título
        $lastCol = $this->getLastColumn();
        $logoColumn = chr(ord($lastCol)); // Usar la última columna
        $drawing->setCoordinates($logoColumn . '1');
        
        // Configurar el desplazamiento para posicionamiento fino
        $drawing->setOffsetX(10); // Desplazamiento horizontal
        $drawing->setOffsetY(10); // Desplazamiento vertical
        
        return [$drawing];
    }
}