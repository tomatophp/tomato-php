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
                "modulePath" => $this->moduleName ? Str::replace('_', '-', Str::snake($this->moduleName))."::" : "admin."
            ],
            [
                $this->moduleName ? module_path($this->moduleName) ."/Http/Controllers/" : app_path("Http/Controllers/Admin")
            ]
        );
    }
}
