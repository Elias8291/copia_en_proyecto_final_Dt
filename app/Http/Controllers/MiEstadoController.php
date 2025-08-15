<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Proveedor;

class MiEstadoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $proveedor = Proveedor::where('usuario_id', $user->id)->first();

        $ultimoTramite = null;
        $datosCompletos = null;
        $archivosCargados = null;

        if ($proveedor) {
            // Cargar trámites y seleccionar el más reciente
            $proveedor->load(['tramites' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }]);

            $ultimoTramite = $proveedor->tramites->first();

            if ($ultimoTramite) {
                // Cargar relaciones necesarias del último trámite
                $ultimoTramite->load([
                    'datosGenerales',
                    'direcciones.estado',
                    'direcciones.coordenada',
                    'contactos',
                    'actividades.actividad',
                    'accionistas',
                    'apoderadosLegales.instrumentoNotarial.estado',
                    'datosConstitutivos.instrumentoNotarial.estado',
                    'archivos.catalogoArchivo',
                    'oficios'
                ]);

                $direccion = $ultimoTramite->direcciones->first();
                $datosGenerales = $ultimoTramite->datosGenerales->first();
                $contacto = $ultimoTramite->contactos->first();

                $datosCompletos = [
                    'datos_generales' => $datosGenerales ? array_merge($datosGenerales->toArray(), [
                        'nombre_contacto' => $contacto->nombre_contacto ?? '',
                        'cargo' => $contacto->cargo ?? '',
                        'telefono_contacto' => $contacto->telefono ?? '',
                        'correo_contacto' => $contacto->correo ?? '',
                    ]) : [],
                    'direccion' => $direccion ? [
                        'codigo_postal' => $direccion->codigo_postal,
                        'estado' => $direccion->estado->nombre ?? '',
                        'estado_id' => $direccion->estado_id,
                        'municipio' => $direccion->municipio,
                        'localidad' => $direccion->localidad,
                        'asentamiento' => $direccion->asentamiento,
                        'colonia' => $direccion->asentamiento,
                        'calle' => $direccion->calle,
                        'numero_exterior' => $direccion->numero_exterior,
                        'numero_interior' => $direccion->numero_interior,
                        'entre_calle' => $direccion->entre_calle,
                        'y_calle' => $direccion->y_calle,
                        'latitud' => $direccion->coordenada->latitud ?? null,
                        'longitud' => $direccion->coordenada->longitud ?? null,
                    ] : [],
                    'actividades_economicas' => $ultimoTramite->actividades->map(function ($actividad) {
                        return [
                            'id' => $actividad->actividad_id,
                            'nombre' => $actividad->actividad->nombre ?? 'Actividad no encontrada',
                            'descripcion' => $actividad->actividad->descripcion ?? '',
                            'sector_id' => $actividad->actividad->sector_id ?? null,
                        ];
                    })->toArray(),
                    'accionistas' => $ultimoTramite->accionistas->toArray(),
                    'apoderado_legal' => $this->formatApoderadoData($ultimoTramite->apoderadosLegales->first()),
                    'constitucion' => $this->formatConstitucionData($ultimoTramite->datosConstitutivos->first()),
                ];

                // Pasar los modelos de archivos directamente para que el componente conserve estilos y nombres
                $archivosCargados = $ultimoTramite->archivos;
            }
        }

        return view('mi_estado', compact('proveedor', 'ultimoTramite', 'datosCompletos', 'archivosCargados'));
    }

    private function formatApoderadoData($apoderado)
    {
        if (!$apoderado) {
            return [];
        }

        $instrumentoNotarial = $apoderado->instrumentoNotarial;

        return [
            'nombre_apoderado' => $apoderado->nombre_apoderado,
            'rfc' => $apoderado->rfc,
            'numero_escritura_poder' => $instrumentoNotarial->numero_escritura ?? '',
            'fecha_poder' => $instrumentoNotarial->fecha_constitucion ?? '',
            'nombre_notario_poder' => $instrumentoNotarial->nombre_notario ?? '',
            'numero_notario_poder' => $instrumentoNotarial->numero_notario ?? '',
            'numero_escritura_constitutiva_poder' => $apoderado->numero_escritura_constitutiva_poder ?? '',
            'numero_registro_publico_poder' => $apoderado->numero_registro_publico_poder ?? '',
            'fecha_inscripcion_poder' => $apoderado->fecha_inscripcion_poder ?? '',
            'estado_id' => $instrumentoNotarial->estado_id ?? '',
            'estado_nombre' => $instrumentoNotarial->estado->nombre ?? '',
        ];
    }

    private function formatConstitucionData($constitutivo)
    {
        if (!$constitutivo) {
            return [];
        }

        $instrumentoNotarial = $constitutivo->instrumentoNotarial;

        return [
            'estado_id' => $instrumentoNotarial->estado_id ?? '',
            'estado_nombre' => $instrumentoNotarial->estado->nombre ?? '',
            'numero_escritura' => $instrumentoNotarial->numero_escritura ?? '',
            'numero_escritura_constitutiva' => $instrumentoNotarial->numero_escritura_constitutiva ?? '',
            'fecha_constitucion' => $instrumentoNotarial->fecha_constitucion ?? '',
            'nombre_notario' => $instrumentoNotarial->nombre_notario ?? '',
            'numero_notario' => $instrumentoNotarial->numero_notario ?? '',
            'numero_registro_publico' => $instrumentoNotarial->numero_registro_publico ?? '',
            'fecha_inscripcion' => $instrumentoNotarial->fecha_inscripcion ?? '',
        ];
    }
} 