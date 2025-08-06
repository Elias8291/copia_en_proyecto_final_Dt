<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMINISTRADOR = 'Super Administrador';
    case ADMINISTRADOR = 'Administrador';
    case REVISOR_DIGITAL = 'Revisor Digital';
    case REVISOR_PRESENCIAL = 'Revisor Presencial';
    case REVISOR_DOMICILIARIO = 'Revisor Domiciliario';
    case PROVEEDOR = 'Proveedor';
    case SOLICITANTE = 'Solicitante';

    /**
     * Obtener el nivel jerárquico del rol
     */
    public function level(): string
    {
        return match($this) {
            self::SUPER_ADMINISTRADOR, self::ADMINISTRADOR => 'admin',
            self::REVISOR_DIGITAL, self::REVISOR_PRESENCIAL, self::REVISOR_DOMICILIARIO => 'reviewer',
            self::PROVEEDOR, self::SOLICITANTE => 'client',
        };
    }

    /**
     * Obtener la descripción del rol
     */
    public function description(): string
    {
        return match($this) {
            self::SUPER_ADMINISTRADOR => 'Acceso total al sistema, gestión de usuarios y configuraciones',
            self::ADMINISTRADOR => 'Gestión general del sistema y supervisión de procesos',
            self::REVISOR_DIGITAL => 'Especialista en revisión de documentos digitales y validaciones online',
            self::REVISOR_PRESENCIAL => 'Especialista en cotejo presencial y validación física de documentos',
            self::REVISOR_DOMICILIARIO => 'Especialista en verificaciones domiciliarias y inspecciones de campo',
            self::PROVEEDOR => 'Empresa o persona física que solicita servicios y gestiona trámites',
            self::SOLICITANTE => 'Usuario individual que realiza solicitudes específicas',
        };
    }

    /**
     * Obtener el icono del rol
     */
    public function icon(): string
    {
        return match($this) {
            self::SUPER_ADMINISTRADOR => '👑',
            self::ADMINISTRADOR => '⚡',
            self::REVISOR_DIGITAL => '💻',
            self::REVISOR_PRESENCIAL => '👥',
            self::REVISOR_DOMICILIARIO => '🏠',
            self::PROVEEDOR => '🏢',
            self::SOLICITANTE => '👤',
        };
    }

    /**
     * Verificar si es un rol administrativo
     */
    public function isAdmin(): bool
    {
        return in_array($this, [self::SUPER_ADMINISTRADOR, self::ADMINISTRADOR]);
    }

    /**
     * Verificar si es un rol de revisor
     */
    public function isReviewer(): bool
    {
        return in_array($this, [
            self::REVISOR_DIGITAL,
            self::REVISOR_PRESENCIAL,
            self::REVISOR_DOMICILIARIO
        ]);
    }

    /**
     * Verificar si es un rol de cliente
     */
    public function isClient(): bool
    {
        return in_array($this, [self::PROVEEDOR, self::SOLICITANTE]);
    }

    /**
     * Obtener todos los roles como array
     */
    public static function toArray(): array
    {
        $roles = [];
        foreach (self::cases() as $role) {
            $roles[$role->value] = $role->value;
        }
        return $roles;
    }

    /**
     * Obtener roles agrupados por nivel
     */
    public static function getByLevel(): array
    {
        return [
            'admin' => [
                self::SUPER_ADMINISTRADOR->value,
                self::ADMINISTRADOR->value,
            ],
            'reviewer' => [
                self::REVISOR_DIGITAL->value,
                self::REVISOR_PRESENCIAL->value,
                self::REVISOR_DOMICILIARIO->value,
            ],
            'client' => [
                self::PROVEEDOR->value,
                self::SOLICITANTE->value,
            ]
        ];
    }
} 