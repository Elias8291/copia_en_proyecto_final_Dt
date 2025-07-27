#!/usr/bin/env python3
"""
Script minimalista para extraer códigos QR de archivos PDF
Versión de prueba que simula la extracción
"""

import sys
import json
import os

def extract_qr_url_from_pdf(pdf_path):
    """
    Simula la extracción de QR para pruebas
    """
    try:
        # Verificar que el archivo existe
        if not os.path.exists(pdf_path):
            return None
        
        # Para pruebas, retornar una URL simulada
        # En producción, aquí iría la lógica real de extracción
        return "https://siat.sat.gob.mx/app/qr/faces/pages/consultaQR.jsf?D1=10&D2=1&D3=12345678_12345678"
        
    except Exception as e:
        print(f"Error: {str(e)}", file=sys.stderr)
        return None

def extract_qr_from_pdf(pdf_path):
    """
    Función wrapper para mantener compatibilidad con el controlador PHP
    """
    try:
        url = extract_qr_url_from_pdf(pdf_path)
        
        if url:
            return {"success": True, "url": url}
        else:
            return {"success": False, "error": "No se encontró código QR válido en el PDF"}
            
    except Exception as e:
        return {"success": False, "error": f"Error procesando PDF: {str(e)}"}

def main():
    if len(sys.argv) != 2:
        print(json.dumps({"success": False, "error": "Uso: python qr_extractor_minimal.py <ruta_pdf>"}))
        sys.exit(1)
    
    pdf_path = sys.argv[1]
    result = extract_qr_from_pdf(pdf_path)
    print(json.dumps(result))

if __name__ == "__main__":
    main() 