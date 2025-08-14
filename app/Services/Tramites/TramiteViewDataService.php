<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Services\RfcProveedorService;
use App\Services\ArchivosFormatter;
use App\ViewModels\FormDataViewModel;
use RuntimeException;

class TramiteViewDataService
{
    private RfcProveedorService $rfcProveedorService;
    private CorreccionService $correccionService;
    private ArchivosFormatter $archivosFormatter;

    public function __construct(
        RfcProveedorService $rfcProveedorService,
        CorreccionService $correccionService,
        ArchivosFormatter $archivosFormatter
    ) {
        $this->rfcProveedorService = $rfcProveedorService;
        $this->correccionService = $correccionService;
        $this->archivosFormatter = $archivosFormatter;
    }

    public function prepareEditData(Tramite $tramite): array
    {
        $rfc = $this->rfcProveedorService->obtenerRfcUsuario();
        if (!$rfc || ($tramite->proveedor && $tramite->proveedor->rfc !== $rfc)) {
            throw new RuntimeException('No tiene permisos para editar este trámite');
        }

        if (!in_array($tramite->status, ['Para_Correccion', 'Rechazado'])) {
            throw new RuntimeException('Este trámite no requiere correcciones');
        }

        $tramite->load([
            'proveedor',
            'datosGenerales',
            'apoderadosLegales.instrumentoNotarial.estado',
            'accionistas',
            'contactos',
            'actividades.actividad',
            'direcciones.coordenada',
            'archivos.catalogoArchivo',
            'datosConstitutivos.instrumentoNotarial.estado',
        ]);

        $formData = [
            'datos_generales' => $tramite->datosGenerales->first() ? $tramite->datosGenerales->first()->toArray() : [],
            'actividades' => $tramite->actividades->toArray(),
            'domicilio' => $tramite->direcciones->first() ? $tramite->direcciones->first()->toArray() : [],
            'contacto' => $tramite->contactos->first() ? $tramite->contactos->first()->toArray() : [],
            'archivos' => $tramite->archivos->toArray(),
        ];

        if ($tramite->proveedor && $tramite->proveedor->tipo_persona === 'Moral') {
            $datosConstitutivos = $tramite->datosConstitutivos->first();
            $constitucionData = [];
            if ($datosConstitutivos) {
                $constitucionData = $datosConstitutivos->toArray();
                if ($datosConstitutivos->instrumentoNotarial) {
                    $instrumentoNotarial = $datosConstitutivos->instrumentoNotarial;
                    $constitucionData = array_merge($constitucionData, [
                        'numero_escritura' => $instrumentoNotarial->numero_escritura ?? '',
                        'numero_escritura_constitutiva' => $instrumentoNotarial->numero_escritura_constitutiva ?? '',
                        'fecha_constitucion' => $instrumentoNotarial->fecha_constitucion ?? '',
                        'nombre_notario' => $instrumentoNotarial->nombre_notario ?? '',
                        'numero_notario' => $instrumentoNotarial->numero_notario ?? '',
                        'estado_id' => $instrumentoNotarial->estado_id ?? '',
                        'estado_nombre' => $instrumentoNotarial->estado->nombre ?? '',
                        'numero_registro_publico' => $instrumentoNotarial->numero_registro_publico ?? '',
                        'fecha_inscripcion' => $instrumentoNotarial->fecha_inscripcion ?? '',
                    ]);
                }
            }
            $formData['constitucion'] = $constitucionData;

            $apoderado = $tramite->apoderadosLegales->first();
            $apoderadoData = [];
            if ($apoderado) {
                $apoderadoData = $apoderado->toArray();
                if ($apoderado->instrumentoNotarial) {
                    $instrumentoNotarial = $apoderado->instrumentoNotarial;
                    $apoderadoData = array_merge($apoderadoData, [
                        'numero_escritura' => $instrumentoNotarial->numero_escritura ?? '',
                        'numero_escritura_poder' => $instrumentoNotarial->numero_escritura_poder ?? ($instrumentoNotarial->numero_escritura_constitutiva ?? ''),
                        'fecha_poder' => $instrumentoNotarial->fecha_constitucion ?? '',
                        'nombre_notario_poder' => $instrumentoNotarial->nombre_notario ?? '',
                        'numero_notario_poder' => $instrumentoNotarial->numero_notario ?? '',
                        'estado_id' => $instrumentoNotarial->estado_id ?? '',
                        'estado_nombre' => $instrumentoNotarial->estado->nombre ?? '',
                        'numero_registro_publico' => $instrumentoNotarial->numero_registro_publico ?? '',
                        'fecha_inscripcion' => $instrumentoNotarial->fecha_inscripcion ?? '',
                    ]);
                }
            }
            $formData['apoderado'] = $apoderadoData;

            $formData['accionistas'] = $tramite->accionistas->toArray();
        }

        $viewModel = new FormDataViewModel($formData);

        $seccionesParaCorregir = $this->correccionService->obtenerSeccionesParaCorreccion($tramite);
        $resumenCorrecciones = $this->correccionService->obtenerResumenCorrecciones($tramite);

        $archivosRequeridos = $this->rfcProveedorService->obtenerArchivosPorTipoPersonaDirecto($tramite->proveedor->tipo_persona);

        $archivosSubidos = $this->archivosFormatter->prepararParaCotejo($tramite->archivos);

        $archivosRechazados = collect($archivosSubidos)->filter(function ($archivo) {
            return ($archivo['status'] ?? 'Pendiente') === 'Rechazado';
        })->values()->toArray();

        return [
            'tramite' => $tramite,
            'viewModel' => $viewModel,
            'seccionesParaCorregir' => $seccionesParaCorregir,
            'resumenCorrecciones' => $resumenCorrecciones,
            'archivosRequeridos' => $archivosRequeridos,
            'tipoPersona' => $tramite->proveedor->tipo_persona,
            'modoCorreccion' => true,
            'esEditable' => true,
            'archivosSubidos' => $archivosSubidos,
            'archivosRechazados' => $archivosRechazados,
        ];
    }
}


