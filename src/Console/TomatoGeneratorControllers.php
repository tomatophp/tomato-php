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

class TomatoGeneratorControllers extends Command
{
    use RunCommand;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tomato:controllers
        {table=0}
        {module=0}
        {--request}
        {--resource}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate controllers for the application by tomato';


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
        $isModule = ($this->argument('module') && $this->argument('module') != "0") ?: confirm('Do you went to use HMVC module?');
        $moduleName = false;
        if ($isModule){
            if (class_exists(\Nwidart\Modules\Facades\Module::class)){
                $modules = \Nwidart\Modules\Facades\Module::toCollection()->map(function ($item){
                    return $item->getName();
                });
                $moduleName = ($this->argument('module') && $this->argument('module') != "0") ? $this->argument('module') : suggest(
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
                    \Laravel\Prompts\info('We Generate It please re-run the command again');
                    exit();
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

        $generateRequest = ($this->option('request') && $this->option('request') != "0") ? $this->option('request') : confirm(
            label: 'Do you went to generate form request?',
        );

        $generateResource = ($this->option('resource') && $this->option('resource') != "0") ? $this->option('resource') : confirm(
            label: 'Do you went to generate json resource?',
        );


        //Generate CRUD Service
        try {
            \Laravel\Prompts\spin(fn()=> (new CRUDGenerator(
                tableName:$tableName,
                moduleName:$moduleName,
                models: false,
                views: false,
                routes: false,
                tables: false,
                controllers: true,
                request: $generateRequest,
                json: $generateResource,
                apiRoutes: false,
                form: false,
                menu: false,
            ))->generate(), 'Generating ...');
        } catch (\Exception $e) {
            \Laravel\Prompts\error($e);
        }

        \Laravel\Prompts\info('🍅 Thanks for using Tomato Plugins & TomatoPHP framework');
        \Laravel\Prompts\info('💼 Join support server on discord https://discord.gg/VZc8nBJ3ZU');
        \Laravel\Prompts\info('📄 You can check docs here https://docs.tomatophp.com');
        \Laravel\Prompts\info('⭐ please gave us a start on any repo if you like it https://github.com/tomatophp');
        \Laravel\Prompts\info('🤝 sponser us here https://github.com/sponsors/3x1io');
    }
}
