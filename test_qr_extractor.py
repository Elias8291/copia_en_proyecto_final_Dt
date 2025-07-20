#!/usr/bin/env python3
"""
Script de prueba para el extractor de QR
"""

import sys
import os
sys.path.append('app/Python')

from qr_extractor import extract_qr_url_from_pdf

def test_extractor():
    print("🔍 Probando extractor de QR desde PDF...")
    
    # Verificar dependencias
    try:
        import fitz
        print("✅ PyMuPDF instalado correctamente")
    except ImportError:
        print("❌ PyMuPDF no instalado. Ejecuta: pip install PyMuPDF")
        return False
    
    try:
        from pyzbar.pyzbar import decode
        print("✅ pyzbar instalado correctamente")
    except ImportError:
        print("❌ pyzbar no instalado. Ejecuta: pip install pyzbar")
        return False
    
    try:
        import cv2
        print("✅ OpenCV instalado correctamente")
    except ImportError:
        print("❌ OpenCV no instalado. Ejecuta: pip install opencv-python")
        return False
    
    try:
        from PIL import Image
        print("✅ Pillow instalado correctamente")
    except ImportError:
        print("❌ Pillow no instalado. Ejecuta: pip install pillow")
        return False
    
    print("\n🎉 Todas las dependencias están instaladas correctamente!")
    print("\nPara probar con un PDF real:")
    print("python app/Python/qr_extractor.py ruta/a/tu/archivo.pdf")
    
    return True

if __name__ == "__main__":
    test_extractor()