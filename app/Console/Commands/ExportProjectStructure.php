<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportProjectStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'code:export 
                            {--path= : Specific folder to export (relative to root)} 
                            {--output=project_export.txt : Output file name} 
                            {--exclude= : Comma-separated custom paths to exclude}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export project structure and files text for LLM/AI context';

    /**
     * Default directories to ignore
     */
    protected $defaultExcludes = [
        'vendor', 'node_modules', '.git', 'storage', 'bootstrap/cache', 
        'public', '.phpunit.cache', '.idea', '.vscode'
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetPath = $this->option('path') ? base_path($this->option('path')) : base_path();
        $outputFile = base_path($this->option('output'));
        
        $customExcludes = $this->option('exclude') ? explode(',', $this->option('exclude')) : [];
        $excludes = array_merge($this->defaultExcludes, $customExcludes);

        if (!File::exists($targetPath)) {
            $this->error("Target path does not exist: {$targetPath}");
            return 1;
        }

        $this->info("Scanning directory structure...");
        File::put($outputFile, "=== PROJECT STRUCTURE ===\n\n");

        $this->exportDirectory($targetPath, $excludes, $outputFile);

        $this->info("Export completed successfully! Saved to: " . basename($outputFile));
        return 0;
    }

    /**
     * Recursively scan and append file data
     */
    protected function exportDirectory($dir, $excludes, $outputFile)
    {
        $files = File::allFiles($dir);

        foreach ($files as $file) {
            $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file->getRealPath());
            
            // Check if file is in excluded paths
            $shouldExclude = false;
            foreach ($excludes as $exclude) {
                if (strpos($relativePath, trim($exclude)) === 0) {
                    $shouldExclude = true;
                    break;
                }
            }

            if ($shouldExclude) {
                continue;
            }

            // Skip binary/large non-code files safely
            $extension = $file->getExtension();
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'ico', 'pdf', 'zip', 'tar', 'gz', 'sql'])) {
                continue;
            }

            $this->line("Processing: " . $relativePath);

            // Append header for individual file
            File::append($outputFile, "\n\n--- FILE: {$relativePath} ---\n\n");
            File::append($outputFile, File::get($file->getRealPath()));
        }
    }
}