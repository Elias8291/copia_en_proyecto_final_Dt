<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SATScraperService
{
    /**
     * Extrae datos del SAT desde la URL del QR
     */
    public function extractDataFromQRUrl($url)
    {
        try {
            // Hacer request a la URL del SAT
            $response = Http::timeout(30)
                ->withoutVerifying() // Deshabilitar verificación SSL para el SAT
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'es-MX,es;q=0.8,en;q=0.5',
                    'Accept-Encoding' => 'gzip, deflate',
                    'Connection' => 'keep-alive',
                    'Upgrade-Insecure-Requests' => '1',
                ])
                ->get($url);

            if (! $response->successful()) {
                throw new \Exception('No se pudo acceder a la URL del SAT');
            }

            $html = $response->body();

            // Parsear el HTML y extraer datos
            return $this->parseHTML($html);

        } catch (\Exception $e) {
            Log::error('Error en SATScraperService: '.$e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Parsea el HTML del SAT y extrae los datos estructurados
     */
    private function parseHTML($html)
    {
        $data = [
            'success' => true,
            'identificacion' => [],
            'ubicacion' => [],
            'caracteristicas_fiscales' => [],
            'tipo_persona' => null,
        ];

        try {
            // Crear DOMDocument
            $dom = new \DOMDocument;
            libxml_use_internal_errors(true);
            $dom->loadHTML($html);
            libxml_clear_errors();

            $xpath = new \DOMXPath($dom);

            // Extraer RFC - Múltiples métodos de búsqueda
            $data['rfc'] = $this->extractRFC($xpath);

            // Log para debug
            Log::info('RFC extraído: '.($data['rfc'] ?: 'No encontrado'));

            // Buscar todas las tablas de datos
            $dataTables = $xpath->query("//tbody[@class='ui-datatable-data ui-widget-content']");

            foreach ($dataTables as $table) {
                $rows = $xpath->query(".//tr[contains(@class, 'ui-widget-content')]", $table);
                $sectionData = [];

                foreach ($rows as $row) {
                    $cells = $xpath->query(".//td[@role='gridcell']", $row);
                    if ($cells->length >= 2) {
                        $label = trim($cells->item(0)->textContent);
                        $value = trim($cells->item(1)->textContent);

                        // Limpiar label (remover ":" y texto en bold)
                        $label = preg_replace('/[:\s]*$/', '', $label);
                        $label = strip_tags($label);

                        if (! empty($label) && ! empty($value)) {
                            $sectionData[$this->normalizeKey($label)] = $value;
                        }
                    }
                }

                // Clasificar datos por sección
                if (! empty($sectionData)) {
                    $this->classifyData($sectionData, $data);
                }
            }

            // Determinar tipo de persona
            $data['tipo_persona'] = $this->determinePersonType($data);

            // Extraer CURP con validación mejorada
            $data['curp_validado'] = $this->extractCURP($data);

            // Normalizar datos para el formulario
            $data['form_data'] = $this->normalizeForForm($data);

            return $data;

        } catch (\Exception $e) {
            Log::error('Error parseando HTML del SAT: '.$e->getMessage());

            return [
                'success' => false,
                'error' => 'Error procesando datos del SAT: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Extrae el RFC usando múltiples estrategias
     */
    private function extractRFC($xpath)
    {
        $rfc = null;

        // Estrategia 1: Buscar en elementos li con clase ui-li-static
        $rfcElements = $xpath->query("//li[contains(@class, 'ui-li-static')]");
        foreach ($rfcElements as $element) {
            $text = $element->textContent;
            // Buscar patrón: "El RFC: XXXXX, tiene asociada..."
            if (preg_match('/El RFC:\s*([A-Z]{3,4}[0-9]{6}[A-Z0-9]{2,3}),?\s*tiene\s*asociada/i', $text, $matches)) {
                $rfc = $matches[1];
                Log::info('RFC encontrado (método 1): '.$rfc);
                break;
            }
            // Patrón alternativo: "RFC: XXXXX"
            if (preg_match('/RFC:\s*([A-Z]{3,4}[0-9]{6}[A-Z0-9]{2,3})/i', $text, $matches)) {
                $rfc = $matches[1];
                Log::info('RFC encontrado (método 1b): '.$rfc);
                break;
            }
        }

        // Estrategia 2: Buscar en cualquier texto que contenga RFC
        if (! $rfc) {
            $allTextElements = $xpath->query("//text()[contains(., 'RFC')]");
            foreach ($allTextElements as $textNode) {
                $text = $textNode->textContent;
                if (preg_match('/RFC:\s*([A-Z]{3,4}[0-9]{6}[A-Z0-9]{2,3})/i', $text, $matches)) {
                    $rfc = $matches[1];
                    Log::info('RFC encontrado (método 2): '.$rfc);
                    break;
                }
            }
        }

        // Estrategia 3: Buscar en tablas de datos específicamente
        if (! $rfc) {
            $rfcCells = $xpath->query("//td[contains(text(), 'RFC')]/../td[2]");
            foreach ($rfcCells as $cell) {
                $text = trim($cell->textContent);
                if (preg_match('/^([A-Z]{3,4}[0-9]{6}[A-Z0-9]{2,3})$/', $text, $matches)) {
                    $rfc = $matches[1];
                    Log::info('RFC encontrado (método 3): '.$rfc);
                    break;
                }
            }
        }

        // Estrategia 4: Buscar en cualquier parte del documento con patrón RFC válido
        if (! $rfc) {
            $bodyText = $xpath->query('//body')->item(0)->textContent ?? '';
            if (preg_match_all('/\b([A-Z]{3,4}[0-9]{6}[A-Z0-9]{2,3})\b/', $bodyText, $matches)) {
                // Tomar el primer RFC válido encontrado
                $rfc = $matches[1][0];
                Log::info('RFC encontrado (método 4): '.$rfc);
            }
        }

        return $rfc;
    }

    /**
     * Extrae el CURP con mejor precisión
     */
    private function extractCURP($data)
    {
        $curp = null;

        // Buscar CURP en los datos de identificación
        if (isset($data['identificacion']['curp'])) {
            $curp = $data['identificacion']['curp'];
        }

        // Validar formato CURP (18 caracteres)
        if ($curp && preg_match('/^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z][0-9]$/', $curp)) {
            return $curp;
        }

        return null;
    }

    /**
     * Normaliza las claves para consistencia
     */
    private function normalizeKey($key)
    {
        $key = strtolower($key);
        $key = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'n'], $key);
        $key = preg_replace('/[^a-z0-9\s]/', '', $key);
        $key = preg_replace('/\s+/', '_', trim($key));

        return $key;
    }

    /**
     * Clasifica los datos en las secciones apropiadas
     */
    private function classifyData($sectionData, &$data)
    {
        // Palabras clave para identificación
        $identificationKeys = [
            'curp', 'nombre', 'apellido_paterno', 'apellido_materno',
            'fecha_nacimiento', 'denominacion_o_razon_social',
            'regimen_de_capital', 'fecha_de_constitucion',
        ];

        // Palabras clave para ubicación
        $locationKeys = [
            'entidad_federativa', 'municipio_o_delegacion', 'localidad',
            'colonia', 'tipo_de_vialidad', 'nombre_de_la_vialidad',
            'numero_exterior', 'numero_interior', 'cp', 'correo_electronico', 'al',
        ];

        // Palabras clave para características fiscales
        $fiscalKeys = [
            'regimen', 'fecha_de_alta', 'fecha_de_inicio_de_operaciones',
            'situacion_del_contribuyente', 'fecha_del_ultimo_cambio_de_situacion',
        ];

        foreach ($sectionData as $key => $value) {
            if (in_array($key, $identificationKeys)) {
                $data['identificacion'][$key] = $value;
            } elseif (in_array($key, $locationKeys)) {
                $data['ubicacion'][$key] = $value;
            } elseif (in_array($key, $fiscalKeys)) {
                $data['caracteristicas_fiscales'][$key] = $value;
            } else {
                // Si no se puede clasificar, agregarlo a identificación por defecto
                $data['identificacion'][$key] = $value;
            }
        }
    }

    /**
     * Determina si es persona física o moral
     */
    private function determinePersonType($data)
    {
        // Si tiene CURP, es persona física
        if (isset($data['identificacion']['curp'])) {
            return 'fisica';
        }

        // Si tiene denominación o razón social, es persona moral
        if (isset($data['identificacion']['denominacion_o_razon_social'])) {
            return 'moral';
        }

        // Si tiene régimen de capital, es persona moral
        if (isset($data['identificacion']['regimen_de_capital'])) {
            return 'moral';
        }

        return 'fisica'; // Por defecto
    }

    /**
     * Normaliza los datos para el formulario
     */
    private function normalizeForForm($data)
    {
        $formData = [
            'rfc' => $data['rfc'] ?? '',
            'razon_social' => '',
            'regimen_fiscal' => $data['caracteristicas_fiscales']['regimen'] ?? '',
            'fecha_inicio' => $data['caracteristicas_fiscales']['fecha_de_inicio_de_operaciones'] ?? '',
            'estatus' => $data['caracteristicas_fiscales']['situacion_del_contribuyente'] ?? '',
            'fecha_actualizacion' => $data['caracteristicas_fiscales']['fecha_del_ultimo_cambio_de_situacion'] ?? '',
            'tipo_persona' => $data['tipo_persona'],
            'curp' => $data['curp_validado'] ?? $data['identificacion']['curp'] ?? '',
            'email' => $data['ubicacion']['correo_electronico'] ?? '',
            'codigo_postal' => $data['ubicacion']['cp'] ?? '',
            'entidad_federativa' => $data['ubicacion']['entidad_federativa'] ?? '',
            'municipio' => $data['ubicacion']['municipio_o_delegacion'] ?? '',
            'colonia' => $data['ubicacion']['colonia'] ?? $data['ubicacion']['localidad'] ?? '',
            'calle' => $data['ubicacion']['nombre_de_la_vialidad'] ?? '',
            'numero_exterior' => $data['ubicacion']['numero_exterior'] ?? '',
            'numero_interior' => $data['ubicacion']['numero_interior'] ?? '',
        ];

        // Log para debug
        Log::info('Datos normalizados para formulario:', [
            'rfc' => $formData['rfc'],
            'curp' => $formData['curp'],
            'tipo_persona' => $formData['tipo_persona'],
        ]);

        // Determinar razón social según tipo de persona
        if ($data['tipo_persona'] === 'moral') {
            $formData['razon_social'] = $data['identificacion']['denominacion_o_razon_social'] ?? '';
        } else {
            // Para persona física, concatenar nombre completo
            $nombre = $data['identificacion']['nombre'] ?? '';
            $apellidoP = $data['identificacion']['apellido_paterno'] ?? '';
            $apellidoM = $data['identificacion']['apellido_materno'] ?? '';
            $formData['razon_social'] = trim("$nombre $apellidoP $apellidoM");
        }

        return $formData;
    }
}
