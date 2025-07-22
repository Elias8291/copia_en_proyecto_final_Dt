<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SATScraperService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class QRExtractorController extends Controller
{
    protected $satScraperService;

    public function __construct(SATScraperService $satScraperService)
    {
        $this->satScraperService = $satScraperService;
    }

    /**
     * Extrae URL de código QR desde un archivo PDF
     */
    public function extractQrFromPdf(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'pdf' => 'required|file|mimes:pdf|max:5120', // 5MB máximo
            ]);

            $file = $request->file('pdf');

            // Guardar temporalmente el archivo
            $tempPath = $file->store('temp', 'local');
            $fullPath = storage_path('app/'.$tempPath);

            try {
                // Usar Python script para extraer QR del PDF
                $qrUrl = $this->extractQrUsingPython($fullPath);

                if ($qrUrl) {
                    // Scrapear datos del SAT usando la URL extraída
                    $satData = $this->satScraperService->extractDataFromQRUrl($qrUrl);

                    return response()->json([
                        'success' => true,
                        'url' => $qrUrl,
                        'sat_data' => $satData,
                        'message' => 'Código QR extraído y datos procesados exitosamente',
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'error' => 'No se encontró código QR en el PDF',
                    ], 404);
                }
            } finally {
                // Limpiar archivo temporal
                Storage::disk('local')->delete($tempPath);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Archivo inválido. Debe ser un PDF de máximo 5MB.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error extrayendo QR del PDF: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Error interno del servidor al procesar el PDF',
            ], 500);
        }
    }

    /**
     * Extrae QR usando script de Python
     */
    private function extractQrUsingPython(string $pdfPath): ?string
    {
        try {
            $pythonScript = app_path('Python/qr_extractor.py');

            // Verificar que el script existe
            if (! file_exists($pythonScript)) {
                throw new \Exception('Script de Python no encontrado');
            }

            // Ejecutar script de Python (usar 'py' en Windows)
            $command = "py \"$pythonScript\" \"$pdfPath\"";
            $output = shell_exec($command);

            if ($output === null) {
                throw new \Exception('Error ejecutando script de Python');
            }

            $result = json_decode(trim($output), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Respuesta inválida del script de Python');
            }

            return $result['success'] ? $result['url'] : null;
        } catch (\Exception $e) {
            Log::error('Error en script Python: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Método alternativo usando JavaScript/Node.js (si está disponible)
     */
    private function extractQrUsingNode(string $pdfPath): ?string
    {
        try {
            $nodeScript = app_path('JavaScript/qr_extractor.js');

            if (! file_exists($nodeScript)) {
                return null;
            }

            $command = "node \"$nodeScript\" \"$pdfPath\"";
            $output = shell_exec($command);

            if ($output === null) {
                return null;
            }

            $result = json_decode(trim($output), true);

            return $result['success'] ? $result['url'] : null;
        } catch (\Exception $e) {
            Log::error('Error en script Node.js: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Scrapea datos del SAT directamente desde una URL
     */
    public function scrapeFromUrl(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'url' => 'required|url',
            ]);

            $url = $request->input('url');

            // Verificar que sea una URL del SAT
            if (! str_contains($url, 'siat.sat.gob.mx')) {
                return response()->json([
                    'success' => false,
                    'error' => 'La URL debe ser del sitio oficial del SAT',
                ], 400);
            }

            $satData = $this->satScraperService->extractDataFromQRUrl($url);

            return response()->json([
                'success' => true,
                'url' => $url,
                'sat_data' => $satData,
                'message' => 'Datos extraídos exitosamente',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'URL inválida',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error scrapeando datos del SAT: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Error interno del servidor al procesar los datos',
            ], 500);
        }
    }
}
