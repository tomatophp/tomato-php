<?php

namespace TomatoPHP\TomatoPHP\Services\Generator\Concerns;

trait GenerateCreateView
{
    private function generateCreateView(): void
    {
        $folders = [];
        if($this->moduleName){
            $folders[] = module_path($this->moduleName) . "/Resources/views/{$this->tableName}";
        }
        else {
            $folders[] = resource_path("views/{$this->tableName}");
        }

        $this->generateStubs(
            $this->stubPath . "create.stub",
            $this->moduleName ? module_path($this->moduleName) . "/Resources/views/{$this->tableName}/create.blade.php" : resource_path("views/admin/{$this->tableName}/create.blade.php"),
            [
                "title" => $this->modelName,
                "table" => str_replace('_', '-', $this->tableName),
                "cols" => $this->generateForm()
            ],
            $folders
        );
    }
}
