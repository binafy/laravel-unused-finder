<?php

namespace Binafy\LaravelUnusedFinder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use function Laravel\Prompts\text;
use function Laravel\Prompts\table;

class FindUnusedMethods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'find-unused:classes {path?}';

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
     * The all class names.
     *
     * @var array
     */
    protected array $classNames = [];

    /**
     * The controller names.
     *
     * @var array
     */
    protected array $controllerNames = [];

    /**
     * The file content.
     *
     * @var string
     */
    protected string $massiveString = '';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (! $this->argument('path')) {
            $this->path = text('Write the directory path:', default: 'app', required: true);
        }
        if (! File::isDirectory($this->path)) {
            $this->error('The directory ' . $this->path . ' does not exist.');
            return;
        }

        $exceptClasses = config('laravel-unused-finder.classes.excluded', []);

        collect(base_path($this->path))
            ->each(function ($path) use ($exceptClasses) {
                collect(File::allFiles($path))
                    ->filter(function ($filename) use ($exceptClasses) {
                        foreach ($exceptClasses as $exceptClass) {
                            if ($filename->getRealPath() === base_path($exceptClass)) {
                                continue;
                            }

                            return Str::endsWith($filename, '.php');
                        }
                    })
                    ->each(function (\Symfony\Component\Finder\SplFileInfo $file) {
                        $fileContents = file_get_contents($file);

                        if (preg_match('/class\s+(\w+)/', $fileContents, $className) === 1) {
                            $this->classNames[$className[1]] = $file->getPathName();
                            $fileContents = str_replace($className[1], Str::random(), $fileContents);
                        }

                        $this->massiveString .= $fileContents;
                    });
                });

        foreach ($this->classNames as $className => $files) {
            if (preg_match("/$className/", $this->massiveString) === 1
                || in_array($className, $this->controllerNames)
            ) {
                unset($this->classNames[$className]);
            }
        }

        $arrayOfArrays = collect($this->classNames)->map(function ($value, $key) {
            return [
                'key' => $key,
                'value' => $value,
            ];
        })->values()->toArray();

        table(['Class Name', 'Class Path'], $arrayOfArrays);
    }
}
