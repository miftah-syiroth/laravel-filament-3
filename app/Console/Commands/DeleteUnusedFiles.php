<?php

namespace App\Console\Commands;

use App\Settings\WebsiteSettings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteUnusedFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:unused-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unused files from storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logo = app(WebsiteSettings::class)->logo;
        $favicon = app(WebsiteSettings::class)->favicon;

        $usedFiles = collect([$logo, $favicon])
            ->filter() // hapus null dan ''
            ->map(fn($file) => basename($file))
            ->toArray();

        collect(Storage::disk('public')->allFiles(WebsiteSettings::PATH))
            ->reject(fn(string $file) => basename($file) === '.gitignore')
            ->reject(fn(string $file) => in_array(basename($file), $usedFiles))
            ->each(function ($file) {
                Storage::disk('public')->delete($file);
                $this->line("Deleted: $file");
            });

        $this->info('Unused files deleted successfully');
    }
}
