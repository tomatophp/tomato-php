<?php

namespace TomatoPHP\TomatoPHP\Services\Generator\Concerns;

use Illuminate\Support\Str;

trait GenerateTable
{
    private function generateTable(): void
    {
        $this->generateStubs(
            $this->stubPath . "table.stub",
            $this->moduleName ? module_path($this->moduleName). "/Tables/{$this->modelName}Table.php" : app_path("Tables/{$this->modelName}Table.php"),
            [
                "name" => "{$this->modelName}Table",
                "title" => $this->modelName,
                "model" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\Entities\\".$this->modelName :"\\App\\Models\\".$this->modelName,
                "searchable" => $this->generateSearchable(),
                "cols" => $this->generateCols(),
                "namespace" => $this->moduleName ? "Modules\\".$this->moduleName."\\Tables" : "App\\Tables",
            ],
            [
                $this->moduleName ? module_path($this->moduleName)."/Tables" : ("Tables")
            ]
        );
    }

    private function generateSearchable(): string
    {
        $searchable = "";
        foreach($this->cols as $key=>$item){
            if($item['unique']){
                $searchable .= "'{$item['name']}',";
            }
            else if($item['name'] === 'id'){
                $searchable .= "'{$item['name']}',";
            }
            else if($item['name'] === 'name'){
                $searchable .= "'{$item['name']}',";
            }
            else if($item['name'] === 'phone'){
                $searchable .= "'{$item['name']}',";
            }
            else if($item['name'] === 'email'){
                $searchable .= "'{$item['name']}',";
            }
        }

        return $searchable;
    }

    private function generateCols(): string
    {
        $cols = "";
        foreach($this->cols as $key=>$item){
            if($item['name'] !== 'password'){
                if($key!== 0){
                    $cols .= "            ";
                }
                $cols .= "->column(label: '".Str::ucfirst($item['name'])."', sortable: true)";
                if($key!== count($this->cols)-1){
                    $cols .= PHP_EOL;
                }
            }
        }
        return $cols;
    }
}
