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
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate service command';

    public function handle(): void
    {


        $module = null;
        $name = $this->argument('name');


        while (true) {
            $type = $this->ask("module or base m/B: ") ?? "b";
            if ($type == "m" or $type == "b") {
                break;
            }
        }
        if ($type == "m") {
            while (true) {
                $module = $this->ask("Enter Module name: ") ?? null;
                if ($module != null and file_exists("Modules/" . $module)) {
                    break;
                }
                $this->error("Module not found");
            }
        }

        $model = $this->ask("Enter Model name: ") ?? "BaseModel";
        $resource = $this->ask("Enter resource name: ") ?? "BaseResource";

        $className = Str::studly($name) . 'Service';

        if ($type == "m") {
            if (!file_exists("Modules/$module/App/Services")) {
                mkdir("Modules/$module/App/Services");
            }
            $path = base_path("Modules/$module/App/Services/{$className}.php");
        } else {
            $path = app_path("Services/{$className}.php");
        }

        if (File::exists($path)) {
            $this->error('Service already exists!');
            return;
        }

        File::put($path, $this->generateServiceClass($className, $model, $resource, $module, $type));

        $this->info("Service {$className} created successfully!");
    }

    /**
     * Generate service
     *
     * @param $className
     * @param $model
     * @param $resource
     * @param $module
     * @param $type
     * @return array|bool|string
     */
    protected function generateServiceClass($className, $model, $resource, $module, $type): array|bool|string
    {
        $path = match ($type) {
            "b" => 'stubs/service.stub',
            "m" => 'stubs/module-service.stub'
        };
        $stub = file_get_contents(base_path($path));
        return str_replace("{{module}}", $module, str_replace("{{resource}}", $resource, str_replace("{{model}}", $model, str_replace('{{class}}', $className, $stub))));
    }
}
