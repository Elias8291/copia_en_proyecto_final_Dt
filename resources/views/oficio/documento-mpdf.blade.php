<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oficio de Inscripción - Padrón de Proveedores</title>
    <style>
       tyle>
        @page {
            size: 8.5in 11in;
            margin: 0;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            width: 216mm;
            height: 279mm;
            position: relative;
            background: white;
            font-size: 8pt;
        }
        .logo {
            position: absolute;
            top: 8mm;
            left: 8mm;
            width: 70mm;
            height: auto;
        }
        
        .logo img {
            width: 100%;
            height: auto;
        }

        .logo-lateral {
            position: absolute;
            top: -12mm;
            right: 9mm;
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
            top: 25mm;
            left: 50mm;
            width: 116mm;
            text-align: center;
            font-style: italic;
            font-size: 8pt;
        }

        .origen-oficio-asunto-fecha {
            position: absolute;
            top: 38mm;
            left: 32mm;
            width: 152mm;
            font-size: 8pt;
            text-align: right;
            line-height: 1.2;
            font-weight: bold;
            z-index: 5;
        }

        .destinatario {
            position: absolute;
            top: 60mm;
            left: 8mm;
            width: 130mm;
            font-size: 8pt;
            font-weight: bold;
            line-height: 1.2;
        }

        .destinatario-persona-moral {
            line-height: 1.2;
        }

        .destinatario-persona-fisica {
            line-height: 1.2;
        }
        
        .contenido-principal {
            position: absolute;
            top: 90mm;
            left: 8mm;
            width: 176mm;
            font-size: 8pt;
            text-align: justify;
            line-height: 1.4;
            z-index: 5;
        }

        .contenido-principal-fisica {
            top: 83mm;
        }

        .firma {
            position: absolute;
            top: 210mm;
            left: 25mm;
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
            bottom: 17mm;
            left: 19mm;
            width: 180mm;
            font-size: 5pt;
            font-weight: bold;
            line-height: 1.2;
        }

        .copias {
            position: absolute;
            top: 228mm;
            left: 19mm;
            font-size: 5pt;
            line-height: 1.2;
        }
        
        .qr-code {
            position: absolute;
            bottom: 25mm;
            right: 20mm;
            width: 25mm;
            height: 25mm;
            background: white;
            border: 1px solid #ccc;
            padding: 1mm;
            z-index: 1000;
        }
        
        .qr-code svg {
            width: 100% !important;
            height: 100% !important;
            display: block;
        }
        
        .qr-code img {
            width: 100% !important;
            height: 100% !important;
            display: block;
            object-fit: contain;
        }
        
        .qr-text {
            position: absolute;
            bottom: 28mm;
            right: 25mm;
            width: 20mm;
            text-align: center;
            font-size: 3pt;
            color: #666;
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
        <div class="destinatario-persona-moral">
            @if(isset($datosConstitutivos) && $datosConstitutivos && isset($datosConstitutivos->representanteLegal) && $datosConstitutivos->representanteLegal && $datosConstitutivos->representanteLegal->nombre_completo)
                {{ strtoupper($datosConstitutivos->representanteLegal->nombre_completo) }}<br>
            @endif
            
            @if(isset($datosGenerales) && $datosGenerales && $datosGenerales->razon_social)
                REPRESENTANTE LEGAL DE {{ strtoupper($datosGenerales->razon_social) }}<br>
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
        Se hace referencia a su solicitud de registro ante el Padrón de Proveedores de la Administración Pública Estatal y anexos que acompaña fechada el {{ $fechaInicioTramite ? $fechaInicioTramite->format('d') . ' de ' . $fechaInicioTramite->translatedFormat('F') . ' de ' . $fechaInicioTramite->format('Y') : '' }}, recibida en esta Dirección de Recursos Materiales el {{ $fechaGeneracionDocumento ? $fechaGeneracionDocumento->format('d') . ' de ' . $fechaGeneracionDocumento->translatedFormat('F') . ' de ' . $fechaGeneracionDocumento->format('Y') : '' }}.
        <br><br>
        Sobre el particular, y en atención a la misma, una vez revisada y analizada, así como cotejados los documentos presentados en original, se informa que se procedió al registro ante el Padrón de Proveedores de la Administración Pública Estatal, de la persona moral "{{ isset($datosGenerales) && $datosGenerales && $datosGenerales->razon_social ? strtoupper($datosGenerales->razon_social) : '' }}", cuyo giro y/o clasificación se establece de manera enunciativa mas no limitativa como a continuación se describe "{{ isset($datosGenerales) && $datosGenerales && $datosGenerales->giro ? strtoupper($datosGenerales->giro) : 'COMERCIO EN GENERAL' }}", y demás actividades comerciales, profesionales, mercantiles o de negocios de conformidad con sus actividades económicas y su objeto social registrado y autorizado, con cédula de inscripción {{ isset($proveedor) && $proveedor && $proveedor->pv ? $proveedor->pv : '' }} asignada, que lo acredita como Proveedor Estatal, cuya vigencia será anual a partir del {{ isset($fechaVigenciaProveedor) && $fechaVigenciaProveedor ? $fechaVigenciaProveedor->format('d') . ' DE ' . strtoupper($fechaVigenciaProveedor->translatedFormat('F')) . ' DE ' . $fechaVigenciaProveedor->format('Y') : '' }} hasta el {{ isset($fechaVigenciaProveedor) && $fechaVigenciaProveedor ? $fechaVigenciaProveedor->addYear()->format('d') . ' DE ' . strtoupper($fechaVigenciaProveedor->addYear()->translatedFormat('F')) . ' DE ' . $fechaVigenciaProveedor->addYear()->format('Y') : '' }}, dejando constancia de ello, en el expediente respectivo.
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
    <div class="qr-text">
        Validar documento
    </div>
</body>
</html>