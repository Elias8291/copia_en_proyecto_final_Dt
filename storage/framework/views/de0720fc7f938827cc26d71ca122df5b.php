<?php $__env->startSection('title', 'Iniciar Sesión - Padrón de Proveedores del Estado de Oaxaca'); ?>

<?php $__env->startSection('content'); ?>

    <!-- Modal de Correo Verificado -->
    <?php echo $__env->make('components.modals.auth.email-verified', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if(session('showEmailVerifiedModal')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                mostrarModalCorreoVerificado();
            });
        </script>
    <?php endif; ?>
    <form id="loginForm" action="<?php echo e(route('login')); ?>" method="POST" class="space-y-4">
        <?php echo csrf_field(); ?>
        <div class="text-center mb-4">
            <div class="flex flex-col items-center justify-center mb-3">
                <div
                    class="w-14 h-14 flex items-center justify-center mb-2 bg-gradient-to-br from-primary/10 to-primary-dark/10 rounded-full p-2">
                    <img src="<?php echo e(asset('images/logoprin.jpg')); ?>" alt="Logo Estado de Oaxaca"
                        class="w-full h-full object-contain rounded-full">
                </div>
                <div class="text-center space-y-1">
                    <span class="text-primary font-bold text-sm block tracking-wide">ADMINISTRACIÓN</span>
                    <span class="text-gray-500 text-xs font-medium uppercase tracking-wider">Gobierno del Estado de
                        Oaxaca</span>
                </div>
            </div>

            <div class="space-y-1 mb-2">
                <h1 class="text-lg font-bold text-gray-800 leading-tight">
                    Iniciar Sesión
                </h1>
                <h2 class="text-sm font-semibold text-primary leading-tight">
                    Padrón de Proveedores del<br class="lg:hidden">
                    <span class="text-primary-dark">Estado de Oaxaca</span>
                </h2>
            </div>
        </div>
        <?php if(session('error') || $errors->any()): ?>
            <div class="flex justify-center mb-3">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-red-100 border border-red-200 shadow-sm animate-fadeInUp">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xs text-red-700 font-medium">
                        <?php if(session('error')): ?>
                            <?php echo e(session('error')); ?>

                        <?php endif; ?>
                        <?php if($errors->any()): ?>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e($error); ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        <?php endif; ?>
        <div class="space-y-2">
            <div>
                <label for="rfc" class="block text-xs font-medium text-gray-700 mb-0.5">RFC</label>
                <div class="relative">
                    <input type="text" id="rfc" name="rfc" required
                        class="w-full px-2.5 py-1.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors duration-300 uppercase <?php $__errorArgs = ['rfc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> text-sm"
                        placeholder="Ej: XAXX010101000">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-medium text-gray-700 mb-0.5">Contraseña</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                        class="w-full px-2.5 py-1.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors duration-300 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> text-sm"
                        placeholder="••••••••">
                    <button type="button" onclick="togglePassword()"
                        class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="toggleIcon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between pt-1">
                <a href="<?php echo e(route('password.request')); ?>"
                    class="text-xs text-primary hover:text-primary-dark transition-colors duration-300">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>
        </div>

        <div class="space-y-1.5 pt-2">
            <button type="submit" onclick="validateAndSubmit(event)"
                class="group w-full bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white font-semibold py-2.5 px-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 relative overflow-hidden text-sm">
                <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-r from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <div class="relative flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                        </path>
                    </svg>
                    <span>Iniciar Sesión</span>
                    <div
                        class="absolute -right-2 w-2 h-2 bg-white/30 rounded-full opacity-0 group-hover:opacity-100 group-hover:animate-ping">
                    </div>
                </div>
            </button>

            <a href="/"
                class="group w-full bg-white hover:bg-gray-50 text-primary hover:text-primary-dark font-semibold py-2.5 px-4 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 border-2 border-primary/20 hover:border-primary/40 relative overflow-hidden inline-flex items-center justify-center text-sm">
                <div
                    class="absolute inset-0 bg-gradient-to-r from-primary/5 to-primary-dark/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
                <div class="relative flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Volver</span>
                </div>
            </a>
        </div>
    </form>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
        `;
            } else {
                passwordInput.type = 'password';
                toggleIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        `;
            }
        }

        function validateAndSubmit(event) {
            event.preventDefault();
            const rfcInput = document.getElementById('rfc');
            const passwordInput = document.getElementById('password');
            const form = document.getElementById('loginForm');
            
            if (!rfcInput.value.trim()) {
                rfcInput.focus();
                rfcInput.classList.add('border-red-500');
                return false;
            }
            
            if (!passwordInput.value.trim()) {
                passwordInput.focus();
                passwordInput.classList.add('border-red-500');
                return false;
            }
            
            rfcInput.classList.remove('border-red-500');
            passwordInput.classList.remove('border-red-500');
            
            showLoading({ text: 'Iniciando sesión...' });
            
            setTimeout(() => {
                form.submit();
            }, 100);
        }

        document.getElementById('rfc').addEventListener('input', function() {
            this.classList.remove('border-red-500');
        });

        document.getElementById('password').addEventListener('input', function() {
            this.classList.remove('border-red-500');
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/auth/login.blade.php ENDPATH**/ ?>