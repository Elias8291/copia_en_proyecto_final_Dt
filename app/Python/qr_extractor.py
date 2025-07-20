#!/usr/bin/env python3
"""
Script para extraer códigos QR de archivos PDF
Requiere: pip install PyMuPDF pyzbar pillow opencv-python
"""

import sys
import json
import fitz  # PyMuPDF
from PIL import Image
from pyzbar.pyzbar import decode
import cv2
import numpy as np
import io
import os

def extract_qr_url_from_pdf(pdf_path):
    """
    Extrae URL de código QR de un archivo PDF
    Versión optimizada y simplificada
    """
    try:
        # Verificar que el archivo existe
        if not os.path.exists(pdf_path):
            return None
        
        # Abrir el PDF
        doc = fitz.open(pdf_path)
        
        for page_num in range(len(doc)):
            page = doc.load_page(page_num)
            
            # Convertir página a imagen con mejor resolución
            pix = page.get_pixmap()
            img_data = pix.tobytes()
            
            # Convertir a PIL Image
            img = Image.open(io.BytesIO(img_data))
            
            # Convertir a formato compatible con OpenCV
            img_cv = cv2.cvtColor(np.array(img), cv2.COLOR_RGB2BGR)
            
            # Buscar QR con pyzbar (más rápido y confiable)
            decoded_objs = decode(img_cv)
            
            for obj in decoded_objs:
                data = obj.data.decode('utf-8')
                if data.startswith('http'):
                    doc.close()
                    return data
        
        doc.close()
        return None
        
    except Exception as e:
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
        print(json.dumps({"success": False, "error": "Uso: python qr_extractor.py <ruta_pdf>"}))
        sys.exit(1)
    
    pdf_path = sys.argv[1]
    result = extract_qr_from_pdf(pdf_path)
    print(json.dumps(result))

if __name__ == "__main__":
    main()