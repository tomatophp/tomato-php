<?php

namespace TomatoPHP\TomatoPHP\Services\Generator\Concerns;

use Illuminate\Support\Facades\Artisan;

trait GenerateModel
{
    public function generateModel()
    {
        //Check if model exists or not

        if($this->moduleName){
            if(!file_exists(module_path($this->moduleName) . '/App/Models/'. $this->modelName . '.php')){
                $command = 'krlove:generate:model ' . $this->modelName . ' --table-name=' . $this->tableName . ' --output-path=' . module_path($this->moduleName) . '/App/Models' . ' --namespace=' . "Modules" . "\\\\" . $this->moduleName . "\\\\" . "App" . "\\\\" ."Models";
            }
        }
        else if(!file_exists(app_path("Models/{$this->modelName}.php"))){
            $command = 'krlove:generate:model ' . $this->modelName . ' --table-name=' . $this->tableName . ' --output-path=' . 'Models' . ' --namespace=' . "\\App\\\\Models\\";
        }

        if(isset($command))
            Artisan::call($command);
    }
}
