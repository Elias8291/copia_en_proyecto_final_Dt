<!-- SECCIÓN 5: APODERADO LEGAL (Solo Morales) -->
<div id="seccion-5" class="section-content p-4 sm:p-8 border-t border-gray-200 mt-8 persona-moral-section">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-2 flex items-center">
            <div class="w-8 h-8 bg-[#9d2449]/10 rounded-lg flex items-center justify-center mr-3">
                <span class="text-[#9d2449] font-bold text-sm">5</span>
            </div>
            Apoderado Legal
        </h2>
        <p class="text-gray-600 text-sm">Información del representante legal de la empresa</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-form-input 
            name="nombre_apoderado" 
            label="Nombre del Apoderado" 
            placeholder="Nombre completo del apoderado legal"
            container_class="md:col-span-2"
            required 
            data-required="true"
        />
        <x-form-input 
            name="apoderado_numero_escritura" 
            label="Número de Escritura" 
            placeholder="Número de escritura de poderes"
            required 
            data-required="true"
        />
        <x-form-input 
            name="apoderado_nombre_notario" 
            label="Nombre del Notario" 
            placeholder="Nombre del notario"
            required 
            data-required="true"
        />
        <x-form-input 
            name="apoderado_numero_notario" 
            label="Número del Notario" 
            placeholder="Número del notario"
            required 
            data-required="true"
        />
        <x-form-select 
            name="apoderado_entidad_federativa" 
            label="Entidad Federativa" 
            placeholder="Seleccione una entidad"
            :options="$estados ?? []"
            required 
            data-required="true"
        />
        <x-form-input 
            type="date"
            name="apoderado_fecha_escritura" 
            label="Fecha de Escritura" 
            required 
            data-required="true"
        />
        <x-form-input 
            name="apoderado_numero_registro" 
            label="Número de Registro" 
            placeholder="Número de registro"
            required 
            data-required="true"
        />
        <x-form-input 
            type="date"
            name="apoderado_fecha_inscripcion" 
            label="Fecha de Inscripción" 
            required 
            data-required="true"
        />
    </div>
</div> 