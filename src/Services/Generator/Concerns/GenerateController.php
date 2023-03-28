<?php

namespace TomatoPHP\TomatoPHP\Services\Generator\Concerns;

use Illuminate\Support\Str;

trait GenerateController
{
    private function generateController()
    {
        $this->generateStubs(
            $this->stubPath . "controller.stub",
            $this->moduleName ? module_path($this->moduleName) ."/Http/Controllers/{$this->modelName}Controller.php" : app_path("Http/Controllers/Admin/{$this->modelName}Controller.php"),
            [
                "name" => "{$this->modelName}Controller",
                "model" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\Entities\\".$this->modelName : "\\App\\Models\\".$this->modelName,
                "title" => $this->modelName,
                "table" => str_replace('_', '-', $this->tableName),
                "requestNamespace" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\Http\\Requests\\{$this->modelName}\\" : "\\App\\Http\\Requests\\Admin\\{$this->modelName}\\",
                "tableClass" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\Tables\\".$this->modelName."Table" : "\\App\\Tables\\".$this->modelName."Table",
                "namespace" => $this->moduleName ? "Modules\\".$this->moduleName."\\Http\\Controllers": "App\\Http\\Controllers\\Admin",
                "modulePath" => $this->moduleName ? Str::replace('_', '-', Str::lower($this->moduleName))."::" : "admin."
            ],
            [
                $this->moduleName ? module_path($this->moduleName) ."/Http/Controllers/" : app_path("Http/Controllers/Admin")
            ]
        );
    }

    private function generateControllerForBuilder()
    {
        $this->generateStubs(
             "vendor/tomatophp/tomato-php/stubs/FormBuilder/BuilderController.stub",
            $this->moduleName ? module_path($this->moduleName) ."/Http/Controllers/{$this->modelName}Controller.php" : app_path("Http/Controllers/Admin/{$this->modelName}Controller.php"),
            [
                "name" => "{$this->modelName}Controller",
                "model" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\Entities\\".$this->modelName : "\\App\\Models\\".$this->modelName,
                "title" => $this->modelName,
                "table" => str_replace('_', '-', $this->tableName),
                "requestNamespace" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\Http\\Requests\\{$this->modelName}\\" : "\\App\\Http\\Requests\\Admin\\{$this->modelName}\\",
                "FormNamespace" => $this->moduleName ? "Modules\\".$this->moduleName."\\Forms\\{$this->modelName}Form" : "App\\Forms\\{$this->modelName}Form",
                "tableClass" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\Tables\\".$this->modelName."Table" : "\\App\\Tables\\".$this->modelName."Table",
                "namespace" => $this->moduleName ? "Modules\\".$this->moduleName."\\Http\\Controllers": "App\\Http\\Controllers\\Admin",
                "modulePath" => $this->moduleName ? Str::replace('_', '-', Str::lower($this->moduleName))."::" : "admin."
            ],
            [
                $this->moduleName ? module_path($this->moduleName) ."/Http/Controllers/" : app_path("Http/Controllers/Admin")
            ]
        );
    }
}
