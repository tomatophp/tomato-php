<?php

namespace TomatoPHP\TomatoPHP\Console;

use Doctrine\DBAL\Schema\Schema;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;
use TomatoPHP\TomatoPHP\Services\Generator\CRUDGenerator;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\search;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use function Laravel\Prompts\error;
use function Laravel\Prompts\warning;
use function Laravel\Prompts\suggest;

class TomatoGenerator extends Command
{
    use RunCommand;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tomato:generate
        {table=0}
        {module=0}
        {--builder}
        {--only-controllers}
    ';

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

        $tables = collect(\DB::select('SHOW TABLES'))->map(function ($item){
            return $item->{'Tables_in_'.config('database.connections.mysql.database')};
        })->toArray();

        $tableName = $this->argument('table') && $this->argument('table') != "0" ? $this->argument('table') : search(
            label: 'Please input your table name you went to create CRUD?',
            options: fn (string $value) => strlen($value) > 0
                ? collect($tables)->filter(function ($item, $key) use ($value){
                    return Str::contains($item, $value) ? (string)$item : null;
                })->toArray()
                : [],
            placeholder: "ex: users",
            scroll: 10
        );

        if(is_numeric($tableName)){
            $tableName = $tables[$tableName];
        }
        else {
            $tableName = $tableName;
        }

        //Check if user need to use HMVC
        $isModule = $this->argument('module') && $this->argument('module') != "0" ?: confirm('Do you went to use HMVC module?');
        $moduleName = false;
        if ($isModule){
            if (class_exists(\Nwidart\Modules\Facades\Module::class)){
                $modules = \Nwidart\Modules\Facades\Module::toCollection()->map(function ($item){
                    return $item->getName();
                });
                $moduleName = $this->argument('module') && $this->argument('module') != "0"  ?: suggest(
                    label:'Please input your module name?',
                    placeholder:'Translations',
                    options: fn (string $value) => strlen($value) > 0
                        ? collect($modules)->filter(function ($item, $key) use ($value){
                            return Str::contains($item, $value) ? $item : null;
                        })->toArray()
                        : [],
                    validate: fn (string $value) => match (true) {
                        strlen($value) < 1 => "Sorry this filed is required!",
                        default => null
                    },
                    scroll: 10
                );
                $check = \Nwidart\Modules\Facades\Module::find($moduleName);
                if (!$check) {
                    $createIt = confirm('Module not found! do you when to create it?');
                    $createIt ? $this->artisanCommand(["module:make", $moduleName]) : $moduleName = null;
                }
            }
            else {
                $installItem = confirm('Sorry nwidart/laravel-modules not installed please install it first. do you when to install it?');
                if($installItem){
                    $this->requireComposerPackages(["nwidart/laravel-modules"]);
                    \Laravel\Prompts\info('Add This line to composer.json psr-4 autoload');
                    \Laravel\Prompts\info('"Modules\\" : "Modules/"');
                    \Laravel\Prompts\info('now run');
                    \Laravel\Prompts\info('composer dump-autoload');
                    \Laravel\Prompts\info('Install success please run the command again');
                    exit();
                }
            }
        }

        $isBuilder = $this->option('builder') && $this->option('builder') != "0" ? $this->option('builder') : select(
            label: 'Do you went to use Form Builder?',
            options: ["form", "file"],
            default: "file",
            validate: fn (string $value) => match (true) {
                strlen($value) === 'form' || strlen($value) === 'file' => 'Sorry please select form/file',
                default => null
            }
        );

        $onlyController = $this->option('only-controllers') && $this->option('only-controllers') != "0" ? $this->option('only-controllers') : confirm(
            label: 'Do you went to generate controllers Only?',
        );


        //Generate CRUD Service
        try {
            $resourceGenerator = new CRUDGenerator(tableName:$tableName,moduleName:$moduleName,isBuilder: $isBuilder, isOnlyController: $onlyController);
            $resourceGenerator->generate();
            info('CRUD Has Been Generated Success');
        } catch (\Exception $e) {
            error($e);
        }
    }
}
