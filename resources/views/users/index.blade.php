@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="min-h-screen py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 mb-6 sm:mb-8 transform hover:scale-[1.01] transition-all duration-300 border border-gray-100">
            <div class="flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <div class="bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-lg sm:rounded-xl p-2 sm:p-3 shadow-md flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Gestión de Usuarios</h1>
                        <p class="text-sm text-slate-600">Administre los usuarios del sistema</p>
                    </div>
                </div>
                <div>
                    <button id="btn-nuevo-usuario" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nuevo Usuario
                    </button>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50 mb-6">
            <div class="p-5">
                <div class="flex items-center mb-4">
                    <div class="bg-gradient-to-r from-[#9D2449] to-[#B91C1C] rounded-lg p-2 mr-3 shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold bg-gradient-to-r from-[#9D2449] to-[#B91C1C] bg-clip-text text-transparent">
                        Filtros de Búsqueda
                    </h3>
                </div>
                
                <div id="filtros-container" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <!-- Búsqueda por nombre/correo -->
                        <div class="group md:col-span-2">
                            <label class="block text-xs font-medium text-slate-700 mb-2 flex items-center">
                                <div class="w-5 h-5 bg-slate-100 rounded-md flex items-center justify-center mr-2">
                                    <svg class="w-3 h-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                Buscar
                            </label>
                            <div class="relative">
                                <input type="text" id="search" placeholder="Buscar por nombre o correo..." 
                                       class="w-full appearance-none bg-white border border-slate-200 rounded-lg pl-10 pr-4 py-2.5 text-sm text-slate-700 hover:border-[#9D2449]/30 focus:border-[#9D2449] focus:ring-2 focus:ring-[#9D2449]/20 transition-all duration-200">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Estado -->
                        <div class="group">
                            <label class="block text-xs font-medium text-slate-700 mb-2 flex items-center">
                                <div class="w-5 h-5 bg-green-100 rounded-md flex items-center justify-center mr-2">
                                    <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                Estado
                            </label>
                            <div class="relative">
                                <select id="estado" class="w-full appearance-none bg-white border border-slate-200 rounded-lg pl-10 pr-10 py-2.5 text-sm text-slate-700 hover:border-green-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all duration-200">
                                    <option value="">Todos</option>
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Rol -->
                        <div class="group">
                            <label class="block text-xs font-medium text-slate-700 mb-2 flex items-center">
                                <div class="w-5 h-5 bg-blue-100 rounded-md flex items-center justify-center mr-2">
                                    <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                Rol
                            </label>
                            <div class="relative">
                                <select id="rol" class="w-full appearance-none bg-white border border-slate-200 rounded-lg pl-10 pr-10 py-2.5 text-sm text-slate-700 hover:border-blue-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                    <option value="">Todos</option>
                                    <option value="admin">Administrador</option>
                                    <option value="user">Usuario</option>
                                </select>
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <!-- Fecha de registro -->
                        <div class="group">
                            <label class="block text-xs font-medium text-slate-700 mb-2 flex items-center">
                                <div class="w-5 h-5 bg-purple-100 rounded-md flex items-center justify-center mr-2">
                                    <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                Fecha de registro
                            </label>
                            <div class="relative">
                                <select id="fecha" class="w-full appearance-none bg-white border border-slate-200 rounded-lg pl-10 pr-10 py-2.5 text-sm text-slate-700 hover:border-purple-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all duration-200">
                                    <option value="">Todas las fechas</option>
                                    <option value="hoy">Hoy</option>
                                    <option value="semana">Esta semana</option>
                                    <option value="mes">Este mes</option>
                                </select>
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Elementos por página -->
                        <div class="group">
                            <label class="block text-xs font-medium text-slate-700 mb-2 flex items-center">
                                <div class="w-5 h-5 bg-orange-100 rounded-md flex items-center justify-center mr-2">
                                    <svg class="w-3 h-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                    </svg>
                                </div>
                                Mostrar
                            </label>
                            <div class="relative">
                                <select id="perPage" class="w-full appearance-none bg-white border border-slate-200 rounded-lg pl-10 pr-10 py-2.5 text-sm text-slate-700 hover:border-orange-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all duration-200">
                                    <option value="10">10 elementos</option>
                                    <option value="25">25 elementos</option>
                                    <option value="50">50 elementos</option>
                                    <option value="100">100 elementos</option>
                                </select>
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                    </svg>
                                </div>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botón de filtrar -->
                        <div class="flex items-end md:col-span-1 lg:col-span-2">
                            <button id="btn-filtrar" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Aplicar Filtros
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200/50 overflow-hidden">
            <div class="p-4 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800">Listado de Usuarios</h3>
                <p class="text-sm text-slate-500" id="total-usuarios">Cargando usuarios...</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Correo
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Rol
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Fecha de registro
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody id="usuarios-table-body" class="bg-white divide-y divide-slate-200">
                        <!-- Los datos se cargarán dinámicamente -->
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-slate-500">
                                <div class="flex items-center justify-center">
                                    <svg class="animate-spin h-5 w-5 text-[#9D2449] mr-2" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Cargando usuarios...
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            <div id="pagination" class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-700" id="pagination-info">
                        Mostrando <span class="font-medium">0</span> de <span class="font-medium">0</span> usuarios
                    </div>
                    <div class="flex space-x-2">
                        <button id="prev-page" class="px-3 py-1 border border-slate-300 rounded-md text-sm bg-white text-slate-700 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Anterior
                        </button>
                        <div id="page-numbers" class="flex space-x-1">
                            <!-- Los números de página se generarán dinámicamente -->
                        </div>
                        <button id="next-page" class="px-3 py-1 border border-slate-300 rounded-md text-sm bg-white text-slate-700 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Siguiente
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear/editar usuario -->
<div id="user-modal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="modal-content">
        <div class="p-5 border-b border-slate-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-slate-800" id="modal-title">Nuevo Usuario</h3>
                <button id="close-modal" class="text-slate-400 hover:text-slate-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <form id="user-form" class="p-5 space-y-4">
            <input type="hidden" id="user-id">
            
            <div>
                <label for="nombre" class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#9D2449]/20 focus:border-[#9D2449]" required>
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico</label>
                <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#9D2449]/20 focus:border-[#9D2449]" required>
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#9D2449]/20 focus:border-[#9D2449]">
                <p class="text-xs text-slate-500 mt-1" id="password-help">La contraseña debe tener al menos 8 caracteres.</p>
            </div>
            
            <div>
                <label for="modal-rol" class="block text-sm font-medium text-slate-700 mb-1">Rol</label>
                <select id="modal-rol" name="rol" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#9D2449]/20 focus:border-[#9D2449]" required>
                    <option value="user">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" id="estado" name="estado" class="h-4 w-4 text-[#9D2449] focus:ring-[#9D2449] border-slate-300 rounded">
                <label for="estado" class="ml-2 block text-sm text-slate-700">Usuario activo</label>
            </div>
            
            <div class="pt-4 border-t border-slate-200 flex justify-end space-x-3">
                <button type="button" id="cancel-form" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white rounded-lg hover:from-[#8B1E3F] hover:to-[#A91B1B] shadow-sm hover:shadow-md transition-all duration-200">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let currentPage = 1;
    let totalPages = 1;
    let perPage = 10;
    let usuarios = [];
    let filteredUsuarios = [];
    
    // Referencias a elementos DOM
    const searchInput = document.getElementById('search');
    const estadoSelect = document.getElementById('estado');
    const rolSelect = document.getElementById('rol');
    const fechaSelect = document.getElementById('fecha');
    const perPageSelect = document.getElementById('perPage');
    const btnFiltrar = document.getElementById('btn-filtrar');
    const usuariosTableBody = document.getElementById('usuarios-table-body');
    const totalUsuarios = document.getElementById('total-usuarios');
    const paginationInfo = document.getElementById('pagination-info');
    const pageNumbers = document.getElementById('page-numbers');
    const prevPageBtn = document.getElementById('prev-page');
    const nextPageBtn = document.getElementById('next-page');
    
    // Modal
    const userModal = document.getElementById('user-modal');
    const modalContent = document.getElementById('modal-content');
    const modalTitle = document.getElementById('modal-title');
    const userForm = document.getElementById('user-form');
    const userId = document.getElementById('user-id');
    const nombreInput = document.getElementById('nombre');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const passwordHelp = document.getElementById('password-help');
    const modalRolSelect = document.getElementById('modal-rol');
    const estadoCheckbox = document.getElementById('estado');
    const btnNuevoUsuario = document.getElementById('btn-nuevo-usuario');
    const closeModalBtn = document.getElementById('close-modal');
    const cancelFormBtn = document.getElementById('cancel-form');
    
    // Inicializar
    cargarUsuarios();
    
    // Event Listeners
    btnFiltrar.addEventListener('click', aplicarFiltros);
    prevPageBtn.addEventListener('click', irPaginaAnterior);
    nextPageBtn.addEventListener('click', irPaginaSiguiente);
    btnNuevoUsuario.addEventListener('click', abrirModalNuevoUsuario);
    closeModalBtn.addEventListener('click', cerrarModal);
    cancelFormBtn.addEventListener('click', cerrarModal);
    userForm.addEventListener('submit', guardarUsuario);
    
    // También aplicar filtros al cambiar los selectores (para mejor UX)
    searchInput.addEventListener('input', debounce(aplicarFiltros, 300));
    estadoSelect.addEventListener('change', aplicarFiltros);
    rolSelect.addEventListener('change', aplicarFiltros);
    fechaSelect.addEventListener('change', aplicarFiltros);
    perPageSelect.addEventListener('change', aplicarFiltros);
    
    // Funciones
    async function cargarUsuarios() {
        try {
            const response = await fetch('/api/usuarios');
            if (!response.ok) throw new Error('Error al cargar usuarios');
            
            const data = await response.json();
            usuarios = data.usuarios || [];
            
            aplicarFiltros();
        } catch (error) {
            console.error('Error:', error);
            mostrarError('No se pudieron cargar los usuarios. Intente nuevamente más tarde.');
        }
    }
    
    function aplicarFiltros() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const estado = estadoSelect.value;
        const rol = rolSelect.value;
        const fecha = fechaSelect.value;
        perPage = parseInt(perPageSelect.value);
        
        // Filtrar usuarios
        filteredUsuarios = usuarios.filter(usuario => {
            // Filtro por búsqueda
            const matchSearch = searchTerm === '' || 
                usuario.nombre.toLowerCase().includes(searchTerm) || 
                usuario.correo.toLowerCase().includes(searchTerm);
            
            // Filtro por estado
            const matchEstado = estado === '' || usuario.estado === estado;
            
            // Filtro por rol
            const matchRol = rol === '' || (usuario.roles && usuario.roles[0] && usuario.roles[0].name === rol);
            
            // Filtro por fecha
            let matchFecha = true;
            if (fecha !== '') {
                const fechaRegistro = new Date(usuario.created_at);
                const hoy = new Date();
                
                if (fecha === 'hoy') {
                    matchFecha = fechaRegistro.toDateString() === hoy.toDateString();
                } else if (fecha === 'semana') {
                    const inicioSemana = new Date(hoy);
                    inicioSemana.setDate(hoy.getDate() - hoy.getDay());
                    matchFecha = fechaRegistro >= inicioSemana;
                } else if (fecha === 'mes') {
                    matchFecha = fechaRegistro.getMonth() === hoy.getMonth() && 
                                 fechaRegistro.getFullYear() === hoy.getFullYear();
                }
            }
            
            return matchSearch && matchEstado && matchRol && matchFecha;
        });
        
        // Resetear paginación
        currentPage = 1;
        totalPages = Math.ceil(filteredUsuarios.length / perPage);
        
        // Actualizar UI
        renderizarTabla();
        actualizarPaginacion();
    }
    
    function renderizarTabla() {
        // Calcular índices para la página actual
        const startIndex = (currentPage - 1) * perPage;
        const endIndex = Math.min(startIndex + perPage, filteredUsuarios.length);
        const usuariosPagina = filteredUsuarios.slice(startIndex, endIndex);
        
        // Actualizar contador
        totalUsuarios.textContent = `Mostrando ${filteredUsuarios.length} usuario(s)`;
        
        // Limpiar tabla
        usuariosTableBody.innerHTML = '';
        
        if (usuariosPagina.length === 0) {
            usuariosTableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-slate-500">
                        No se encontraron usuarios con los filtros seleccionados
                    </td>
                </tr>
            `;
            return;
        }
        
        // Renderizar filas
        usuariosPagina.forEach(usuario => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-slate-50 transition-colors';
            
            // Formatear fecha
            const fecha = new Date(usuario.created_at);
            const fechaFormateada = fecha.toLocaleDateString('es-MX', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
            
            // Estado con color
            const estadoClass = usuario.estado === 'activo' 
                ? 'bg-green-100 text-green-800' 
                : 'bg-red-100 text-red-800';
            
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-slate-200 rounded-full flex items-center justify-center">
                            <span class="text-slate-600 font-medium">${usuario.nombre.charAt(0).toUpperCase()}</span>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-slate-900">${usuario.nombre}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-slate-900">${usuario.correo}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        ${usuario.roles && usuario.roles[0] ? usuario.roles[0].name : 'Sin rol'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${estadoClass}">
                        ${usuario.estado === 'activo' ? 'Activo' : 'Inactivo'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                    ${fechaFormateada}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button class="text-blue-600 hover:text-blue-900 mr-3 btn-editar" data-id="${usuario.id}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button class="text-red-600 hover:text-red-900 btn-eliminar" data-id="${usuario.id}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </td>
            `;
            
            usuariosTableBody.appendChild(row);
        });
        
        // Agregar event listeners a los botones de acción
        document.querySelectorAll('.btn-editar').forEach(btn => {
            btn.addEventListener('click', () => editarUsuario(btn.dataset.id));
        });
        
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', () => eliminarUsuario(btn.dataset.id));
        });
    }
    
    function actualizarPaginacion() {
        // Actualizar información de paginación
        const startIndex = (currentPage - 1) * perPage + 1;
        const endIndex = Math.min(currentPage * perPage, filteredUsuarios.length);
        
        if (filteredUsuarios.length > 0) {
            paginationInfo.innerHTML = `
                Mostrando <span class="font-medium">${startIndex}-${endIndex}</span> de 
                <span class="font-medium">${filteredUsuarios.length}</span> usuarios
            `;
        } else {
            paginationInfo.innerHTML = 'No hay usuarios para mostrar';
        }
        
        // Habilitar/deshabilitar botones de navegación
        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = currentPage === totalPages || totalPages === 0;
        
        // Generar números de página
        pageNumbers.innerHTML = '';
        
        // Limitar el número de páginas mostradas
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);
        
        if (endPage - startPage < 4 && totalPages > 4) {
            startPage = Math.max(1, endPage - 4);
        }
        
        // Mostrar primera página si estamos lejos
        if (startPage > 1) {
            const btn = crearBotonPagina(1);
            pageNumbers.appendChild(btn);
            
            if (startPage > 2) {
                const ellipsis = document.createElement('span');
                ellipsis.className = 'px-3 py-1 text-sm text-slate-500';
                ellipsis.textContent = '...';
                pageNumbers.appendChild(ellipsis);
            }
        }
        
        // Páginas centrales
        for (let i = startPage; i <= endPage; i++) {
            const btn = crearBotonPagina(i);
            pageNumbers.appendChild(btn);
        }
        
        // Mostrar última página si estamos lejos
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const ellipsis = document.createElement('span');
                ellipsis.className = 'px-3 py-1 text-sm text-slate-500';
                ellipsis.textContent = '...';
                pageNumbers.appendChild(ellipsis);
            }
            
            const btn = crearBotonPagina(totalPages);
            pageNumbers.appendChild(btn);
        }
    }
    
    function crearBotonPagina(numero) {
        const btn = document.createElement('button');
        btn.textContent = numero;
        
        if (numero === currentPage) {
            btn.className = 'px-3 py-1 border border-[#9D2449] bg-[#9D2449]/10 rounded-md text-sm text-[#9D2449] font-medium';
        } else {
            btn.className = 'px-3 py-1 border border-slate-300 rounded-md text-sm bg-white text-slate-700 hover:bg-slate-50';
            btn.addEventListener('click', () => irAPagina(numero));
        }
        
        return btn;
    }
    
    function irAPagina(pagina) {
        currentPage = pagina;
        renderizarTabla();
        actualizarPaginacion();
    }
    
    function irPaginaAnterior() {
        if (currentPage > 1) {
            currentPage--;
            renderizarTabla();
            actualizarPaginacion();
        }
    }
    
    function irPaginaSiguiente() {
        if (currentPage < totalPages) {
            currentPage++;
            renderizarTabla();
            actualizarPaginacion();
        }
    }
    
    // Funciones para el modal
    function abrirModalNuevoUsuario() {
        modalTitle.textContent = 'Nuevo Usuario';
        userForm.reset();
        userId.value = '';
        passwordInput.required = true;
        passwordHelp.textContent = 'La contraseña debe tener al menos 8 caracteres.';
        estadoCheckbox.checked = true;
        
        abrirModal();
    }
    
    function editarUsuario(id) {
        const usuario = usuarios.find(u => u.id == id);
        if (!usuario) return;
        
        modalTitle.textContent = 'Editar Usuario';
        userId.value = usuario.id;
        nombreInput.value = usuario.nombre;
        emailInput.value = usuario.correo;
        passwordInput.value = '';
        passwordInput.required = false;
        passwordHelp.textContent = 'Dejar en blanco para mantener la contraseña actual.';
        
        if (usuario.roles && usuario.roles[0]) {
            modalRolSelect.value = usuario.roles[0].name;
        } else {
            modalRolSelect.value = 'user';
        }
        
        estadoCheckbox.checked = usuario.estado === 'activo';
        
        abrirModal();
    }
    
    async function eliminarUsuario(id) {
        if (!confirm('¿Está seguro de que desea eliminar este usuario?')) return;
        
        try {
            const response = await fetch(`/api/usuarios/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (!response.ok) throw new Error('Error al eliminar usuario');
            
            // Actualizar lista
            usuarios = usuarios.filter(u => u.id != id);
            aplicarFiltros();
            
            mostrarNotificacion('Usuario eliminado correctamente', 'success');
        } catch (error) {
            console.error('Error:', error);
            mostrarError('No se pudo eliminar el usuario');
        }
    }
    
    async function guardarUsuario(e) {
        e.preventDefault();
        
        const formData = {
            nombre: nombreInput.value,
            email: emailInput.value,
            rol: modalRolSelect.value,
            estado: estadoCheckbox.checked ? 'activo' : 'inactivo'
        };
        
        if (passwordInput.value) {
            formData.password = passwordInput.value;
        }
        
        const isEditing = userId.value !== '';
        const url = isEditing ? `/api/usuarios/${userId.value}` : '/api/usuarios';
        const method = isEditing ? 'PUT' : 'POST';
        
        try {
            const response = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            });
            
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Error al guardar usuario');
            }
            
            const data = await response.json();
            
            // Actualizar lista
            if (isEditing) {
                const index = usuarios.findIndex(u => u.id == userId.value);
                if (index !== -1) {
                    usuarios[index] = data.usuario;
                }
            } else {
                usuarios.push(data.usuario);
            }
            
            aplicarFiltros();
            cerrarModal();
            
            mostrarNotificacion(
                isEditing ? 'Usuario actualizado correctamente' : 'Usuario creado correctamente',
                'success'
            );
        } catch (error) {
            console.error('Error:', error);
            mostrarError(error.message || 'Error al guardar usuario');
        }
    }
    
    function abrirModal() {
        userModal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    function cerrarModal() {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            userModal.classList.add('hidden');
        }, 300);
    }
    
    // Utilidades
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }
    
    function mostrarNotificacion(mensaje, tipo = 'info') {
        // Aquí puedes implementar tu sistema de notificaciones
        alert(mensaje);
    }
    
    function mostrarError(mensaje) {
        // Aquí puedes implementar tu sistema de notificaciones de error
        alert('Error: ' + mensaje);
    }
});
</script>
@endpush
@endsection