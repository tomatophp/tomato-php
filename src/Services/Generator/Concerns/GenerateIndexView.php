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
            $this->moduleName ? module_path($this->moduleName) . "/Resources/views/".str_replace('_', '-', $this->tableName)."/index.blade.php" : resource_path("views/admin/{$this->tableName}/index.blade.php"),
            [
                "bool" => $this->getBool(),
                "title" => $this->modelName,
                "table" => str_replace('_', '-', $this->tableName),
                "modelClass" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\Entities\\".$this->modelName : "\\App\\Models\\".$this->modelName,
            ],
            $folders
        );
    }

    /**
     * @return string
     */
    private function getBool(): string
    {
        $bool = "";
        foreach ($this->cols as $field){
            if($field['type'] == 'boolean'){
                $bool .= '<x-splade-cell '. $field['name'] .'>'.PHP_EOL;
                $bool .= '    <h3 class="text-lg">'.PHP_EOL;
                $bool .= '      @if($item->'.$field['name'].')'.PHP_EOL;
                $bool .= '        <x-heroicon-s-check-circle class="text-green-500 w-8 h-8"/>'.PHP_EOL;
                $bool .= '      @else'.PHP_EOL;
                $bool .= '        <x-heroicon-s-x-circle class="text-red-500 w-8 h-8"/>'.PHP_EOL;
                $bool .= '      @endif'.PHP_EOL;
                $bool .= '    </h3>'.PHP_EOL;
                $bool .= '</x-splade-cell>'.PHP_EOL;
            }
        }
        return $bool;
    }
}
