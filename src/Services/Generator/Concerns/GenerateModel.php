<?php

namespace TomatoPHP\TomatoPHP\Services\Generator\Concerns;

use Illuminate\Support\Facades\Artisan;

trait GenerateModel
{
    public function generateModel()
    {
        //Check if model exists or not

        if($this->moduleName){
            if(!file_exists(module_path($this->moduleName) . '/Entities/'. $this->modelName . '.php')){
                $command = 'krlove:generate:model ' . $this->modelName . ' --table-name=' . $this->tableName . ' --output-path=' . module_path($this->moduleName) . '/Entities' . ' --namespace=' . "Modules" . "\\\\" . $this->moduleName . "\\\\" . "Entities";
            }
        }
        else if(!file_exists(app_path("Models/{$this->modelName}.php"))){
            $command = 'krlove:generate:model ' . $this->modelName . ' --table-name=' . $this->tableName . ' --output-path=' . app_path('/Models') . ' --namespace=' . "\\App\\\\Models\\";
        }

        if(isset($command))
            Artisan::call($command);

        \Laravel\Prompts\info("Model Generate Success");
    }
}
