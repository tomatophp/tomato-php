<?php

namespace TomatoPHP\TomatoPHP\Services\Generator\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait GenerateFolders
{
    private function generateFolders(): void
    {
        if($this->moduleName){
            $folders = [
                module_path($this->moduleName) ."/Http/Controllers/",
                module_path($this->moduleName) . "/Resources/views/" . str_replace('_', '-', $this->tableName),
                module_path($this->moduleName) . "/Routes",
                module_path($this->moduleName)."/Tables"
            ];
        }
        else {
            $folders = [
                app_path("Http/Controllers") . "/Admin",
                resource_path("views") . '/admin',
                resource_path("views") . '/admin/' . str_replace('_', '-', $this->tableName),
                base_path("routes"),
                app_path("Tables")
            ];
        }

        foreach($folders as $folder){
            if(!File::exists($folder)){
                File::makeDirectory($folder);
            }
        }

        \Laravel\Prompts\info("Folders Generate Success");
    }
}
