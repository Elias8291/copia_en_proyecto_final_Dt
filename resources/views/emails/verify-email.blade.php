<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Cuenta</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 8px 24px rgba(149, 26, 29, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #9d2449 0%, #8a203f 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .logo {
            width: 80px;
            height: 80px;
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }
        .content {
            padding: 40px 30px;
        }
        .welcome-text {
            font-size: 28px;
            font-weight: bold;
            color: #9d2449;
            margin-bottom: 25px;
            text-align: center;
        }
        .verify-button {
            display: inline-block;
            background: linear-gradient(135deg, #9d2449 0%, #8a203f 100%);
            color: white;
            padding: 16px 40px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            margin: 25px 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(157, 36, 73, 0.2);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
        }
        .verify-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(157, 36, 73, 0.3);
        }
        .info-box {
            background: linear-gradient(135deg, #800000 0%, #5c0000 100%);
            border-radius: 15px;
            padding: 30px;
            color: white;
            margin: 30px 0;
            box-shadow: 0 8px 24px rgba(149, 26, 29, 0.15);
        }
        .verification-link {
            word-break: break-all;
            color: #b87070;
            background-color: #fff5f5;
            padding: 15px;
            border-radius: 8px;
            font-family: monospace;
            border: 1px dashed #c98b8b;
            margin: 20px 0;
        }
        .footer {
            background: linear-gradient(135deg, #800000 0%, #5c0000 100%);
            color: white;
            padding: 35px;
            text-align: center;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <svg width="40" height="40" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0L3 7v10c0 5.55 3.84 9.739 9 9.739s9-4.189 9-9.739V7L12 0z"/>
                </svg>
            </div>
            <h1 style="margin: 0; font-size: 32px; font-weight: 600;">Padrón de Proveedores</h1>
            <p style="margin: 15px 0 0 0; opacity: 0.9; font-size: 18px;">Gobierno del Estado de Oaxaca</p>
        </div>
        <div class="content">
            <div class="welcome-text">¡Bienvenido {{ $user->nombre }}!</div>
            
            <p style="font-size: 16px; color: #444;">
                Gracias por registrarte en el Padrón de Proveedores del Gobierno del Estado de Oaxaca. 
                Para activar tu cuenta y comenzar a utilizar nuestros servicios, por favor verifica tu dirección de correo electrónico.
            </p>

            <div style="text-align: center; margin: 35px 0;">
                <a href="{{ $verificationUrl }}" class="verify-button">
                    Verificar mi cuenta
                </a>
            </div>

            <div class="info-box">
                <h3 style="margin: 0 0 20px 0; font-size: 20px; text-align: center; border-bottom: 2px solid rgba(255,255,255,0.2); padding-bottom: 15px;">
                    Información Importante
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 25px;">
                    <div style="text-align: center; padding: 15px; background: rgba(255,255,255,0.1); border-radius: 10px;">
                        <p style="margin: 0; font-size: 14px;">Enlace activo por<br><strong>{{ $expirationHours }} horas</strong></p>
                    </div>
                    
                    <div style="text-align: center; padding: 15px; background: rgba(255,255,255,0.1); border-radius: 10px;">
                        <p style="margin: 0; font-size: 14px;">Un solo clic<br><strong>para verificar</strong></p>
                    </div>
                    
                    <div style="text-align: center; padding: 15px; background: rgba(255,255,255,0.1); border-radius: 10px;">
                        <p style="margin: 0; font-size: 14px;">Acceso completo<br><strong>a la plataforma</strong></p>
                    </div>
                </div>

                <div style="background: rgba(255, 255, 255, 0.95); padding: 20px; border-radius: 10px; color: #800000; text-align: center; border-left: 5px solid #ff9999;">
                    <strong style="font-size: 16px; color: #800000;">Recordatorio Importante</strong>
                    <p style="margin: 10px 0 0 0; color: #666;">
                        Si no verificas tu cuenta en las próximas {{ $expirationHours }} horas, deberás iniciar el proceso de registro nuevamente.
                    </p>
                </div>
            </div>

            <p style="color: #444;">Si el botón no funciona, copia y pega el siguiente enlace en tu navegador:</p>
            <div class="verification-link">
                {{ $verificationUrl }}
            </div>

            <p style="color: #666; font-style: italic;">
                Si no has solicitado este registro, puedes ignorar este mensaje de forma segura.
            </p>
        </div>

        <div class="footer">
            <div style="margin-bottom: 20px;">
                <svg width="40" height="40" fill="rgba(255,255,255,0.9)" viewBox="0 0 24 24" style="margin: 0 auto;">
                    <path d="M12 0L3 7v10c0 5.55 3.84 9.739 9 9.739s9-4.189 9-9.739V7L12 0z"/>
                </svg>
            </div>

            <h2 style="color: white; font-size: 20px; font-weight: 600; margin: 0 0 15px 0; text-transform: uppercase; letter-spacing: 1px;">
                Padrón de Proveedores
            </h2>
            <p style="color: white; font-size: 16px; margin: 0 0 5px 0; font-weight: 500;">
                Gobierno del Estado de Oaxaca
            </p>
            
            <div style="width: 60px; height: 2px; background: rgba(255,255,255,0.3); margin: 20px auto;"></div>
            
            <p style="color: rgba(255,255,255,0.8); font-size: 14px; margin: 15px 0;">
                Este es un correo automático. Por favor, no responda a este mensaje.
            </p>
            
            <p style="color: rgba(255,255,255,0.7); font-size: 12px; margin: 0;">
                © {{ date('Y') }} Gobierno del Estado de Oaxaca. Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>
</html>