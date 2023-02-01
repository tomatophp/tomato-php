<?php

namespace TomatoPHP\TomatoPHP\Services\Generator;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use TomatoPHP\ConsoleHelpers\Traits\HandleStub;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateCols;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateController;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateCreateView;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateEditView;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateForm;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateIndexView;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateMenu;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateModel;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateRequest;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateRoutes;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateShowView;
use TomatoPHP\TomatoPHP\Services\Generator\Concerns\GenerateTable;

class CRUDGenerator
{
    private string $modelName;
    private string $stubPath;
    private array $cols;

    //Handler
    use HandleStub;

    //Generate Classes
    use GenerateCols;
    use GenerateModel;
    use GenerateTable;
    use GenerateRequest;
    use GenerateController;
    use GenerateRoutes;

    //Generate From & View
    use GenerateForm;

    //Generate Views
    use GenerateIndexView;
    use GenerateShowView;
    use GenerateCreateView;
    use GenerateEditView;
    use GenerateMenu;

    private Connection $connection;

    public function __construct(
        private string $tableName,
        private string | bool | null $moduleName
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
        $this->stubPath = config('tomato-php.stubs-path'). "/";
        $this->cols = $this->getCols();
    }

    public function generate()
    {
        $this->generateModel();
        $this->generateTable();
        $this->generateRequest();
        $this->generateController();
        $this->generateRoutes();
        $this->generateIndexView();
        $this->generateCreateView();
        $this->generateEditView();
        $this->generateShowView();
        $this->generateMenu();
    }

}
