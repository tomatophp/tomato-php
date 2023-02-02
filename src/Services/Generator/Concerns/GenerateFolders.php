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
                module_path($this->moduleName) . "/Resources/views/{$this->tableName}",
                module_path($this->moduleName) . "/Menus",
                module_path($this->moduleName) ."/Http/Requests",
                module_path($this->moduleName) . "/Routes",
                module_path($this->moduleName)."/Tables"
            ];
        }
        else {
            $folders = [
                app_path("Http/Controllers") . "/Admin",
                resource_path("views") . '/admin',
                resource_path("views") . '/admin/' . $this->tableName,
                app_path("Menus"),
                app_path("Http/Requests") . "/Admin",
                app_path("Http/Requests/Admin") . '/'.$this->modelName,
                base_path("routes"),
                app_path("Tables")
            ];
        }

        foreach($folders as $folder){
            if(!File::exists($folder)){
                File::makeDirectory($folder);
            }
        }
    }
}
