<?php

namespace TomatoPHP\TomatoPHP\Services\Generator;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use TomatoPHP\ConsoleHelpers\Traits\HandleStub;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateCasts;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateJsonResource;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateRequest;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateCols;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateController;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateCreateView;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateEditView;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateFolders;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateForm;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateFormView;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateIndexView;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateMenu;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateModel;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateRoutes;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateRules;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateShowView;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateTable;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\InjectString;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateMenus;

class CRUDGenerator
{
    private string $modelName;
    private string $stubPath;
    private array $cols;

    //Handler
    use HandleStub;
    use InjectString;

    //Generate Classes
    use GenerateFolders;
    use GenerateCols;
    use GenerateRules;
    use GenerateModel;
    use GenerateCasts;
    use GenerateTable;
    use GenerateRequest;
    use GenerateJsonResource;
    use GenerateController;
    use GenerateRoutes;
    use GenerateMenus;

    //Generate From & View
    use GenerateForm;

    //Generate Views
    use GenerateIndexView;
    use GenerateShowView;
    use GenerateCreateView;
    use GenerateFormView;
    use GenerateEditView;

    private Connection $connection;

    /**
     * @param string $tableName
     * @param string|bool|null $moduleName
     * @throws Exception
     */
    public function __construct(
        private string $tableName,
        private string | bool | null $moduleName,
        private bool $controllers = true,
        private bool $request = true,
        private bool $models  = true,
        private bool $views  = true,
        private bool $tables  = true,
        private bool $routes = true,
        private bool $json  = true,
        private bool $form = false,
        private bool $apiRoutes=false,
        private bool $menu=true,
    ){
        $connectionParams = [
            'dbname' => config('database.connections.mysql.database'),
            'user' => config('database.connections.mysql.username'),
            'password' => config('database.connections.mysql.password'),
            'host' => config('database.connections.mysql.host'),
            'driver' => 'pdo_mysql',
        ];

        $this->connection = DriverManager::getConnection($connectionParams);
        $this->modelName = Str::ucfirst(Str::singular(Str::camel($this->tableName)));
        $this->stubPath = base_path(config('tomato-php.stubs-path')) . "/";
        $this->cols = $this->getCols();
    }

    /**
     * @return void
     */
    public function generate(): void
    {
        $this->generateFolders();
        sleep(3);
        if($this->models){
            $this->generateModel();
            $this->generateCasts();
        }
        if($this->tables){
            $this->generateTable();
        }
        if($this->form){
            $this->generateControllerForBuilder();
        }
        else if($this->request){
            $this->generateRequest();
            $this->generateControllerForRequest();
        }
        else {
            $this->generateController();
        }

        if($this->json){
            $this->generateJsonResource();
        }
        if($this->routes || $this->apiRoutes){
            $this->generateRoutes();
        }
        if($this->views){
            $this->generateIndexView();
            if ($this->form){
                $this->generateFormView();
                $this->generateFormBuilderClass();

            }else{
                $this->generateCreateView();
                $this->generateEditView();
            }
            $this->generateShowView();
        }
        if($this->menu){
            $this->generateMenus();
        }
    }
}
