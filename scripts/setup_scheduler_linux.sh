#!/bin/bash

echo "Configurando Scheduler para Limpieza Automática de Trámites"
echo "========================================================"

# Obtener la ruta del proyecto
PROJECT_PATH=$(dirname "$0")/..
cd "$PROJECT_PATH"

# Verificar si el comando crontab está disponible
if ! command -v crontab &> /dev/null; then
    echo "Error: crontab no está disponible"
    exit 1
fi

# Crear entrada para el crontab
CRON_ENTRY="0 2 * * * cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1"

# Verificar si ya existe la entrada
if crontab -l 2>/dev/null | grep -q "php artisan schedule:run"; then
    echo "La entrada del scheduler ya existe en el crontab"
else
    # Agregar la entrada al crontab
    (crontab -l 2>/dev/null; echo "$CRON_ENTRY") | crontab -
    echo "Entrada agregada al crontab exitosamente!"
fi

echo ""
echo "Configuración completada!"
echo "La limpieza automática se ejecutará todos los días a las 2:00 AM"
echo ""
echo "Para verificar el crontab:"
echo "crontab -l"
echo ""
echo "Para probar manualmente:"
echo "php artisan schedule:run"
echo ""
echo "Para ver los logs:"
echo "tail -f storage/logs/tramites-limpiados.log" 