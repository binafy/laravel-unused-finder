<?php

namespace Binafy\LaravelUnusedFinder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use function Laravel\Prompts\text;

class FindUnusedClasses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'find-unused:classes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find unused classes';

    /**
     * The directory path.
     *
     * @var string
     */
    protected string $path;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (! $this->option('path')) {
            $this->path = text('Write the directory path:', default: 'app', required: true);
        }

        collect($this->path)->each(function ($path) {
            $phpFiles = collect(File::allFiles($path))
                ->filter(fn ($filename) => Str::endsWith($filename, '.php'))
                ->each(function ($phpFile) {
                    $fileContents = file_get_contents($phpFile);
                    dd($fileContents);
                    if (preg_match('/class\s+(\w+)/', $fileContents, $className) === 1) {
                        $this->classNames[$className[1]] = $phpFile->getPathName();
                        $fileContents = str_replace($className[1], Str::random(16), $fileContents);
                    }
                    $this->massiveString .= $fileContents;
                });
            });
    }
}
