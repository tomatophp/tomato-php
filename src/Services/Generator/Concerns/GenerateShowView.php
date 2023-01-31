<?php

namespace TomatoPHP\TomatoPHP\Services\Generator\Concerns;

trait GenerateShowView
{
    private function generateShowView(): void
    {
        $folders = [];
        if($this->moduleName){
            $folders[] = module_path($this->moduleName) . "/Resources/views/{$this->tableName}";
        }
        else {
            $folders[] = resource_path("views/{$this->tableName}");
        }

        $this->generateStubs(
            $this->stubPath . "view.stub",
            $this->moduleName ? module_path($this->moduleName) . "/Resources/views/{$this->tableName}/show.blade.php" : resource_path("views/admin/{$this->tableName}/show.blade.php"),
            [
                "title" => $this->modelName,
                "table" => str_replace('_', '-', $this->tableName),
                "cols" => $this->generateForm(true)
            ],
            $folders
        );
    }
}
