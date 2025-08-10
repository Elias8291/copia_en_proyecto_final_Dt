#!/bin/bash

echo "========================================"
echo "   BUILDING ASSETS FOR PRODUCTION"
echo "========================================"
echo

echo "[1/3] Installing dependencies..."
npm install
if [ $? -ne 0 ]; then
    echo "ERROR: Failed to install dependencies"
    exit 1
fi

echo
echo "[2/3] Building assets..."
npm run build
if [ $? -ne 0 ]; then
    echo "ERROR: Failed to build assets"
    exit 1
fi

echo
echo "[3/3] Verifying build files..."
if [ ! -f "public/build/manifest.json" ]; then
    echo "ERROR: manifest.json not found"
    exit 1
fi

if [ ! -f "public/build/assets/app-"*.css ]; then
    echo "ERROR: CSS file not found"
    exit 1
fi

if [ ! -f "public/build/assets/app-"*.js ]; then
    echo "ERROR: JS file not found"
    exit 1
fi

echo
echo "========================================"
echo "   BUILD COMPLETED SUCCESSFULLY!"
echo "========================================"
echo
echo "Files created:"
ls -la public/build/assets/
echo
echo "You can now deploy your application."
echo "The Tailwind CSS styles will work correctly"
echo "in production environments like Cloudflare Tunnel."
echo
