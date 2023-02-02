<?php

namespace TomatoPHP\TomatoPHP\Services\Generator\Concerns;

use Illuminate\Support\Facades\Artisan;

trait GenerateMenu
{
    public function generateMenu()
    {
        $this->generateStubs(
            $this->stubPath . "menu.stub",
            $this->moduleName ? module_path($this->moduleName) . "/Menus/{$this->modelName}Menu.php" : app_path("Menus/{$this->modelName}Menu.php"),
            [
                "group" => $this->moduleName ? $this->moduleName : $this->modelName,
                "name" => $this->modelName,
                "table" => ucfirst(str_replace('_', ' ', $this->tableName)),
                "index" => "admin.". str_replace('_', '-', $this->tableName) . ".index",
                "namespace" =>  $this->moduleName ? "Modules\\".$this->moduleName. "\\Menus" : "App\\Menus"
            ],
            [
                $this->moduleName ? module_path($this->moduleName) . "/Menus" : app_path("Menus")
            ]
        );
    }
}
