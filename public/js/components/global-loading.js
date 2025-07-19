/**
 * Global Loading Component
 * Componente de carga con persona proveedor elegante y minimalista.
 */
class GlobalLoading {
    constructor() {
        this.isVisible = false;
        this.loadingElement = null;
        this.init();
    }

    /** Inicializar el componente */
    init() {
        // Si el elemento ya existe, no hacer nada
        if (document.getElementById('global-loading')) return;
        
        this.createLoadingElement();
        this.addStyles();
    }

    /** Crear el elemento de loading */
    createLoadingElement() {
        this.loadingElement = document.createElement('div');
        this.loadingElement.id = 'global-loading';
        this.loadingElement.className = 'global-loading-overlay';

        this.loadingElement.innerHTML = `
            <div class="global-loading-container">
                <div class="loading-card">
                    <!-- Círculo de Progreso Principal -->
                    <div class="main-loader">
                        <div class="loader-ring">
                            <div class="ring-segment segment-1"></div>
                            <div class="ring-segment segment-2"></div>
                            <div class="ring-segment segment-3"></div>
                            <div class="ring-segment segment-4"></div>
                        </div>
                        
                        <!-- Icono Central -->
                        <div class="center-icon">
                            <div class="document-icon">
                                <div class="doc-body">
                                    <div class="doc-lines">
                                        <div class="doc-line"></div>
                                        <div class="doc-line"></div>
                                        <div class="doc-line"></div>
                                        <div class="doc-line"></div>
                                    </div>
                                </div>
                                <div class="doc-corner"></div>
                            </div>
                        </div>
                        
                        <!-- Partículas Flotantes -->
                        <div class="floating-particles">
                            <div class="particle p1"></div>
                            <div class="particle p2"></div>
                            <div class="particle p3"></div>
                            <div class="particle p4"></div>
                            <div class="particle p5"></div>
                            <div class="particle p6"></div>
                        </div>
                    </div>
                    
                    <!-- Texto y Barra de Progreso -->
                    <div class="loading-content">
                        <div class="loading-text" id="global-loading-text">Procesando</div>
                        <div class="progress-container">
                            <div class="progress-bar">
                                <div class="progress-fill"></div>
                            </div>
                            <div class="progress-dots">
                                <div class="dot"></div>
                                <div class="dot"></div>
                                <div class="dot"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(this.loadingElement);
    }

    /** Agregar estilos CSS */
    addStyles() {
        if (document.getElementById('global-loading-styles')) return;

        const styles = document.createElement('style');
        styles.id = 'global-loading-styles';
        styles.textContent = `
            :root {
                --primary-elegant: #9d2449;
                --primary-light: #be185d;
                --primary-dark: #7a1d37;
                --accent-gold: #d4af37;
                --accent-rose: #f43f5e;
                --accent-purple: #a855f7;
                --bg-dark: #0f0f23;
                --bg-gradient: #1a1a2e;
                --text-white: #ffffff;
                --text-light: #f8fafc;
            }

            .global-loading-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, 
                    rgba(15, 15, 35, 0.96), 
                    rgba(26, 26, 46, 0.94), 
                    rgba(157, 36, 73, 0.08));
                backdrop-filter: blur(25px);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
                opacity: 0;
                visibility: hidden;
                transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .global-loading-overlay.show {
                opacity: 1;
                visibility: visible;
            }

            .global-loading-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 32px;
                padding: 0;
            }

            .loading-card {
                background: transparent;
                border-radius: 0;
                padding: 0;
                box-shadow: none;
                backdrop-filter: none;
                border: none;
                transform: scale(0.8) translateY(40px);
                animation: cardAppear 1s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            }

            @keyframes cardAppear {
                to {
                    transform: scale(1) translateY(0);
                }
            }

            /* === MAIN LOADER === */
            .main-loader {
                position: relative;
                width: 140px;
                height: 140px;
                display: flex;
                align-items: center;
                justify-content: center;
                animation: mainFloat 4s ease-in-out infinite;
            }

            @keyframes mainFloat {
                0%, 100% { transform: translateY(0px) scale(1); }
                50% { transform: translateY(-8px) scale(1.02); }
            }

            /* === LOADER RING === */
            .loader-ring {
                position: absolute;
                width: 120px;
                height: 120px;
                border-radius: 50%;
            }

            .ring-segment {
                position: absolute;
                width: 120px;
                height: 120px;
                border-radius: 50%;
                border: 4px solid transparent;
            }

            .segment-1 {
                border-top: 4px solid var(--primary-elegant);
                animation: ringRotate1 2s linear infinite;
                filter: drop-shadow(0 0 12px rgba(157, 36, 73, 0.8));
            }

            .segment-2 {
                border-right: 4px solid var(--primary-light);
                animation: ringRotate2 2.5s linear infinite reverse;
                filter: drop-shadow(0 0 12px rgba(190, 24, 93, 0.8));
            }

            .segment-3 {
                border-bottom: 4px solid var(--accent-gold);
                animation: ringRotate3 3s linear infinite;
                filter: drop-shadow(0 0 12px rgba(212, 175, 55, 0.8));
            }

            .segment-4 {
                border-left: 4px solid var(--accent-rose);
                animation: ringRotate4 2.2s linear infinite reverse;
                filter: drop-shadow(0 0 12px rgba(244, 63, 94, 0.8));
            }

            @keyframes ringRotate1 {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            @keyframes ringRotate2 {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            @keyframes ringRotate3 {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            @keyframes ringRotate4 {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            /* === CENTER ICON === */
            .center-icon {
                position: relative;
                z-index: 10;
                animation: iconPulse 3s ease-in-out infinite;
            }

            @keyframes iconPulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.1); }
            }

            /* === DOCUMENT ICON === */
            .document-icon {
                position: relative;
                width: 40px;
                height: 50px;
            }

            .doc-body {
                width: 40px;
                height: 50px;
                background: linear-gradient(145deg, #ffffff, #f1f5f9);
                border-radius: 4px;
                box-shadow: 
                    0 8px 32px rgba(0, 0, 0, 0.2),
                    inset 0 2px 0 rgba(255, 255, 255, 0.9);
                border: 2px solid rgba(255, 255, 255, 0.8);
                position: relative;
                overflow: hidden;
            }

            .doc-corner {
                position: absolute;
                top: 0;
                right: 0;
                width: 12px;
                height: 12px;
                background: linear-gradient(135deg, transparent 50%, #e2e8f0 50%);
                border-bottom-left-radius: 4px;
            }

            .doc-lines {
                position: absolute;
                top: 12px;
                left: 8px;
                right: 8px;
                display: flex;
                flex-direction: column;
                gap: 4px;
            }

            .doc-line {
                height: 2px;
                background: linear-gradient(90deg, var(--primary-elegant), var(--primary-light));
                border-radius: 1px;
                animation: docLineGlow 2s ease-in-out infinite;
            }

            .doc-line:nth-child(1) { 
                width: 80%; 
                animation-delay: 0s;
            }
            .doc-line:nth-child(2) { 
                width: 60%; 
                animation-delay: 0.3s;
            }
            .doc-line:nth-child(3) { 
                width: 90%; 
                animation-delay: 0.6s;
            }
            .doc-line:nth-child(4) { 
                width: 45%; 
                animation-delay: 0.9s;
            }

            @keyframes docLineGlow {
                0%, 100% { 
                    opacity: 0.6; 
                    transform: scaleX(1);
                    box-shadow: 0 0 6px rgba(157, 36, 73, 0.4);
                }
                50% { 
                    opacity: 1; 
                    transform: scaleX(1.05);
                    box-shadow: 0 0 15px rgba(157, 36, 73, 0.8);
                }
            }

            /* === FLOATING PARTICLES === */
            .floating-particles {
                position: absolute;
                width: 140px;
                height: 140px;
                pointer-events: none;
            }

            .particle {
                position: absolute;
                width: 6px;
                height: 6px;
                border-radius: 50%;
                opacity: 0.8;
            }

            .p1 {
                top: 15%;
                left: 20%;
                background: var(--primary-elegant);
                animation: particleFloat1 3s ease-in-out infinite;
                box-shadow: 0 0 10px rgba(157, 36, 73, 0.8);
            }

            .p2 {
                top: 25%;
                right: 15%;
                background: var(--primary-light);
                animation: particleFloat2 3.5s ease-in-out infinite;
                box-shadow: 0 0 10px rgba(190, 24, 93, 0.8);
            }

            .p3 {
                bottom: 30%;
                left: 15%;
                background: var(--accent-gold);
                animation: particleFloat3 2.8s ease-in-out infinite;
                box-shadow: 0 0 10px rgba(212, 175, 55, 0.8);
            }

            .p4 {
                bottom: 20%;
                right: 25%;
                background: var(--accent-rose);
                animation: particleFloat4 3.2s ease-in-out infinite;
                box-shadow: 0 0 10px rgba(244, 63, 94, 0.8);
            }

            .p5 {
                top: 40%;
                left: 10%;
                background: var(--accent-purple);
                animation: particleFloat5 2.6s ease-in-out infinite;
                box-shadow: 0 0 10px rgba(168, 85, 247, 0.8);
            }

            .p6 {
                top: 60%;
                right: 10%;
                background: var(--primary-elegant);
                animation: particleFloat6 3.8s ease-in-out infinite;
                box-shadow: 0 0 10px rgba(157, 36, 73, 0.8);
            }

            @keyframes particleFloat1 {
                0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.6; }
                50% { transform: translate(15px, -20px) scale(1.3); opacity: 1; }
            }

            @keyframes particleFloat2 {
                0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.6; }
                50% { transform: translate(-12px, -15px) scale(1.2); opacity: 1; }
            }

            @keyframes particleFloat3 {
                0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.6; }
                50% { transform: translate(18px, 12px) scale(1.4); opacity: 1; }
            }

            @keyframes particleFloat4 {
                0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.6; }
                50% { transform: translate(-15px, 18px) scale(1.1); opacity: 1; }
            }

            @keyframes particleFloat5 {
                0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.6; }
                50% { transform: translate(20px, -10px) scale(1.25); opacity: 1; }
            }

            @keyframes particleFloat6 {
                0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.6; }
                50% { transform: translate(-18px, -25px) scale(1.15); opacity: 1; }
            }

            /* === LOADING CONTENT === */
            .loading-content {
                text-align: center;
                animation: contentFadeIn 1s ease-out 0.5s both;
            }

            @keyframes contentFadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .progress-container {
                margin-top: 16px;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 12px;
            }

            .progress-bar {
                width: 200px;
                height: 4px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 2px;
                overflow: hidden;
                position: relative;
            }

            .progress-fill {
                height: 100%;
                background: linear-gradient(90deg, var(--primary-elegant), var(--primary-light), var(--accent-gold));
                border-radius: 2px;
                animation: progressMove 2.5s ease-in-out infinite;
                box-shadow: 0 0 15px rgba(157, 36, 73, 0.8);
            }

            @keyframes progressMove {
                0% { 
                    width: 0%; 
                    transform: translateX(0%);
                }
                50% { 
                    width: 80%; 
                    transform: translateX(0%);
                }
                100% { 
                    width: 100%; 
                    transform: translateX(0%);
                }
            }

            .progress-dots {
                display: flex;
                gap: 8px;
            }

            .dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: var(--primary-elegant);
                animation: dotBounce 1.4s ease-in-out infinite;
                box-shadow: 0 0 10px rgba(157, 36, 73, 0.8);
            }

            .dot:nth-child(1) { animation-delay: 0s; }
            .dot:nth-child(2) { 
                animation-delay: 0.2s; 
                background: var(--primary-light);
                box-shadow: 0 0 10px rgba(190, 24, 93, 0.8);
            }
            .dot:nth-child(3) { 
                animation-delay: 0.4s; 
                background: var(--accent-gold);
                box-shadow: 0 0 10px rgba(212, 175, 55, 0.8);
            }

            @keyframes dotBounce {
                0%, 80%, 100% {
                    transform: scale(1);
                    opacity: 0.7;
                }
                40% {
                    transform: scale(1.3);
                    opacity: 1;
                }
            }

            /* === LOADING TEXT === */
            .loading-text {
                font-size: 24px;
                font-weight: 300;
                color: white;
                font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
                letter-spacing: 2px;
                text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
                animation: textPulse 2s ease-in-out infinite;
            }

            @keyframes textPulse {
                0%, 100% { opacity: 0.9; }
                50% { opacity: 1; }
            }

            /* === RESPONSIVE === */
            @media (max-width: 640px) {
                .main-loader {
                    width: 120px;
                    height: 120px;
                }
                
                .loader-ring {
                    width: 100px;
                    height: 100px;
                }
                
                .ring-segment {
                    width: 100px;
                    height: 100px;
                    border-width: 3px;
                }
                
                .document-icon {
                    width: 32px;
                    height: 40px;
                }
                
                .doc-body {
                    width: 32px;
                    height: 40px;
                }
                
                .floating-particles {
                    width: 120px;
                    height: 120px;
                }
                
                .particle {
                    width: 4px;
                    height: 4px;
                }
                
                .progress-bar {
                    width: 160px;
                    height: 3px;
                }
                
                .loading-text {
                    font-size: 20px;
                    letter-spacing: 1.5px;
                }
                
                .global-loading-container {
                    gap: 24px;
                }
            }
        `;

        document.head.appendChild(styles);
    }

    /** Mostrar loading */
    show(options = {}) {
        const {
            title = 'Procesando...',
            subtitle = 'Por favor espere'
        } = options;

        if (this.isVisible) return;

        this.isVisible = true;

        // Actualizar textos
        const textElement = document.getElementById('global-loading-text');
        if (textElement) {
            textElement.textContent = title;
        }
        
        const subtitleElement = document.getElementById('global-loading-subtitle');
        if (subtitleElement) {
            subtitleElement.textContent = subtitle;
        }

        // Mostrar con animación
        this.loadingElement.classList.add('show');

        // Prevenir scroll del body
        document.body.style.overflow = 'hidden';
    }

    /** Ocultar loading */
    hide() {
        if (!this.isVisible) return;

        this.isVisible = false;

        // Ocultar con animación
        this.loadingElement.classList.remove('show');

        // Restaurar scroll del body
        document.body.style.overflow = '';
    }

    /** Mostrar loading por un tiempo específico */
    showFor(duration, options = {}) {
        this.show(options);

        setTimeout(() => {
            this.hide();
        }, duration);
    }

    /** Verificar si está visible */
    isShowing() {
        return this.isVisible;
    }
}

// Crear instancia global
window.globalLoading = new GlobalLoading();

// Funciones de conveniencia globales
window.showLoading = (options) => window.globalLoading.show(options);
window.hideLoading = () => window.globalLoading.hide();
window.showLoadingFor = (duration, options) => window.globalLoading.showFor(duration, options);

// Exportar para módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = GlobalLoading;
}
