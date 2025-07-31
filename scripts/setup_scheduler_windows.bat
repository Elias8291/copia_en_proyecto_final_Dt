@echo off
echo Configurando Scheduler para Limpieza Automática de Trámites
echo ========================================================

REM Obtener la ruta del proyecto
set PROJECT_PATH=%~dp0..
cd /d "%PROJECT_PATH%"

REM Crear tarea programada para ejecutar el scheduler
schtasks /create /tn "Laravel Scheduler" /tr "php artisan schedule:run" /sc daily /st 02:00 /ru "SYSTEM" /f

echo.
echo Tarea programada creada exitosamente!
echo La limpieza automática se ejecutará todos los días a las 2:00 AM
echo.
echo Para verificar las tareas programadas:
echo schtasks /query /tn "Laravel Scheduler"
echo.
echo Para eliminar la tarea si es necesario:
echo schtasks /delete /tn "Laravel Scheduler" /f
echo.
pause 