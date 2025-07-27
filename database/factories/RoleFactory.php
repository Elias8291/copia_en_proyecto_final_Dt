<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Role;

class RoleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'guard_name' => 'web',
            'description' => fake()->sentence(),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'admin',
            'description' => 'Administrador del sistema',
        ]);
    }

    public function user(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'user',
            'description' => 'Usuario estándar',
        ]);
    }

    public function moderator(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'moderator',
            'description' => 'Moderador del sistema',
        ]);
    }
} 