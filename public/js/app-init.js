/**
 * Application Initialization
 * Loads error handling and security utilities in the correct order
 */

(function() {
    'use strict';

    // Load order: Error Handler -> SVG Validator -> Iframe Security -> DOM Ready
    const loadScript = (src, callback) => {
        const script = document.createElement('script');
        script.src = src;
        script.onload = callback;
        script.onerror = () => {
            console.warn(`Failed to load script: ${src}`);
            if (callback) callback();
        };
        document.head.appendChild(script);
    };

    // Initialize error handling first
    const initErrorHandling = () => {
        loadScript('/js/error-handler.js', () => {
            console.log('Error handler loaded');
            initSVGValidation();
        });
    };

    // Initialize SVG validation
    const initSVGValidation = () => {
        loadScript('/js/svg-fix.js', () => {
            console.log('SVG validator loaded');
            initIframeSecurity();
        });
    };

    // Initialize iframe security
    const initIframeSecurity = () => {
        loadScript('/js/iframe-security.js', () => {
            console.log('Iframe security loaded');
            initApp();
        });
    };

    // Main application initialization
    const initApp = () => {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(initComponents, 100);
            });
        } else {
            setTimeout(initComponents, 100);
        }
    };

    // Initialize application components
    const initComponents = () => {
        try {
            // Initialize SVG validation
            if (window.svgValidator) {
                window.svgValidator.validateAllPaths();
            }

            // Initialize iframe security
            if (window.iframeSecurity) {
                window.iframeSecurity.secureExistingIframes();
                window.iframeSecurity.startSecurityMonitoring();
            }

            // Initialize any existing PrelineUI components safely
            if (typeof HSStaticMethods !== 'undefined') {
                try {
                    HSStaticMethods.autoInit();
                } catch (error) {
                    console.warn('PrelineUI initialization warning:', error);
                }
            }

            // Initialize document viewer if it exists
            if (window.DocumentViewer) {
                try {
                    new window.DocumentViewer();
                } catch (error) {
                    console.warn('Document viewer initialization warning:', error);
                }
            }

            console.log('Application components initialized successfully');
        } catch (error) {
            console.error('Error initializing components:', error);
        }
    };

    // Start the initialization process
    initErrorHandling();

    // Provide global reinitialization function
    window.reinitializeApp = () => {
        console.log('Reinitializing application components...');
        initComponents();
    };

    // Provide debugging utilities
    window.getAppStatus = () => {
        return {
            errorHandler: !!window.errorHandler,
            svgValidator: !!window.svgValidator,
            iframeSecurity: !!window.iframeSecurity,
            errorStats: window.errorHandler ? window.errorHandler.getErrorStats() : null,
            domReady: document.readyState === 'complete'
        };
    };

})(); 