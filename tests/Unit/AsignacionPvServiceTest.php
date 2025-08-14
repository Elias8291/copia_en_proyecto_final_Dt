<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AsignacionPvService;
use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AsignacionPvServiceTest extends TestCase
{
    use RefreshDatabase;

    private AsignacionPvService $asignacionPvService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->asignacionPvService = app(AsignacionPvService::class);
    }

    /** @test */
    public function puede_generar_pv_sin_ultimo_pv_sistema()
    {
        $resultado = $this->asignacionPvService->asignarPvYVigencia(
            901323,
            null,
            '2024-01-15'
        );

        $this->assertEquals('PV901323', $resultado['pv']);
        $this->assertEquals('2024-01-15', $resultado['vigencia_inicio']);
        $this->assertEquals('2025-01-15', $resultado['vigencia_fin']);
        $this->assertEquals('2024-01-15', $resultado['fecha_alta_padron']);
        $this->assertStringContainsString('sin correlativo base', $resultado['notas_validacion']);
    }

    /** @test */
    public function puede_generar_pv_con_ultimo_pv_sistema()
    {
        $resultado = $this->asignacionPvService->asignarPvYVigencia(
            57,
            'PV901323',
            '2024-01-15'
        );

        $this->assertEquals('PV90132357', $resultado['pv']);
        $this->assertEquals('2024-01-15', $resultado['vigencia_inicio']);
        $this->assertEquals('2025-01-15', $resultado['vigencia_fin']);
        $this->assertEquals('2024-01-15', $resultado['fecha_alta_padron']);
        $this->assertStringContainsString('correlativo base: 901323', $resultado['notas_validacion']);
    }

    /** @test */
    public function maneja_fechas_bisiestas_correctamente()
    {
        $resultado = $this->asignacionPvService->asignarPvYVigencia(
            123,
            null,
            '2024-02-29'
        );

        $this->assertEquals('PV123', $resultado['pv']);
        $this->assertEquals('2024-02-29', $resultado['vigencia_inicio']);
        $this->assertEquals('2025-02-28', $resultado['vigencia_fin']); // 2025 no es bisiesto
        $this->assertEquals('2024-02-29', $resultado['fecha_alta_padron']);
    }

    /** @test */
    public function maneja_fechas_bisiestas_cuando_el_siguiente_ano_es_bisiesto()
    {
        $resultado = $this->asignacionPvService->asignarPvYVigencia(
            123,
            null,
            '2023-02-29'
        );

        $this->assertEquals('PV123', $resultado['pv']);
        $this->assertEquals('2023-02-29', $resultado['vigencia_inicio']);
        $this->assertEquals('2024-02-29', $resultado['vigencia_fin']); // 2024 es bisiesto
        $this->assertEquals('2023-02-29', $resultado['fecha_alta_padron']);
    }

    /** @test */
    public function valida_formato_de_fecha_invalido()
    {
        $resultado = $this->asignacionPvService->asignarPvYVigencia(
            123,
            null,
            'fecha-invalida'
        );

        $this->assertNull($resultado['pv']);
        $this->assertStringContainsString('Formato de fecha inválido', $resultado['notas_validacion']);
    }

    /** @test */
    public function valida_formato_de_ultimo_pv_invalido()
    {
        $resultado = $this->asignacionPvService->asignarPvYVigencia(
            123,
            'INVALIDO',
            '2024-01-15'
        );

        $this->assertEquals('PV123', $resultado['pv']); // Debe ignorar el PV inválido
        $this->assertStringContainsString('Formato de último PV inválido', $resultado['notas_validacion']);
    }

    /** @test */
    public function detecta_pv_duplicado()
    {
        // Crear un proveedor con PV existente
        Proveedor::factory()->create([
            'pv_numero' => 'PV123'
        ]);

        $resultado = $this->asignacionPvService->asignarPvYVigencia(
            123,
            null,
            '2024-01-15'
        );

        $this->assertNull($resultado['pv']);
        $this->assertStringContainsString('PV no es único', $resultado['notas_validacion']);
    }

    /** @test */
    public function puede_aplicar_asignacion_a_proveedor()
    {
        $proveedor = Proveedor::factory()->create([
            'pv_numero' => null,
            'estado_padron' => 'Pendiente'
        ]);

        $datosAsignacion = [
            'pv' => 'PV123',
            'vigencia_inicio' => '2024-01-15',
            'vigencia_fin' => '2025-01-15',
            'fecha_alta_padron' => '2024-01-15',
            'notas_validacion' => 'Test'
        ];

        $resultado = $this->asignacionPvService->aplicarAsignacion($proveedor, $datosAsignacion);

        $this->assertTrue($resultado);

        $proveedor->refresh();
        $this->assertEquals('PV123', $proveedor->pv_numero);
        $this->assertEquals('Activo', $proveedor->estado_padron);
        $this->assertEquals('2024-01-15', $proveedor->fecha_alta_padron->format('Y-m-d'));
        $this->assertEquals('2025-01-15', $proveedor->fecha_vencimiento_padron->format('Y-m-d'));
    }

    /** @test */
    public function no_sobrescribe_fecha_alta_padron_existente()
    {
        $proveedor = Proveedor::factory()->create([
            'pv_numero' => null,
            'fecha_alta_padron' => '2023-01-01',
            'estado_padron' => 'Pendiente'
        ]);

        $datosAsignacion = [
            'pv' => 'PV123',
            'vigencia_inicio' => '2024-01-15',
            'vigencia_fin' => '2025-01-15',
            'fecha_alta_padron' => '2024-01-15',
            'notas_validacion' => 'Test'
        ];

        $resultado = $this->asignacionPvService->aplicarAsignacion($proveedor, $datosAsignacion);

        $this->assertTrue($resultado);

        $proveedor->refresh();
        $this->assertEquals('2023-01-01', $proveedor->fecha_alta_padron->format('Y-m-d')); // Mantiene la fecha original
        $this->assertEquals('PV123', $proveedor->pv_numero);
        $this->assertEquals('2025-01-15', $proveedor->fecha_vencimiento_padron->format('Y-m-d'));
    }
}
