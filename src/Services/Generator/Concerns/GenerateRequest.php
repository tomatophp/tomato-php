<?php

namespace TomatoPHP\TomatoPHP\Services\Generator\Concerns;

trait GenerateRequest
{
    private function generateRequest(): void
    {
        $this->generateStubs(
            $this->stubPath . "request.stub",
            $this->moduleName ? module_path($this->moduleName) ."/Http/Requests/{$this->modelName}/{$this->modelName}StoreRequest.php" : app_path("Http/Requests/Admin/{$this->modelName}/{$this->modelName}StoreRequest.php"),
            [
                "name" => "{$this->modelName}StoreRequest",
                "model" => $this->modelName,
                "validation" => $this->generateRules(),
                "namespace" => $this->moduleName ? "Modules\\{$this->moduleName}\\Http\\Requests\\{$this->modelName}" : "App\\Http\\Requests\\Admin\\{$this->modelName}",
            ],
            [
                $this->moduleName ? module_path($this->moduleName) ."/Http/Requests": app_path("Http/Requests/Admin"),
                $this->moduleName ? module_path($this->moduleName) ."/Http/Requests/{$this->modelName}": app_path("Http/Requests/Admin/{$this->modelName}"),
            ]
        );

        $this->generateStubs(
            $this->stubPath . "request.stub",
            $this->moduleName ? module_path($this->moduleName)."/Http/Requests/{$this->modelName}/{$this->modelName}UpdateRequest.php" : app_path("Http/Requests/Admin/{$this->modelName}/{$this->modelName}UpdateRequest.php"),
            [
                "name" => "{$this->modelName}UpdateRequest",
                "model" => $this->modelName,
                "validation" => $this->generateRules(true),
                "namespace" => $this->moduleName ? "Modules\\{$this->moduleName}\\Http\\Requests\\{$this->modelName}" : "App\\Http\\Requests\\Admin\\{$this->modelName}",
            ],
            [
                $this->moduleName ? module_path($this->moduleName) ."/Http/Requests": app_path("Http/Requests/Admin"),
                $this->moduleName ? module_path($this->moduleName) ."/Http/Requests/{$this->modelName}": app_path("Http/Requests/Admin/{$this->modelName}"),
            ]
        );
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