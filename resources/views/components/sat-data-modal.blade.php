<div id="satDataModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-sm transition-opacity"></div>
    
    <div class="relative min-h-screen flex items-center justify-center p-2 sm:p-4">
        <div class="relative bg-white rounded-xl sm:rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-2xl max-h-[90vh] sm:max-h-[85vh] overflow-y-auto border border-gray-100">
            <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-100 bg-gradient-to-r from-primary/5 to-primary-dark/5">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-gray-900">Datos Fiscales</h3>
                        <p class="text-xs text-gray-600 font-medium">Información del SAT</p>
                    </div>
                </div>
                <button onclick="closeSatDataModal()" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all duration-200">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-4 sm:p-6">
                <div class="mb-4 sm:mb-6">
                    <div class="flex items-center space-x-2 mb-2 sm:mb-3">
                        <div class="w-2 h-2 bg-primary rounded-full"></div>
                        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">RFC</h4>
                    </div>
                    <div class="bg-gradient-to-r from-primary/10 to-primary-dark/10 border border-primary/20 rounded-lg sm:rounded-xl p-3 sm:p-4 shadow-sm">
                        <span id="modal-rfc" class="text-sm sm:text-lg font-mono font-bold text-primary-dark"></span>
                    </div>
                </div>

                <div class="mb-4 sm:mb-6">
                    <div class="flex items-center space-x-2 mb-2 sm:mb-3">
                        <div class="w-2 h-2 bg-primary-dark rounded-full"></div>
                        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Razón Social</h4>
                    </div>
                    <div class="bg-gradient-to-r from-primary-dark/10 to-primary/10 border border-primary-dark/20 rounded-lg sm:rounded-xl p-3 sm:p-4 shadow-sm">
                        <span id="modal-nombre" class="text-sm sm:text-base font-bold text-primary-dark leading-relaxed"></span>
                    </div>
                </div>

                <div class="mb-4 sm:mb-6">
                    <div class="flex items-center space-x-2 mb-2 sm:mb-3">
                        <div class="w-2 h-2 bg-primary rounded-full"></div>
                        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Tipo</h4>
                    </div>
                    <div class="bg-gradient-to-r from-primary/10 to-primary-dark/10 border border-primary/20 rounded-lg sm:rounded-xl p-3 sm:p-4 shadow-sm">
                        <span id="modal-tipo-persona" class="text-sm sm:text-base font-semibold text-primary-dark"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-lg sm:rounded-xl p-3 sm:p-4 shadow-sm">
                        <div class="flex items-center space-x-2 mb-2">
                            <div class="w-2 h-2 bg-primary rounded-full"></div>
                            <h5 class="text-xs font-bold text-gray-700 uppercase tracking-wider">CURP</h5>
                        </div>
                        <span id="modal-curp" class="text-xs sm:text-sm font-mono text-gray-800 font-medium"></span>
                    </div>

                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-lg sm:rounded-xl p-3 sm:p-4 shadow-sm">
                        <div class="flex items-center space-x-2 mb-2">
                            <div class="w-2 h-2 bg-primary-dark rounded-full"></div>
                            <h5 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Régimen</h5>
                        </div>
                        <span id="modal-regimen-fiscal" class="text-xs sm:text-sm text-gray-800 font-medium"></span>
                    </div>

                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-lg sm:rounded-xl p-3 sm:p-4 shadow-sm">
                        <div class="flex items-center space-x-2 mb-2">
                            <div class="w-2 h-2 bg-primary rounded-full"></div>
                            <h5 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Estatus</h5>
                        </div>
                        <span id="modal-estatus" class="text-xs sm:text-sm text-gray-800 font-medium"></span>
                    </div>

                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-lg sm:rounded-xl p-3 sm:p-4 shadow-sm">
                        <div class="flex items-center space-x-2 mb-2">
                            <div class="w-2 h-2 bg-primary-dark rounded-full"></div>
                            <h5 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Entidad</h5>
                        </div>
                        <span id="modal-entidad" class="text-xs sm:text-sm text-gray-800 font-medium"></span>
                    </div>
                </div>

                <div class="mb-4 sm:mb-6">
                    <div class="flex items-center space-x-2 mb-2 sm:mb-3">
                        <div class="w-2 h-2 bg-primary-dark rounded-full"></div>
                        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Domicilio</h4>
                    </div>
                    <div class="bg-gradient-to-r from-primary-dark/10 to-primary/10 border border-primary-dark/20 rounded-lg sm:rounded-xl p-3 sm:p-4 shadow-sm">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                            <div class="space-y-2">
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-bold text-primary-dark uppercase tracking-wider">Calle:</span>
                                    <span id="modal-calle" class="text-xs sm:text-sm text-primary-dark font-medium"></span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-bold text-primary-dark uppercase tracking-wider">No.:</span>
                                    <span id="modal-numero" class="text-xs sm:text-sm text-primary-dark font-medium"></span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-bold text-primary-dark uppercase tracking-wider">Col.:</span>
                                    <span id="modal-colonia" class="text-xs sm:text-sm text-primary-dark font-medium"></span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-bold text-primary-dark uppercase tracking-wider">CP:</span>
                                    <span id="modal-cp" class="text-xs sm:text-sm text-primary-dark font-medium"></span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-bold text-primary-dark uppercase tracking-wider">Mun.:</span>
                                    <span id="modal-municipio" class="text-xs sm:text-sm text-primary-dark font-medium"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between p-4 sm:p-6 border-t border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100 space-y-3 sm:space-y-0">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xs text-gray-600 font-medium">Verifique los datos antes de continuar</span>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-3 w-full sm:w-auto">
                    <button onclick="closeSatDataModal()" class="flex-1 sm:flex-none px-3 sm:px-4 py-2 text-xs font-bold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
                        Cancelar
                    </button>
                    <button onclick="confirmSatData()" class="flex-1 sm:flex-none px-3 sm:px-6 py-2 text-xs font-bold text-white bg-gradient-to-r from-primary to-primary-dark border border-primary rounded-lg hover:from-primary-dark hover:to-primary transition-all duration-200 shadow-md">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showSatDataModal(satData) {
    document.getElementById('modal-rfc').textContent = satData.rfc || 'No disponible';
    document.getElementById('modal-nombre').textContent = satData.nombre || 'No disponible';
    document.getElementById('modal-tipo-persona').textContent = satData.tipo_persona || 'No disponible';
    document.getElementById('modal-curp').textContent = satData.curp || 'No disponible';
    document.getElementById('modal-regimen-fiscal').textContent = satData.regimen_fiscal || 'No disponible';
    document.getElementById('modal-estatus').textContent = satData.estatus || 'No disponible';
    document.getElementById('modal-entidad').textContent = satData.entidad_federativa || 'No disponible';
    document.getElementById('modal-calle').textContent = satData.nombre_vialidad || 'No disponible';
    document.getElementById('modal-numero').textContent = (satData.numero_exterior || '') + (satData.numero_interior ? ' Int. ' + satData.numero_interior : '');
    document.getElementById('modal-colonia').textContent = satData.colonia || 'No disponible';
    document.getElementById('modal-cp').textContent = satData.cp || 'No disponible';
    document.getElementById('modal-municipio').textContent = satData.municipio || 'No disponible';

    const modal = document.getElementById('satDataModal');
    modal.classList.remove('hidden');
    
    setTimeout(() => {
        modal.querySelector('.bg-black').classList.add('bg-opacity-50');
        modal.querySelector('.bg-white').classList.add('animate-fadeInUp');
    }, 10);
}

function closeSatDataModal() {
    const modal = document.getElementById('satDataModal');
    
    modal.querySelector('.bg-black').classList.remove('bg-opacity-50');
    modal.querySelector('.bg-white').classList.remove('animate-fadeInUp');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

function confirmSatData() {
    closeSatDataModal();
    
    if (window.satDataGlobal) {
        fillHiddenInputs(window.satDataGlobal);
    }
    
    showRegistrationForm();
}
</script> 