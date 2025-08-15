<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oficio de {{ ucfirst(strtolower($tramite->tipo_tramite ?? 'Inscripción')) }} - Padrón de Proveedores (Persona Física)</title>
    <style>
        @page {
            size: 8.5in 11in;
            margin: 1cm 2.54cm 2.54cm 2.54cm; 
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            position: relative;
            background: white;
            font-size: 8pt;
            line-height: 1.4;
        }
        .logo {
            position: absolute;
            top: 0mm;
            left: 0mm;
            width: 70mm;
            height: auto;
        }
        
        .logo img {
            width: 100%;
            height: auto;
        }

        .logo-lateral {
            position: absolute;
            top: -20mm;
            right: -24mm;
            width: 36mm;
            height: 100%;
            z-index: 1;
            opacity: 0.7;
        }
        
        .logo-lateral img {
            width: 100%;
            height: auto;
        }

        .lema-constitucional {
            position: absolute;
            top: 20mm;
            left: 42mm;
            width: 116mm;
            text-align: center;
            font-style: italic;
            font-size: 8pt;
        }

        .origen-oficio-asunto-fecha {
            position: absolute;
            top: 33mm;
            left: 0mm;
            width: 160mm;
            font-size: 8pt;
            text-align: right;
            line-height: 1.2;
            font-weight: bold;
            z-index: 5;
        }

        .destinatario {
            position: absolute;
            top: 55mm;
            left: 0mm;
            width: 130mm;
            font-size: 8pt;
            font-weight: bold;
            line-height: 1.2;
        }

        .destinatario-persona-fisica {
            line-height: 1.2;
        }
        
        .contenido-principal {
            position: absolute;
            top: 78mm;
            left: 0mm;
            width: 160mm;
            font-size: 8pt;
            text-align: justify;
            line-height: 1.4;
            z-index: 5;
        }

        .firma {
            position: absolute;
            top: 200mm;
            left: 0mm;
            width: 160mm;
            text-align: center;
            font-size: 8pt;
            font-weight: bold;
            line-height: 1.5;
            z-index: 5;
        }

        .signature-space {
            height: 10mm;
        }
        
        .footer {
            position: absolute;
            bottom: 0mm;
            left: 0mm;
            width: 160mm;
            font-size: 5pt;
            font-weight: bold;
            line-height: 1.2;
        }

        .copias {
            position: absolute;
            top: 225mm;
            left: 0mm;
            font-size: 5pt;
            line-height: 1.2;
        }
        
        .qr-code {
            position: absolute;
            top: 215mm;
            left: -20mm;
            width: 22mm;
            height: 22mm;
        }
        
        .qr-code img {
            width: 100%;
            height: 100%;
        }
        


        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .font-bold { font-weight: bold; }
        .font-italic { font-style: italic; }
        
        @media print {
            body { print-color-adjust: exact; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="logo">
        <img src="{{ public_path('images/logo_administracion.png') }}" alt="Logo">
    </div>

    <div class="lema-constitucional">
            "2025, BICENTENARIO DE LA PRIMERA CONSTITUCIÓN POLÍTICA DEL ESTADO LIBRE Y SOBERANO DE OAXACA"
        </div>
    <div class="header">
 
    </div>

    <div class="origen-oficio-asunto-fecha">
        ORIGEN: Dirección de Recursos Materiales<br>
        OFICIO No.: {{ $oficio->numero_oficio }}<br>
        ASUNTO: Registro en el Padrón de Proveedores de la Administración Pública Estatal<br>
        Tlalixtac de Cabrera, Oax., {{ $fechaTexto }}
    </div>

    <div class="destinatario">
        <div class="destinatario-persona-fisica">
            @if(isset($datosGenerales) && $datosGenerales && $datosGenerales->razon_social)
                {{ strtoupper($datosGenerales->razon_social) }}<br>
            @endif
            
            @if(isset($direcciones) && $direcciones->count() > 0)
                @php
                    $direccion = $direcciones->first();
                    $domicilioCompleto = [];
                    if ($direccion->calle) $domicilioCompleto[] = $direccion->calle;
                    if ($direccion->numero_exterior) $domicilioCompleto[] = 'NÚMERO EXTERIOR ' . $direccion->numero_exterior;
                    if ($direccion->numero_interior) $domicilioCompleto[] = 'NÚMERO INTERIOR ' . $direccion->numero_interior;
                    if ($direccion->colonia_asentamiento) $domicilioCompleto[] = 'COL. ' . $direccion->colonia_asentamiento;
                    if ($direccion->municipio) $domicilioCompleto[] = $direccion->municipio;
                    if ($direccion->estado && $direccion->estado->nombre) $domicilioCompleto[] = $direccion->estado->nombre;
                    if ($direccion->codigo_postal) $domicilioCompleto[] = 'C.P. ' . $direccion->codigo_postal;
                    $domicilioString = implode(', ', $domicilioCompleto);
                @endphp
                {{ strtoupper($domicilioString) }}<br>
            @endif
            
            @if(isset($proveedor) && $proveedor && $proveedor->rfc)
                RFC: {{ $proveedor->rfc }}<br>
            @endif
            P R E S E N T E
        </div>
    </div>

    <div class="contenido-principal">
        @php
            $tipoTramiteTexto = strtolower($tramite->tipo_tramite ?? 'inscripcion');
            $accionTexto = match($tipoTramiteTexto) {
                'inscripcion' => 'registro',
                'renovacion' => 'renovación',
                'actualizacion' => 'actualización',
                default => 'registro'
            };
            $procesoTexto = match($tipoTramiteTexto) {
                'inscripcion' => 'se procedió al registro',
                'renovacion' => 'se procedió a la renovación del registro',
                'actualizacion' => 'se procedió a la actualización del registro',
                default => 'se procedió al registro'
            };
        @endphp
        
        Se hace referencia a su solicitud de {{ $accionTexto }} ante el Padrón de Proveedores de la Administración Pública Estatal y anexos que acompaña fechada el {{ $fechaInicioTramiteEspanol }}, recibida en esta Dirección de Recursos Materiales el {{ $fechaGeneracionDocumentoEspanol }}.
        <br><br>
        Sobre el particular, y en atención a la misma, una vez revisada y analizada, así como cotejados los documentos presentados en original, se informa que {{ $procesoTexto }} ante el Padrón de Proveedores de la Administración Pública Estatal, de la persona física "{{ isset($datosGenerales) && $datosGenerales && $datosGenerales->razon_social ? strtoupper($datosGenerales->razon_social) : '' }}", cuyas actividades económicas son las que se describen en su constancia de situación fiscal, con cédula de {{ $tipoTramiteTexto === 'inscripcion' ? 'inscripción' : 'proveedor' }} {{ isset($proveedor) && $proveedor && $proveedor->pv_numero ? $proveedor->pv_numero : '' }} asignada, que lo acredita como Proveedor Estatal, cuya vigencia será anual a partir del {{ strtoupper($fechaVigenciaInicioEspanol) }} hasta el {{ strtoupper($fechaVigenciaFinEspanol) }}, dejando constancia de ello, en el expediente respectivo.
        <br><br>
        Así mismo, se informa que, para renovar este registro, deberá presentar su solicitud dentro de los siete días hábiles previos a su vencimiento, en caso de que omita presentar dicha solicitud en el plazo indicado, se cancelará el registro a su vencimiento, sin perjuicio de lo anterior, podrá formular una nueva solicitud de inscripción, es importante puntualizar que en cualquier tiempo siempre que se encuentre vigente su registro, deberá comunicar a esta Secretaría a través de esta Dirección, las modificaciones legales, de capacidad técnica, económica o productiva y aquellas que puedan implicar un cambio en su giro y/o clasificación.
        <br><br>
        Por último, se exhorta a que en todos los trámites, procedimientos y contratos que celebre con las Dependencias o Entidades de la Administración Pública Estatal, se abstenga de adoptar conductas que vayan en contravención de la normatividad aplicable.
        <br><br>
        Lo anterior con fundamento en los artículos 1, 3 fracción XIV, 6, 11, 48, 49, 50, 51, 92, 93 y 94 de la Ley de Adquisiciones, Enajenaciones, Arrendamientos, Prestación de Servicios y Administración de Bienes Muebles e Inmuebles del Estado de Oaxaca, 46, 47, 48 y 49 de su Reglamento.
        <br><br>
        Sin otro particular, le reitero la seguridad de mi consideración distinguida.
    </div>

    <div class="firma">
        A T E N T A M E N T E.<br>
        SUFRAGIO EFECTIVO, NO REELECCIÓN.<br>
        "EL RESPETO AL DERECHO AJENO ES LA PAZ"<br>
        DIRECTORA DE RECURSOS MATERIALES<br>
        LIC. SARA ZÁRATE SANTIAGO<br>
        <div class="signature-space"></div>
    </div>

    <div class="logo-lateral">
        <img src="{{ base_path('public/images/logo_lateral2022.jpg') }}" alt="Logo Lateral">
    </div>

    <div class="copias">
        C.c.p.- Expediente y Minutario.<br>
        SZS/TEST
    </div>

    <div class="footer">
        Carretera Internacional Oaxaca-Istmo Km. 11.5, Ciudad Administrativa Benemérito de las Américas Edificio 2, Planta Baja, Tlalixtac de Cabrera, Oaxaca. C.P. 68270 Tel. Conmutador 01(951)5015000 Ext. 10004 y 10031.
    </div>

    <!-- Código QR para validación -->
    <div class="qr-code">
        {!! $qrCode !!}
    </div>

</body>
</html> 