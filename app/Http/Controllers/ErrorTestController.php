<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class ErrorTestController extends Controller
{
    /**
     * Test 404 error
     */
    public function test404()
    {
        throw new NotFoundHttpException('Recurso de prueba no encontrado');
    }

    /**
     * Test 403 error
     */
    public function test403()
    {
        throw new AccessDeniedHttpException('Acceso denegado para prueba');
    }

    /**
     * Test 419 error
     */
    public function test419()
    {
        throw new TokenMismatchException('Token CSRF de prueba inválido');
    }

    /**
     * Test 429 error
     */
    public function test429()
    {
        throw new TooManyRequestsHttpException(60, 'Demasiadas solicitudes de prueba');
    }

    /**
     * Test 500 error
     */
    public function test500()
    {
        throw new Exception('Error interno de prueba del servidor');
    }

    /**
     * Test 503 error
     */
    public function test503()
    {
        throw new ServiceUnavailableHttpException(3600, 'Servicio no disponible para prueba');
    }

    /**
     * Test 405 error
     */
    public function test405()
    {
        throw new MethodNotAllowedHttpException(['GET', 'POST'], 'Método no permitido para prueba');
    }

    /**
     * Test 401 error
     */
    public function test401()
    {
        throw new AuthenticationException('No autenticado para prueba');
    }

    /**
     * Test 422 validation error
     */
    public function test422(Request $request)
    {
        $request->validate([
            'required_field' => 'required|string|min:5',
            'email_field' => 'required|email',
            'numeric_field' => 'required|numeric|min:1|max:100',
        ]);

        return response()->json(['message' => 'Validación exitosa']);
    }

    /**
     * Show error test page
     */
    public function index()
    {
        return view('errors.test');
    }

    /**
     * API endpoint to test errors
     */
    public function apiTest(Request $request): JsonResponse
    {
        $errorType = $request->get('type', '500');

        return match ($errorType) {
            '404' => $this->test404(),
            '403' => $this->test403(),
            '419' => $this->test419(),
            '429' => $this->test429(),
            '500' => $this->test500(),
            '503' => $this->test503(),
            '405' => $this->test405(),
            '401' => $this->test401(),
            '422' => $this->test422($request),
            default => response()->json(['message' => 'Tipo de error no válido'], 400),
        };
    }
}
