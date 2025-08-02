/**
 * Script de seguridad DOM - Previene errores de elementos no encontrados
 * Autor: Sistema - Generado automáticamente
 * Versión: 1.0
 */

(function() {
    'use strict';

    // Función helper para verificar si un elemento existe antes de manipularlo
    window.safeElementOperation = function(elementId, operation) {
        const element = document.getElementById(elementId);
        if (element && typeof operation === 'function') {
            return operation(element);
        } else if (!element) {
            console.warn(`Elemento con ID '${elementId}' no encontrado en el DOM`);
        }
        return null;
    };

    // Función helper para querySelector con verificación
    window.safeQuerySelector = function(selector, operation) {
        const element = document.querySelector(selector);
        if (element && typeof operation === 'function') {
            return operation(element);
        } else if (!element) {
            console.warn(`Elemento con selector '${selector}' no encontrado en el DOM`);
        }
        return null;
    };

    // Función helper para verificar propiedades de elementos
    window.safeElementProperty = function(element, property, value) {
        if (element && element.hasOwnProperty(property)) {
            if (value !== undefined) {
                element[property] = value;
            }
            return element[property];
        } else if (!element) {
            console.warn('Elemento es null o undefined');
        } else {
            console.warn(`Propiedad '${property}' no existe en el elemento`);
        }
        return null;
    };

    // Override del getElementById para hacerlo más seguro
    const originalGetElementById = document.getElementById;
    document.getElementById = function(id) {
        const element = originalGetElementById.call(document, id);
        if (!element) {
            console.debug(`getElementById: Elemento '${id}' no encontrado`);
        }
        return element;
    };

    // Función para manejar clicks seguros
    window.safeClick = function(elementId, handler) {
        window.safeElementOperation(elementId, function(element) {
            element.addEventListener('click', handler);
        });
    };

    // Función para establecer texto de forma segura
    window.safeSetText = function(elementId, text) {
        return window.safeElementOperation(elementId, function(element) {
            element.textContent = text;
            return true;
        });
    };

    // Función para obtener texto de forma segura
    window.safeGetText = function(elementId) {
        return window.safeElementOperation(elementId, function(element) {
            return element.textContent;
        });
    };

    // Función para verificar clases de forma segura
    window.safeHasClass = function(elementId, className) {
        return window.safeElementOperation(elementId, function(element) {
            return element.classList.contains(className);
        }) || false;
    };

    // Función para agregar clases de forma segura
    window.safeAddClass = function(elementId, className) {
        return window.safeElementOperation(elementId, function(element) {
            element.classList.add(className);
            return true;
        });
    };

    // Función para remover clases de forma segura
    window.safeRemoveClass = function(elementId, className) {
        return window.safeElementOperation(elementId, function(element) {
            element.classList.remove(className);
            return true;
        });
    };

    // Manejar errores de JavaScript no capturados
    window.addEventListener('error', function(event) {
        // Solo logear errores DOM específicos, no interrumpir la aplicación
        if (event.message && (
            event.message.includes('Cannot read properties of null') ||
            event.message.includes('Cannot set properties of null') ||
            event.message.includes('appendChild') ||
            event.message.includes('textContent') ||
            event.message.includes('classList')
        )) {
            console.warn('Error DOM prevenido:', {
                message: event.message,
                filename: event.filename,
                lineno: event.lineno
            });
            
            // Prevenir que el error se propague
            event.preventDefault();
            return true;
        }
    });

    // Inicialización cuando el DOM esté listo
    function initDOMSafety() {
        console.info('DOM Safety inicializado correctamente');
        
        // Verificar elementos comunes que suelen causar errores
        const commonElements = ['currentTime', 'currentDate', 'greeting', 'main-content', 'sidebar'];
        commonElements.forEach(id => {
            if (!document.getElementById(id)) {
                console.debug(`Elemento común '${id}' no encontrado - esto es normal si no está en esta página`);
            }
        });
    }

    // Ejecutar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDOMSafety);
    } else {
        initDOMSafety();
    }

})();