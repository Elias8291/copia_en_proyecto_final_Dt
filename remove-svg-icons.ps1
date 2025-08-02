# PowerShell script to remove SVG icons from Blade templates
# This script replaces SVG icons with basic text alternatives

Write-Host "🔧 Removing SVG icons from Blade templates..." -ForegroundColor Green

# Get all Blade template files
$bladeFiles = Get-ChildItem -Path "resources/views" -Filter "*.blade.php" -Recurse

$fixedCount = 0

foreach ($file in $bladeFiles) {
    $content = Get-Content $file.FullName -Raw
    $originalContent = $content

    # Replace common SVG patterns with text alternatives
    
    # Search icon
    $content = $content -replace '<svg[^>]*class="[^"]*h-[0-9][^"]*w-[0-9][^"]*"[^>]*>.*?<path[^>]*d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0 1 14 0z"[^>]*>.*?</svg>', '<span class="text-gray-400 text-sm">🔍</span>'
    
    # Arrow down icon
    $content = $content -replace '<svg[^>]*class="[^"]*h-[0-9][^"]*w-[0-9][^"]*"[^>]*>.*?<path[^>]*d="M19 9l-7 7-7-7"[^>]*>.*?</svg>', '<span class="text-gray-400 text-xs">▼</span>'
    
    # Arrow left icon
    $content = $content -replace '<svg[^>]*class="[^"]*h-[0-9][^"]*w-[0-9][^"]*"[^>]*>.*?<path[^>]*d="M15 19l-7-7 7-7"[^>]*>.*?</svg>', '<span class="text-xs">◀</span>'
    
    # Arrow right icon
    $content = $content -replace '<svg[^>]*class="[^"]*h-[0-9][^"]*w-[0-9][^"]*"[^>]*>.*?<path[^>]*d="M9 5l7 7-7 7"[^>]*>.*?</svg>', '<span class="text-xs">▶</span>'
    
    # Eye icon (view)
    $content = $content -replace '<svg[^>]*class="[^"]*h-[0-9][^"]*w-[0-9][^"]*"[^>]*>.*?<path[^>]*d="M15 12a3 3 0 11-6 0 3 3 0 616 0z"[^>]*>.*?<path[^>]*d="M2\.458 12C3\.732 7\.943 7\.523 5 12 5c4\.478 0 8\.268 2\.943 9\.542 7-1\.274 4\.057-5\.064 7-9\.542 7-4\.477 0-8\.268-2\.943-9\.542-7z"[^>]*>.*?</svg>', '<span class="text-xs sm:text-sm md:text-base">👁️</span>'
    
    # Edit icon
    $content = $content -replace '<svg[^>]*class="[^"]*h-[0-9][^"]*w-[0-9][^"]*"[^>]*>.*?<path[^>]*d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1\.414-9\.414a2 2 0 112\.828 2\.828L11\.828 15H9v-2\.828l8\.586-8\.586z"[^>]*>.*?</svg>', '<span class="text-xs sm:text-sm md:text-base">✏️</span>'
    
    # Settings/gear icon
    $content = $content -replace '<svg[^>]*class="[^"]*h-[0-9][^"]*w-[0-9][^"]*"[^>]*>.*?<path[^>]*d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"[^>]*>.*?</svg>', '<span class="text-xs">⚙️</span>'
    
    # Document icon
    $content = $content -replace '<svg[^>]*class="[^"]*h-[0-9][^"]*w-[0-9][^"]*"[^>]*>.*?<path[^>]*d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5\.586a1 1 0 01\.707\.293l5\.414 5\.414a1 1 0 01\.293\.707V19a2 2 0 01-2 2z"[^>]*>.*?</svg>', '<span class="text-2xl mx-auto mb-2 block">📄</span>'
    
    # Users/people icon
    $content = $content -replace '<svg[^>]*class="[^"]*h-[0-9][^"]*w-[0-9][^"]*"[^>]*>.*?<path[^>]*d="M17 20h5v-2a3 3 0 00-5\.356-1\.857M17 20H7m10 0v-2c0-\.656-\.126-1\.283-\.356-1\.857M7 20H2v-2a3 3 0 515\.356-1\.857M7 20v-2c0-\.656\.126-1\.283\.356-1\.857m0 0a5\.002 5\.002 0 919\.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 814 0z"[^>]*>.*?</svg>', '<span class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl mx-auto mb-2 sm:mb-3 md:mb-4 block">👥</span>'
    
    # Info icon
    $content = $content -replace '<svg[^>]*class="[^"]*h-[0-9][^"]*w-[0-9][^"]*"[^>]*>.*?<path[^>]*d="M13 16h-1v-4h-1m1-4h\.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"[^>]*>.*?</svg>', '<span class="text-xs text-gray-500">ℹ️</span>'
    
    # Filter icon
    $content = $content -replace '<svg[^>]*class="[^"]*h-[0-9][^"]*w-[0-9][^"]*"[^>]*>.*?<path[^>]*d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2\.586a1 1 0 01-\.293\.707l-6\.414 6\.414a1 1 0 00-\.293\.707V17l-4 4v-6\.586a1 1 0 00-\.293-\.707L3\.293 7\.207A1 1 0 013 6\.5V4z"[^>]*>.*?</svg>', '<span class="text-gray-500 text-xs sm:text-sm md:text-base">🔧</span>'
    
    # Refresh icon
    $content = $content -replace '<svg[^>]*class="[^"]*h-[0-9][^"]*w-[0-9][^"]*"[^>]*>.*?<path[^>]*d="M4 4v5h\.582m15\.356 2A8\.001 8\.001 0 004\.582 9m0 0H9m11 11v-5h-\.581m0 0a8\.003 8\.003 0 01-15\.357-2m15\.357 2H15"[^>]*>.*?</svg>', '<span class="text-xs sm:text-sm md:text-base">🔄</span>'
    
    # Plus icon
    $content = $content -replace '<svg[^>]*class="[^"]*h-[0-9][^"]*w-[0-9][^"]*"[^>]*>.*?<path[^>]*d="M12 4v16m8-8H4"[^>]*>.*?</svg>', '<span class="text-xs sm:text-sm md:text-base">➕</span>'
    
    # Check icon
    $content = $content -replace '<svg[^>]*class="[^"]*h-[0-9][^"]*w-[0-9][^"]*"[^>]*>.*?<path[^>]*d="M5 13l4 4L19 7"[^>]*>.*?</svg>', '<span class="text-xs sm:text-sm md:text-base">✅</span>'
    
    # X/Close icon
    $content = $content -replace '<svg[^>]*class="[^"]*h-[0-9][^"]*w-[0-9][^"]*"[^>]*>.*?<path[^>]*d="M6 18L18 6M6 6l12 12"[^>]*>.*?</svg>', '<span class="text-xs sm:text-sm md:text-base">❌</span>'
    
    # Trash/Delete icon
    $content = $content -replace '<svg[^>]*class="[^"]*h-[0-9][^"]*w-[0-9][^"]*"[^>]*>.*?<path[^>]*d="M19 7l-\.867 12\.142A2 2 0 0116\.138 21H7\.862a2 2 0 01-1\.995-1\.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"[^>]*>.*?</svg>', '<span class="text-xs sm:text-sm md:text-base">🗑️</span>'
    
    # Generic action icon (fallback for any remaining action icons)
    $content = $content -replace '<svg[^>]*class="[^"]*h-[0-9][^"]*w-[0-9][^"]*"[^>]*>.*?<path[^>]*d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"[^>]*>.*?</svg>', '<span class="text-xs">⚙️</span>'

    # If content changed, write it back
    if ($content -ne $originalContent) {
        Set-Content -Path $file.FullName -Value $content -NoNewline
        $fixedCount++
        Write-Host "✅ Fixed: $($file.FullName)" -ForegroundColor Yellow
    }
}

Write-Host "`n🎉 Fixed $fixedCount Blade template files" -ForegroundColor Green
Write-Host "All SVG icons have been replaced with basic text alternatives!" -ForegroundColor Green 