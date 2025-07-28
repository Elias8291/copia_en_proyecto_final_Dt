<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CatalogoArchivoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->words(3, true),
            'descripcion' => fake()->sentence(),
            'tipo_persona' => fake()->randomElement(['Física', 'Moral', 'Ambas']),
            'tipo_archivo' => fake()->randomElement(['png', 'pdf', 'mp3', 'mp4']),
            'es_visible' => fake()->boolean(80),
        ];
    }

    public function visible(): static
    {
        return $this->state(fn (array $attributes) => [
            'es_visible' => true,
        ]);
    }

    public function hidden(): static
    {
        return $this->state(fn (array $attributes) => [
            'es_visible' => false,
        ]);
    }
} 