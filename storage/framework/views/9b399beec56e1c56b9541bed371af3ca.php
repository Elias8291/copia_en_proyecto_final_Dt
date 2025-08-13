<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous">
    
    
    <link rel="stylesheet" href="/build/assets/app-CZkbBSon.css">
    <script src="/build/assets/app-D-nbQjmd.js" defer></script>
    
    <link rel="stylesheet" href="/css/custom.css">
    <link rel="stylesheet" href="/css/tramite-forms.css">
    <link rel="stylesheet" href="/css/form-validator.css">
    <link rel="stylesheet" href="/css/global-input-styles.css">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @livewireStyles
</head>

<body class="font-sans antialiased">
    <div class="bg-logo-pattern"></div>

    <div class="min-h-screen relative" x-data="{ sidebarOpen: false, sidebarHovered: false }">
        <header class="fixed top-0 inset-x-0 z-50">
            <?php echo $__env->make('layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </header>

        <div class="flex pt-16">
            <div class="hidden md:block">
                <?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <div class="md:hidden">
                <?php echo $__env->make('layouts.sidebar-mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <div class="flex-1 transition-all duration-300 md:ml-[65px]" x-data="{ sidebarHovered: false }"
                @sidebar-hover.window="sidebarHovered = $event.detail"
                :class="{ 'md:ml-72': sidebarHovered, 'md:ml-[65px]': !sidebarHovered }">
                <main class="w-full mx-auto">
                    <?php echo $__env->yieldContent('content'); ?>
                </main>
            </div>
        </div>
    </div>

    <?php if (isset($component)) { $__componentOriginal1d72b62bca0a51e0130f3972ff967024 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1d72b62bca0a51e0130f3972ff967024 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.alerts.alert','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.alerts.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1d72b62bca0a51e0130f3972ff967024)): ?>
<?php $attributes = $__attributesOriginal1d72b62bca0a51e0130f3972ff967024; ?>
<?php unset($__attributesOriginal1d72b62bca0a51e0130f3972ff967024); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1d72b62bca0a51e0130f3972ff967024)): ?>
<?php $component = $__componentOriginal1d72b62bca0a51e0130f3972ff967024; ?>
<?php unset($__componentOriginal1d72b62bca0a51e0130f3972ff967024); ?>
<?php endif; ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>

</html>
<?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/layouts/app.blade.php ENDPATH**/ ?>