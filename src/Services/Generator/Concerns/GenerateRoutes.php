<?php

namespace TomatoPHP\TomatoPHP\Services\Generator\Concerns;

trait GenerateRoutes
{
    private function generateRoutes()
    {
        $this->generateStubs(
            $this->stubPath . "route.stub",
            $this->moduleName ? module_path($this->moduleName) . "/Routes/web.php" : base_path("routes/web.php"),
            [
                "name" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\Http\\Controllers\\{$this->modelName}Controller" : "App\\Http\\Controllers\\Admin\\{$this->modelName}Controller",
                "table" => str_replace('_', '-', $this->tableName)
            ],
            [
                $this->moduleName ? module_path($this->moduleName) . "/Routes" : base_path("routes")
            ],
            true
        );
    }
}
