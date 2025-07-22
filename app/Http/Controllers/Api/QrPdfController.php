<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class QrPdfController extends Controller
{
    public function extract(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:5120',
        ]);

        $pdf = $request->file('pdf');
        $path = $pdf->storeAs('temp', uniqid().'.pdf');

        $pythonScript = base_path('app/Python/extract_qr_url_from_pdf.py');
        $fullPdfPath = storage_path('app/'.$path);

        $process = new Process(['python', $pythonScript, $fullPdfPath]);
        $process->run();

        // Eliminar el archivo temporal
        Storage::delete($path);

        if (! $process->isSuccessful()) {
            return response()->json(['error' => 'Error al procesar el PDF'], 500);
        }

        $output = trim($process->getOutput());
        if (preg_match('/URL encontrada: (.+)/', $output, $matches)) {
            return response()->json(['url' => $matches[1]]);
        } else {
            return response()->json(['error' => 'No se encontró un QR con URL en el PDF'], 404);
        }
    }
}
