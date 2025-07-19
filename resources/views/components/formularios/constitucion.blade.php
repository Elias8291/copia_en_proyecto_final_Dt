<!-- SECCIÓN 3: CONSTITUCIÓN (Solo Morales) -->
<div id="seccion-3" class="section-content p-4 sm:p-8 border-t border-gray-200 mt-8 persona-moral-section">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-2 flex items-center">
            <div class="w-8 h-8 bg-[#9d2449]/10 rounded-lg flex items-center justify-center mr-3">
                <span class="text-[#9d2449] font-bold text-sm">3</span>
            </div>
            Constitución
        </h2>
        <p class="text-gray-600 text-sm">Datos de constitución de la empresa (Solo para personas morales)</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-form-input 
            name="numero_escritura" 
            label="Número de Escritura" 
            placeholder="Número de escritura constitutiva"
            required 
            data-required="true"
        />
        <x-form-input 
            type="date"
            name="fecha_constitucion" 
            label="Fecha de Constitución" 
            required 
            data-required="true"
        />
        <x-form-input 
            name="nombre_notario" 
            label="Nombre del Notario" 
            placeholder="Nombre completo del notario"
            required 
            data-required="true"
        />
        <x-form-select 
            name="entidad_federativa" 
            label="Entidad Federativa" 
            placeholder="Seleccione una entidad"
            :options="$estados ?? []"
            required 
            data-required="true"
        />
        <x-form-input 
            name="numero_notario" 
            label="Número de Notario" 
            placeholder="Número del notario"
            required 
            data-required="true"
        />
        <x-form-input 
            name="numero_registro" 
            label="Número de Registro" 
            placeholder="Número de registro público"
            required 
            data-required="true"
        />
        <x-form-input 
            type="date"
            name="fecha_inscripcion" 
            label="Fecha de Inscripción" 
            container_class="md:col-span-2"
            required 
            data-required="true"
        />
    </div>
</div> 