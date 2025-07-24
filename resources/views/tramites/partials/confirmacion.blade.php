<div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
    <!-- Encabezado con icono -->
    <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100">
        <div class="flex items-center space-x-4">
            <div
                class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Confirmación</h2>
                <p class="text-sm text-gray-500 mt-1">Revise y confirme los datos ingresados</p>
            </div>
        </div>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
        <div class="flex items-start space-x-3">
            <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="currentColor"
                viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd"></path>
            </svg>
            <div class="flex-1">
                <h3 class="text-sm font-medium text-amber-800">Importante</h3>
                <p class="text-amber-700 text-sm mt-1">
                    @if ($tipo_tramite === 'inscripcion')
                        Verifique que todos los datos sean correctos. Una vez
                        enviado, el proceso de inscripción será revisado por nuestro
                        equipo.
                    @elseif($tipo_tramite === 'renovacion')
                        La renovación mantendrá su registro activo. Asegúrese de que
                        toda la información esté actualizada.
                    @else
                        Los cambios realizados pueden requerir documentación de
                        soporte y revisión administrativa.
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <label class="flex items-start space-x-3">
            <input type="checkbox" name="confirma_datos" required
                class="mt-1 h-4 w-4 text-[#9D2449] focus:ring-[#9D2449] border-slate-300 rounded">
            <span class="text-sm text-slate-700">
                Confirmo que los datos proporcionados son correctos y veraces.
                Entiendo que proporcionar información falsa puede resultar en la
                cancelación de mi registro.
            </span>
        </label>
    </div>
</div> 