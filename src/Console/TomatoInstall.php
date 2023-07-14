<?php

namespace TomatoPHP\TomatoPHP\Console;

use Doctrine\DBAL\Schema\Schema;
use Illuminate\Console\Command;
use Nwidart\Modules\Facades\Module;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;
use TomatoPHP\TomatoPHP\Services\Generator\CRUDGenerator;

class TomatoInstall extends Command
{
    use RunCommand;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'tomato:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create a new CRUD for the application by tomato';


    /**
     * @return void
     */
    public function handle(): void
    {
        //Get Table Name
        $check = true;
        while ($check) {
            $tableName = $this->ask('🍅 Please input your table name you went to create CRUD? (ex: users)');

            if (\Illuminate\Support\Facades\Schema::hasTable($tableName)) {
                $check = false;
            }
            else {
                $this->error("Sorry table not found!");
            }
        }

        //Check if user need to use HMVC
        $isModule = $this->ask('🍅 Do you went to use HMVC module? (y/n)', 'y');
        if (!$isModule) {
            $isModule = 'y';
        }
        $moduleName = false;
        if ($isModule === 'y'){
            $moduleName = $this->ask('🍅 Please input your module name? (ex: Translations)');
            if ($moduleName){
                if (class_exists(\Nwidart\Modules\Facades\Module::class)){
                    $check = \Nwidart\Modules\Facades\Module::find($moduleName);
                    if (!$check) {
                        $this->info('🍅 Module not found but we will create it for you');
                        $this->artisanCommand(["module:make", $moduleName]);
                    }
                }
                else {
                    $this->error('🍅 Sorry nwidart/laravel-modules not installed please install it first');
                }
            }
        }

        $isBuilder = $this->ask('🍅 Do you went to use Form Builder? (form/file)', 'form');
        
        //Generate CRUD Service
        try {
            $resourceGenerator = new CRUDGenerator(tableName:$tableName,moduleName:$moduleName,isBuilder: $isBuilder);
            $resourceGenerator->generate();
            $this->info('🍅 CRUD Has Been Generated Success');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return;
        }
    }
}
