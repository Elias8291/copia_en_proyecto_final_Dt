<?php

/**
 * SVG Template Fixer
 * Automatically fixes malformed SVG paths in Blade templates
 */

class SVGTemplateFixer {
    private $searchPatterns = [
        // Fix the specific malformed search icon path
        '/d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/' => 'd="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0 1 14 0z"',
        // Fix other common malformed arc patterns
        '/d="([^"]*a[^"]*7[^"]*7[^"]*0[^"]*)([01])([01])([0-9]+[^"]*)"/' => 'd="$1$2 $3 $4"',
        // Fix arc commands with missing spaces
        '/a([^"]*)\s+([0-9]+)\s+([0-9]+)\s+([0-9]+)\s+([0-9]+)\s+([01])([01])([0-9]+)/' => 'a$1 $2 $3 $4 $5 $6 $7 $8'
    ];

    private $bladeExtensions = ['.blade.php'];
    private $excludeDirs = ['vendor', 'node_modules', '.git', 'storage'];

    /**
     * Fix SVG paths in all Blade templates
     */
    public function fixAllTemplates($directory = null) {
        if (!$directory) {
            $directory = __DIR__ . '/resources/views';
        }

        if (!is_dir($directory)) {
            echo "Directory not found: $directory\n";
            return false;
        }

        $files = $this->findBladeFiles($directory);
        $fixedCount = 0;

        foreach ($files as $file) {
            if ($this->fixTemplate($file)) {
                $fixedCount++;
                echo "✅ Fixed: $file\n";
            }
        }

        echo "\n🎉 Fixed $fixedCount Blade template files\n";
        return $fixedCount;
    }

    /**
     * Find all Blade template files
     */
    private function findBladeFiles($directory) {
        $files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            $path = $file->getPathname();
            
            // Skip excluded directories
            $shouldSkip = false;
            foreach ($this->excludeDirs as $excludeDir) {
                if (strpos($path, $excludeDir) !== false) {
                    $shouldSkip = true;
                    break;
                }
            }
            
            if ($shouldSkip) continue;

            // Check if it's a Blade file
            foreach ($this->bladeExtensions as $ext) {
                if (str_ends_with($path, $ext)) {
                    $files[] = $path;
                    break;
                }
            }
        }

        return $files;
    }

    /**
     * Fix SVG paths in a single template file
     */
    private function fixTemplate($filePath) {
        if (!file_exists($filePath)) {
            return false;
        }

        $content = file_get_contents($filePath);
        $originalContent = $content;
        $hasChanges = false;

        // Apply all search patterns
        foreach ($this->searchPatterns as $pattern => $replacement) {
            $newContent = preg_replace($pattern, $replacement, $content);
            if ($newContent !== $content) {
                $content = $newContent;
                $hasChanges = true;
            }
        }

        // Additional fixes for specific patterns
        $content = $this->fixSpecificPatterns($content);

        // Write back if changes were made
        if ($hasChanges && $content !== $originalContent) {
            file_put_contents($filePath, $content);
            return true;
        }

        return false;
    }

    /**
     * Fix specific SVG patterns
     */
    private function fixSpecificPatterns($content) {
        // Fix the search icon pattern specifically
        $content = str_replace(
            'd="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"',
            'd="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0 1 14 0z"',
            $content
        );

        // Fix other common malformed patterns
        $content = preg_replace(
            '/d="([^"]*a[^"]*7[^"]*7[^"]*0[^"]*)([01])([01])([0-9]+[^"]*)"/',
            'd="$1$2 $3 $4"',
            $content
        );

        return $content;
    }

    /**
     * Preview changes without making them
     */
    public function previewChanges($directory = null) {
        if (!$directory) {
            $directory = __DIR__ . '/resources/views';
        }

        $files = $this->findBladeFiles($directory);
        $changes = [];

        foreach ($files as $file) {
            $fileChanges = $this->previewFileChanges($file);
            if (!empty($fileChanges)) {
                $changes[$file] = $fileChanges;
            }
        }

        return $changes;
    }

    /**
     * Preview changes for a single file
     */
    private function previewFileChanges($filePath) {
        if (!file_exists($filePath)) {
            return [];
        }

        $content = file_get_contents($filePath);
        $changes = [];

        // Check for the specific problematic pattern
        if (strpos($content, 'd="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"') !== false) {
            $changes[] = [
                'type' => 'search_icon_fix',
                'description' => 'Fix malformed search icon SVG path',
                'lines' => $this->findLinesWithPattern($content, 'd="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"')
            ];
        }

        return $changes;
    }

    /**
     * Find line numbers containing a pattern
     */
    private function findLinesWithPattern($content, $pattern) {
        $lines = explode("\n", $content);
        $lineNumbers = [];

        foreach ($lines as $index => $line) {
            if (strpos($line, $pattern) !== false) {
                $lineNumbers[] = $index + 1;
            }
        }

        return $lineNumbers;
    }
}

// CLI usage
if (php_sapi_name() === 'cli') {
    $fixer = new SVGTemplateFixer();
    
    if (isset($argv[1]) && $argv[1] === 'preview') {
        echo "Previewing SVG fixes...\n";
        $changes = $fixer->previewChanges();
        
        if (empty($changes)) {
            echo "No SVG issues found.\n";
        } else {
            foreach ($changes as $file => $fileChanges) {
                echo "\nFile: $file\n";
                foreach ($fileChanges as $change) {
                    echo "  - {$change['description']} (lines: " . implode(', ', $change['lines']) . ")\n";
                }
            }
        }
    } else {
        echo "Fixing SVG paths in Blade templates...\n";
        $fixer->fixAllTemplates();
    }
}

// For web usage, you can call:
// $fixer = new SVGTemplateFixer();
// $fixer->fixAllTemplates(); 