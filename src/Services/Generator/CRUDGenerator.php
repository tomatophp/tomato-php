<?php

namespace TomatoPHP\TomatoPHP\Services\Generator;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use TomatoPHP\ConsoleHelpers\Traits\HandleStub;
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
    use GenerateFolders;
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
    use GenerateFormView;
    use GenerateEditView;
    use GenerateMenu;

    private Connection $connection;

    /**
     * @param string $tableName
     * @param string|bool|null $moduleName
     * @throws Exception
     */
    public function __construct(
        private string $tableName,
        private string | bool | null $moduleName,
        private string $isBuilder
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

    /**
     * @return void
     */
    public function generate(): void
    {
        $this->generateFolders();
        $this->generateModel();
        $this->generateTable();
        $this->generateRequest();
        ($this->isBuilder == 'form')?$this->generateControllerForBuilder():$this->generateController();
        $this->generateRoutes();
        $this->generateIndexView();
        if ($this->isBuilder == 'form'){
            $this->generateFormView();
            $this->generateFormBuilderClass();

        }else{
            $this->generateCreateView();
            $this->generateEditView();
        }

        $this->generateShowView();
        $this->generateMenu();
    }

}
