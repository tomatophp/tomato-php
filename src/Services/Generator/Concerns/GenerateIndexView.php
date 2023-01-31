<?php

namespace TomatoPHP\TomatoPHP\Services\Generator\Concerns;

trait GenerateIndexView
{
    private function generateIndexView(): void
    {
        $folders = [];
        if($this->moduleName){
            $folders[] = module_path($this->moduleName) . "/Resources/views/{$this->tableName}";
        }
        else {
            $folders[] = resource_path("views/{$this->tableName}");
        }
        $this->generateStubs(
            $this->stubPath . "index.stub",
            $this->moduleName ? module_path($this->moduleName) . "/Resources/views/{$this->tableName}/index.blade.php" : resource_path("views/admin/{$this->tableName}/index.blade.php"),
            [
                "title" => $this->modelName,
                "table" => str_replace('_', '-', $this->tableName),
                "modelClass" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\Entities\\".$this->modelName : "\\App\\Models\\".$this->modelName,
            ],
            $folders
        );
    }
}
