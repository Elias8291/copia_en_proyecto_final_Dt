<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nombres = [
            'Juan Carlos', 'María Elena', 'Roberto', 'Ana Patricia', 'Luis Fernando',
            'Carmen', 'Miguel Ángel', 'Sofia', 'Carlos Alberto', 'Laura',
            'Francisco Javier', 'Isabel', 'José Luis', 'Gabriela', 'Antonio',
            'Patricia', 'Manuel', 'Adriana', 'Ricardo', 'Claudia',
            'Fernando', 'Verónica', 'Eduardo', 'Silvia', 'Alberto',
            'Rosa María', 'Guillermo', 'Teresa', 'Héctor', 'Mónica'
        ];
        
        $apellidos = [
            'García', 'López', 'Martínez', 'Hernández', 'González',
            'Pérez', 'Rodríguez', 'Sánchez', 'Ramírez', 'Cruz',
            'Flores', 'Gómez', 'Morales', 'Vázquez', 'Jiménez',
            'Torres', 'Ruiz', 'Díaz', 'Serrano', 'Moreno',
            'Alvarez', 'Romero', 'Navarro', 'Ruiz', 'Mendoza',
            'Castro', 'Ortiz', 'Silva', 'Núñez', 'Medina'
        ];
        
        $nombre = fake()->randomElement($nombres) . ' ' . fake()->randomElement($apellidos) . ' ' . fake()->randomElement($apellidos);
        $nombreLimpio = strtolower(str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['', 'a', 'e', 'i', 'o', 'u', 'n'], $nombre));
        $nombreLimpio = preg_replace('/[^a-z]/', '', $nombreLimpio);
        
        // Generar correo institucional del gobierno de Oaxaca
        $dominios = [
            'oaxaca.gob.mx',
            'sedesoh.oaxaca.gob.mx',
            'sefin.oaxaca.gob.mx',
            'sedapa.oaxaca.gob.mx',
            'sedatu.oaxaca.gob.mx',
            'sedesoh.oaxaca.gob.mx',
            'sedeco.oaxaca.gob.mx',
            'sedesoh.oaxaca.gob.mx',
            'semaedeso.oaxaca.gob.mx',
            'ssppo.oaxaca.gob.mx'
        ];
        
        $correo = $nombreLimpio . '@' . fake()->randomElement($dominios);
        
        // Generar RFC realista para persona física (13 caracteres)
        $apellidoPaterno = fake()->randomElement($apellidos);
        $apellidoMaterno = fake()->randomElement($apellidos);
        $nombrePila = fake()->randomElement($nombres);
        
        // Obtener iniciales de apellidos y nombre
        $inicialApellidoPaterno = substr($apellidoPaterno, 0, 2);
        $inicialApellidoMaterno = substr($apellidoMaterno, 0, 1);
        $inicialNombre = substr($nombrePila, 0, 1);
        
        // Generar fecha de nacimiento (entre 1960 y 2000)
        $anio = fake()->numberBetween(1960, 2000);
        $mes = str_pad(fake()->numberBetween(1, 12), 2, '0', STR_PAD_LEFT);
        $dia = str_pad(fake()->numberBetween(1, 28), 2, '0', STR_PAD_LEFT);
        
        // Clave de entidad federativa (OAX para Oaxaca)
        $entidad = 'OAX';
        
        // Homoclave (3 caracteres alfanuméricos)
        $homoclave = strtoupper(Str::random(3));
        
        $rfc = $inicialApellidoPaterno . $inicialApellidoMaterno . $inicialNombre . $anio . $mes . $dia . $entidad . $homoclave;
        
        return [
            'nombre' => $nombre,
            'correo' => $correo,
            'rfc' => $rfc,
            'password' => bcrypt('password'),
            'estado' => 'pendiente',
            'verification_token' => Str::random(60),
            'fecha_verificacion_correo' => null,
            'remember_token' => Str::random(10),
        ];
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_verificacion_correo' => now(),
            'estado' => 'activo',
            'verification_token' => null,
        ]);
    }
}
