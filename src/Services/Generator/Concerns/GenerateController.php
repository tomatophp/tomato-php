<?php

namespace TomatoPHP\TomatoPHP\Services\Generator\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait GenerateController
{
    private function generateController(bool $isForce = false)
    {
        $filePath = $this->moduleName ? module_path($this->moduleName) ."/Http/Controllers/{$this->modelName}Controller.php" : app_path("Http/Controllers/Admin/{$this->modelName}Controller.php");
        if($isForce){
            if(File::exists($filePath)){
                File::delete($filePath);
            }
        }
        $this->generateStubs(
            $this->stubPath . "controller.stub",
            $this->moduleName ? module_path($this->moduleName) ."/Http/Controllers/{$this->modelName}Controller.php" : app_path("Http/Controllers/Admin/{$this->modelName}Controller.php"),
            [
                "name" => "{$this->modelName}Controller",
                "model" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\Entities\\".$this->modelName : "\\App\\Models\\".$this->modelName,
                "title" => $this->modelName,
                "table" => str_replace('_', '-', $this->tableName),
                "validation" => $this->generateRules(),
                "validationEdit" => $this->generateRules(true),
                "requestNamespace" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\Http\\Requests\\{$this->modelName}\\" : "\\App\\Http\\Requests\\Admin\\{$this->modelName}\\",
                "tableClass" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\Tables\\".$this->modelName."Table" : "\\App\\Tables\\".$this->modelName."Table",
                "namespace" => $this->moduleName ? "Modules\\".$this->moduleName."\\Http\\Controllers": "App\\Http\\Controllers\\Admin",
                "modulePath" => $this->moduleName ? Str::replace('_', '-', Str::lower($this->moduleName))."::" : "admin."
            ],
            [
                $this->moduleName ? module_path($this->moduleName) ."/Http/Controllers/" : app_path("Http/Controllers/Admin")
            ]
        );

        \Laravel\Prompts\info("Controller Generate Success");
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
                "validation" => $this->generateRules(),
                "validationEdit" => $this->generateRules(true),
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

        \Laravel\Prompts\info("Controller Generate Success");
    }


    private function generateRules(bool $edit = false): string
    {
        $rules = "";
        foreach ($this->cols as $key => $item) {
            if ($item['name'] !== 'id') {
                if($key !== 0){
                    $rules .= "            ";
                }
                $rules .= "'{$item['name']}' => ";
                $rules .= "'";
                if($item['required'] === 'required'){
                    if($edit){
                        $rules .= 'sometimes';
                    }
                    else {
                        $rules .= 'required';
                    }

                }
                else {
                    $rules .= 'nullable';
                }

                if($item['maxLength']){
                    $rules .= '|max:'.$item['maxLength'];
                }
                if($item['type'] === 'string' || $item['type'] === 'email' || $item['type'] === 'phone'){
                    $rules .= '|string';
                }
                if($item['type'] === 'email'){
                    $rules .= '|email';
                }
                if($item['type'] === 'tel'){
                    $rules .= '|min:12';
                }
                if($item['type'] === 'password'){
                    $rules .= '|confirmed|min:6';
                }
                if($item['type'] === 'relation'){
                    $rules .= '|exists:'.$item['relation']['table'].',id';
                }

                $rules .= "'";
                if (($key !== count($this->cols) - 1)) {
                    $rules .= ",".PHP_EOL;
                }
            }
        }

        return $rules;
    }
}
