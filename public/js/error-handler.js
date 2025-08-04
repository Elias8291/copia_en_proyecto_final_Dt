/**
 * Error Handler para la aplicación
 * Maneja errores de JavaScript de forma global
 */

(function() {
    'use strict';

    // Capturar errores no manejados
    window.addEventListener('error', function(event) {
        // Categorizar errores para mejor manejo
        const isDOMError = event.message && (
            event.message.includes('Cannot read properties of null') ||
            event.message.includes('Cannot set properties of null') ||
            event.message.includes('appendChild') ||
            event.message.includes('textContent') ||
            event.message.includes('classList') ||
            event.message.includes('addEventListener')
        );
        
        const isScriptError = event.filename && (
            event.filename.includes('stepper.js') ||
            event.filename.includes('loading-states.js') ||
            event.filename.includes('global-loading.js')
        );
        
        // Solo logear errores DOM específicos, no interrumpir la aplicación
        if (isDOMError || isScriptError) {
            console.warn('Error DOM/script prevenido:', {
                message: event.message,
                filename: event.filename,
                lineno: event.lineno,
                colno: event.colno,
                type: isDOMError ? 'DOM' : 'Script'
            });
            
            // Prevenir que el error se propague
            event.preventDefault();
            return true;
        }
        
        // Para otros errores, solo logear sin prevenir
        console.error('Error capturado:', {
            message: event.message,
            filename: event.filename,
            lineno: event.lineno,
            colno: event.colno,
            error: event.error
        });
    });

    // Capturar promesas rechazadas no manejadas
    window.addEventListener('unhandledrejection', function(event) {
        console.error('Promesa rechazada no manejada:', {
            reason: event.reason,
            promise: event.promise
        });
        
        // Prevenir que el error se propague
        event.preventDefault();
    });

    // Función para manejar errores de forma personalizada
    window.handleError = function(error, context = '') {
        console.error(`Error en ${context}:`, error);
        
        // Aquí puedes agregar lógica adicional como enviar errores a un servicio de monitoreo
        // o mostrar notificaciones al usuario
    };

    // Función para validar que un elemento existe antes de usarlo
    window.safeElement = function(selector) {
        const element = document.querySelector(selector);
        if (!element) {
            console.warn(`Elemento no encontrado: ${selector}`);
            return null;
        }
        return element;
    };

    // Función para agregar event listeners de forma segura
    window.safeAddEventListener = function(selector, event, handler) {
        const element = window.safeElement(selector);
        if (element) {
            element.addEventListener(event, handler);
            return true;
        }
        return false;
    };

    console.log('Error Handler inicializado correctamente');
})(); 