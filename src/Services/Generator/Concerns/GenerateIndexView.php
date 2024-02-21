<?php

namespace TomatoPHP\TomatoPHP\Services\Generator\Concerns;

use Illuminate\Support\Str;

trait GenerateIndexView
{
    private function generateIndexView(): void
    {
        $folders = [];
        if($this->moduleName){
            $folders[] = module_path($this->moduleName) . "/resources/views/" . Str::replace('_', '-',$this->tableName);
        }
        else {
            $folders[] = resource_path("views/admin");
            $folders[] = resource_path("views/admin/" . Str::replace('_', '-',$this->tableName));
        }
        $this->generateStubs(
            $this->stubPath . "index.stub",
            $this->moduleName ? module_path($this->moduleName) . "/resources/views/".str_replace('_', '-', $this->tableName)."/index.blade.php" : resource_path("views/admin/".Str::replace('_', '-',$this->tableName)."/index.blade.php"),
            [
                "cells" => $this->getCells(),
                "title" => $this->modelName,
                "table" => str_replace('_', '-', $this->tableName),
                "modelClass" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\App\\Models\\".$this->modelName : "\\App\\Models\\".$this->modelName,
            ],
            $folders
        );
    }

    /**
     * @return string
     */
    private function getCells(): string
    {
        $cell = "";
        foreach ($this->cols as $field){
            if($field['type'] == 'longText'){
                $cell .= '<x-splade-cell '. $field['name'] .'>'.PHP_EOL;
                $cell .= '    <x-tomato-admin-row table :value="$item->'.$field['name'].'" />'.PHP_EOL;
                $cell .= '</x-splade-cell>'.PHP_EOL;
            }
            if($field['type'] == 'boolean'){
                $cell .= '<x-splade-cell '. $field['name'] .'>'.PHP_EOL;
                $cell .= '    <x-tomato-admin-row table type="bool" :value="$item->'.$field['name'].'" />'.PHP_EOL;
                $cell .= '</x-splade-cell>'.PHP_EOL;
            }
            if($field['type'] == 'email'){
                $cell .= '<x-splade-cell '. $field['name'] .'>'.PHP_EOL;
                $cell .= '    <x-tomato-admin-row table type="email" :value="$item->'.$field['name'].'" />'.PHP_EOL;
                $cell .= '</x-splade-cell>'.PHP_EOL;
            }
            if($field['type'] == 'tel'){
                $cell .= '<x-splade-cell '. $field['name'] .'>'.PHP_EOL;
                $cell .= '    <x-tomato-admin-row table type="tel" :value="$item->'.$field['name'].'" />'.PHP_EOL;
                $cell .= '</x-splade-cell>'.PHP_EOL;
            }
            if($field['type'] == 'int'){
                $cell .= '<x-splade-cell '. $field['name'] .'>'.PHP_EOL;
                $cell .= '    <x-tomato-admin-row table type="number" :value="$item->'.$field['name'].'" />'.PHP_EOL;
                $cell .= '</x-splade-cell>'.PHP_EOL;
            }
            if($field['name'] == 'color'){
                $cell .= '<x-splade-cell '. $field['name'] .'>'.PHP_EOL;
                $cell .= '    <x-tomato-admin-row table type="color" :value="$item->'.$field['name'].'" />'.PHP_EOL;
                $cell .= '</x-splade-cell>'.PHP_EOL;
            }
            if($field['name'] == 'icon'){
                $cell .= '<x-splade-cell '. $field['name'] .'>'.PHP_EOL;
                $cell .= '    <x-tomato-admin-row table type="icon" :value="$item->'.$field['name'].'" />'.PHP_EOL;
                $cell .= '</x-splade-cell>'.PHP_EOL;
            }
        }
        return $cell;
    }
}
