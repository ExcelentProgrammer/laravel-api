<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name} {model} {resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate service command';

    public function handle(): void
    {
        $name = $this->argument('name');

        $model = $this->argument('model');
        $resource = $this->argument('resource');

        $className = Str::studly($name) . 'Service';
        $path = app_path("Services/{$className}.php");

        if (File::exists($path)) {
            $this->error('Service already exists!');
            return;
        }

        File::put($path, $this->generateServiceClass($className, $model, $resource));

        $this->info("Service {$className} created successfully!");
    }

    /**
     * Generate service
     *
     * @param $className
     * @return array|bool|string
     */
    protected function generateServiceClass($className, $model, $resource): array|bool|string
    {
        $stub = file_get_contents(base_path('stubs/service.stub'));
        return str_replace("{{resource}}", $resource, str_replace("{{model}}", $model, str_replace('{{class}}', $className, $stub)));
    }
}
