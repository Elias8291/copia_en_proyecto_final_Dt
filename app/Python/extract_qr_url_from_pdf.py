import fitz  # PyMuPDF
from PIL import Image
import io
import cv2
import numpy as np
from pyzbar.pyzbar import decode


def extract_qr_url_from_pdf(pdf_path):
    # Abrir el PDF
    doc = fitz.open(pdf_path)
    for page_num in range(len(doc)):
        page = doc.load_page(page_num)
        pix = page.get_pixmap()
        img_data = pix.tobytes()
        img = Image.open(io.BytesIO(img_data))
        # Convertir a formato compatible con OpenCV
        img_cv = cv2.cvtColor(np.array(img), cv2.COLOR_RGB2BGR)
        # Buscar QR
        decoded_objs = decode(img_cv)
        for obj in decoded_objs:
            data = obj.data.decode('utf-8')
            if data.startswith('http'):
                return data
    return None

if __name__ == "__main__":
    import sys
    if len(sys.argv) < 2:
        print("Uso: python extract_qr_url_from_pdf.py archivo.pdf")
        sys.exit(1)
    pdf_path = sys.argv[1]
    url = extract_qr_url_from_pdf(pdf_path)
    if url:
        print(f"URL encontrada: {url}")
    else:
        print("No se encontró un QR con URL en el PDF.") 