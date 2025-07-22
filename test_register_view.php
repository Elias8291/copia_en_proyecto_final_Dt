<?php

require_once 'vendor/autoload.php';

// Create a simple Laravel app instance for testing
$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// Test the view compilation
try {
    // Mock the errors object
    $errors = new Illuminate\Support\MessageBag();
    
    // Test view compilation
    $view = view('auth.register', compact('errors'));
    echo "✅ View compiles successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}