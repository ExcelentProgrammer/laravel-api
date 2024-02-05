<?php

namespace App\Console\Commands;

use Gettext\Loader\PoLoader;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Process\Process;

class Gettext extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gettext:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scanner gettext';

    function rglob($pattern, $flags = 0): bool|array
    {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge(
                [],
                ...[$files, $this->rglob($dir . "/" . basename($pattern), $flags)]
            );
        }
        return $files;
    }

    /**
     * Execute the console command.
     */
    #[NoReturn] public function handle(): void
    {
        Artisan::call("view:cache");
        $this->info("View files cached");

        $paths = [
            "storage/framework/views",
            "app",
            "config",
            "Modules",
            "resources/views"
        ]; // Scanner path


        $files = []; // All files

        foreach ($paths as $path) {
            $fs = $this->rglob($path . "**/*.php");
            $files = array_merge($files, $fs);
        }
        $command = new Process(['xgettext', "--keyword=__", "-o", "lang/messages.po", ...$files]);
        $command->run();
        $loader = new PoLoader();
        $response = $loader->loadFile("lang/messages.po")->toArray()['translations'];
        $messages = [];
        $languages = Config::get("app.locales", ['uz', "en", "ru"]);
        foreach ($languages as $language) {
            $this->warn("==============\nStart: $language");
            $old = json_decode(File::get(base_path("lang/gettext/$language.json")), true);

            foreach ($response as $message) {
                $messages[$message['original']] = $old[$message['original']] ?? "";
            }
            File::put(base_path("lang/gettext/$language.json"), json_encode($messages, JSON_PRETTY_PRINT));
            $this->info("Success: $language\n==============\n");
        }
        Artisan::call("view:clear");
        $this->info("Cache clear");
    }

}
