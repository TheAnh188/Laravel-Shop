<?php

namespace App\Services;

use App\Services\Interfaces\GeneratorServiceInterface;
use App\Repositories\Interfaces\GeneratorRepositoryInterface as GeneratorRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreGeneratorRequest;
use App\Http\Requests\UpdateGeneratorRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

/**
 * Class GeneratorService
 * @package App\Services
 */
class GeneratorService implements GeneratorServiceInterface
{
    protected $generatorRepository;

    public function __construct(GeneratorRepository $generatorRepository)
    {
        $this->generatorRepository = $generatorRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = $request->input('keyword');
        $condition['status'] = $request->integer('status');
        // $condition['where'] = [
        //     ['generators.deleted_at', '=', NULL],
        // ];
        $perpage = $request->integer('perpage');

        $generators = $this->generatorRepository->paginate(['id', 'name', 'schema',], $condition, $perpage, [], ['path' => '/generator'], [], [], []);
        return $generators;
    }

    private function convertModelToTable($model)
    {
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $model));
        $temp = str_replace(' ', '', $temp);
        return $temp;
    }

    //tao file migration
    private function createMigrationFile($tableName, $schema)
    {
        $migrationTemplate = <<<MIGRATION
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        {$schema}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('{$tableName}');
    }
};
MIGRATION;

        return $migrationTemplate;
    }

    //tao them pivot schema
    private function createPivotSchema1($tableName = '', $foreignKey = '')
    {
        $pivotSchema = <<<SCHEMA
Schema::create('{$tableName}_language', function (Blueprint \$table) {
            \$table->unsignedBigInteger('{$foreignKey}');
            \$table->unsignedBigInteger('language_id');
            \$table->foreign('{$foreignKey}')->references('id')->on('{$tableName}s')->onDelete('cascade');
            \$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            \$table->string('name');
            \$table->text('description');
            \$table->longText('content');
            \$table->string('meta_title');
            \$table->string('meta_keyword');
            \$table->text('meta_description');
            \$table->string('canonical')->unique();
            \$table->timestamps();
            \$table->timestamp('deleted_at')->nullable();
        });
SCHEMA;

        return $pivotSchema;
    }

    private function createPivotSchema2($tableName = '', $name = '')
    {
        $explodedName = explode('_', $name);
        $pivotSchema = <<<SCHEMA
Schema::create('{$tableName}', function (Blueprint \$table) {
            \$table->unsignedBigInteger('{$explodedName[0]}_catalogue_id');
            \$table->unsignedBigInteger('{$explodedName[0]}_id');
            \$table->foreign('{$explodedName[0]}_catalogue_id')->references('id')->on('{$explodedName[0]}_catalogues')->onDelete('cascade');
            \$table->foreign('{$explodedName[0]}_id')->references('id')->on('{$explodedName[0]}s')->onDelete('cascade');
        });
SCHEMA;

        return $pivotSchema;
    }

    private function makeDatabase($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only(['schema', 'name', 'module_type']);
            //chuyen input name thanh chu thuong va xoa khoang trang
            $tableName = $this->convertModelToTable($payload['name']);
            $migrationFileName = date('Y_m_d_His') . '_create_' . $tableName . 's_table.php';
            $migrationPath = database_path('migrations\\' . $migrationFileName);
            $migrationTemplate = $this->createMigrationFile($tableName . 's', $payload['schema']);
            // FILE::put($migrationPath, $migrationTemplate); //tạo file migration
            if ($payload['module_type'] !== 3) {
                $foreignKey = $this->convertModelToTable($payload['name']) . '_id';
                $pivotMigrationFileName1 = date('Y_m_d_His', time() + 5) . '_create_' . $tableName . '_language_table.php';
                $pivotMigrationPath1 = database_path('migrations\\' . $pivotMigrationFileName1);
                $pivotMigrationTemplate1 = $this->createMigrationFile(
                    $tableName . '_language',
                    $this->createPivotSchema1($tableName, $foreignKey)
                );
                // FILE::put($pivotMigrationPath1, $pivotMigrationTemplate1); //tạo file migration
                $pivotMigrationFileName2 = date('Y_m_d_His', time() + 5) . '_create_product_catalogue_product_table.php';
                $pivotMigrationPath2 = database_path('migrations\\' . $pivotMigrationFileName2);
                $pivotMigrationTemplate2 = $this->createMigrationFile(
                    'product_catalogue_product',
                    $this->createPivotSchema2('product_catalogue_product', $tableName)
                );
                FILE::put($pivotMigrationPath2, $pivotMigrationTemplate2); //tạo file migration

            }
            Artisan::call('migrate'); //goi lenh migrate
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    private function createControllerTemplate($name, $controllerType)
    {
        try {
            $controllerName = str_replace(' ', '', ucwords($name)) . 'Controller';
            $controllerTemplatePath = base_path('app\\Templates\\' . $controllerType . '.php'); //lay duong dan TemplateController
            $controllerContent = file_get_contents($controllerTemplatePath); //lay noi dung trong TemplateController

            //Viet hoa chu dau tien sau do xoa khoang trang

            $controllerArguments = [
                'ModuleTemplate' => str_replace(' ', '', ucwords($name)),
                'moduleTemplate' => lcfirst(str_replace(' ', '', ucwords($name))),
                'foreignKey' => $this->convertModelToTable(ucwords($name)) . ($controllerType == 'CatalogueControllerTemplate' ? '_id' : '_catalogue_id'),
                'moduleView' => $this->convertModelToTable(ucwords($name)),
                'moduleCanonical' => str_replace('_', '-', $this->convertModelToTable(ucwords($name))),
                'tableName' => $this->convertModelToTable(ucwords($name)) . ($controllerType == 'CatalogueControllerTemplate' ? 's' : '_catalogues'),
            ];

            //truyen doi so
            foreach ($controllerArguments as $key => $value) {
                $controllerContent = str_replace('{' . $key . '}', $value, $controllerContent);
            }

            $controllerPath = base_path('app\\Http\\Controllers\\Backend\\' . $controllerName . '.php');
            FILE::put($controllerPath, $controllerContent); //tạo file controller
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    private function createSingleController() {}

    private function makeController($request)
    {
        $payload = $request->only(['name', 'module_type']);
        switch ($payload['module_type']) {
            case 1:
                $this->createControllerTemplate($payload['name'], 'CatalogueControllerTemplate');
                break;
            case 2:
                $this->createControllerTemplate($payload['name'], 'ControllerTemplate');
                break;
            default:
                $this->createSingleController();
        }
    }

    private function createModelTemplate($name)
    {
        try {
            //Viet hoa chu dau tien sau do xoa khoang trang
            //productcatalogue => Productcatalogue ||
            //Productcatalogue = Productcatalogue ||
            //productCatalogue = ProductCatalogue
            //ProductCatalogue = ProductCatalogue
            //product catalogue => ProductCatalogue
            //product Catalogue => ProductCatalogue
            //Product catalogue => ProductCatalogue
            //Product Catalogue => ProductCatalogue
            $modelName = $name;
            $catalogueModelTemplatePath = base_path('app\\Templates\\CatalogueModelTemplate.php'); //lay duong dan CatalogueTemplateController
            $modelContent = file_get_contents($catalogueModelTemplatePath); //lay noi dung trong CatalogueTemplateController
            $modelArguments = [
                'ModuleTemplate' => str_replace(' ', '', ucwords($modelName)),
                'tableName' => $this->convertModelToTable(ucwords($modelName)),
                'relatedModel' => explode('_', $this->convertModelToTable(ucwords($modelName)))[0],
                'RelatedModel' => ucwords(explode('_', $this->convertModelToTable(ucwords($modelName)))[0]),
            ];

            //truyen doi so vao file model
            foreach ($modelArguments as $key => $value) {
                $modelContent = str_replace('{' . $key . '}', $value, $modelContent);
            }
            $modelPath = base_path('app\\Models\\' . str_replace(' ', '', ucwords($modelName)) . '.php');
            FILE::put($modelPath, $modelContent); //tạo file model
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    private function makeModel($request)
    {
        $payload = $request->only(['name', 'module_type']);
        switch ($payload['module_type']) {
            case 1:
                $this->createModelTemplate($payload['name']); //Model danh muc
                break;
            case 2:
                $this->createModelTemplate($payload['name']); //Model chi tiet (chua lam)
                break;
            default:
                $this->createSingleController();
        }
    }

    private function makeRepository($request)
    {
        try {
            $payload = $request->only(['name', 'module_type']);
            $name = $payload['name'];
            $repositoryName = $name . 'Repository';
            $repositoryInterfaceName = $name . 'RepositoryInterface';

            //tạo file RepositoryInterface
            $repositoryInterfaceTemplatePath = base_path('app\\Templates\\RepositoryInterfaceTemplate.php');
            $repositoryInterfaceContent = file_get_contents($repositoryInterfaceTemplatePath);
            $repositoryInterfaceArguments = [
                'Module' => str_replace(' ', '', ucwords($name)),
            ];
            $repositoryInterfaceContent = str_replace('{Module}', $repositoryInterfaceArguments['Module'], $repositoryInterfaceContent);
            $repositoryInterfacePath = base_path('app\\Repositories\\Interfaces\\' . str_replace(' ', '', ucwords($repositoryInterfaceName)) . '.php');
            FILE::put($repositoryInterfacePath, $repositoryInterfaceContent);

            //Tao file Repository
            $repositoryTemplatePath = base_path('app\\Templates\\RepositoryTemplate.php');
            $repositoryContent = file_get_contents($repositoryTemplatePath);
            $repositoryArguments = [
                'Module' => str_replace(' ', '', ucwords($name)),
                'tableName' => $this->convertModelToTable(ucwords($name)),
            ];
            foreach ($repositoryArguments as $key => $value) {
                $repositoryContent = str_replace('{' . $key . '}', $value, $repositoryContent);
            }
            $repositoryPath = base_path('app\\Repositories\\' . str_replace(' ', '', ucwords($repositoryName)) . '.php');
            FILE::put($repositoryPath, $repositoryContent);
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    private function makeService($request)
    {
        try {
            $payload = $request->only(['name', 'module_type']);
            $name = $payload['name'];
            $serviceName = $name . 'Service';
            $serviceInterfaceName = $name . 'ServiceInterface';

            //tạo file ServiceInterface
            $serviceInterfaceTemplatePath = base_path('app\\Templates\\ServiceInterfaceTemplate.php');
            $serviceInterfaceContent = file_get_contents($serviceInterfaceTemplatePath);
            $serviceInterfaceArguments = [
                'Module' => str_replace(' ', '', ucwords($name)),
            ];
            $serviceInterfaceContent = str_replace('{Module}', $serviceInterfaceArguments['Module'], $serviceInterfaceContent);
            $serviceInterfacePath = base_path('app\\Services\\Interfaces\\' . str_replace(' ', '', ucwords($serviceInterfaceName)) . '.php');
            FILE::put($serviceInterfacePath, $serviceInterfaceContent);

            // Tao file Service
            $serviceTemplatePath = base_path('app\\Templates\\ServiceTemplate.php');
            $serviceContent = file_get_contents($serviceTemplatePath);
            $serviceArguments = [
                'Module' => str_replace(' ', '', ucwords($name)),
                'module' => lcfirst(str_replace(' ', '', ucwords($name))),
                'tableName' => $this->convertModelToTable(ucwords($name)),
                'moduleCanonical' => str_replace('_', '-', $this->convertModelToTable(ucwords($name))),
            ];
            foreach ($serviceArguments as $key => $value) {
                $serviceContent = str_replace('{' . $key . '}', $value, $serviceContent);
            }
            $servicePath = base_path('app\\Services\\' . str_replace(' ', '', ucwords($serviceName)) . '.php');
            FILE::put($servicePath, $serviceContent);
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    private function makeProvider($request)
    {
        try {
            $payload = $request->only(['name', 'module_type']);
            $name = str_replace(' ', '', ucwords($payload['name']));
            $providers = [
                'appServiceProviderPath' => base_path('app\\Providers\\AppServiceProvider.php'),
                'repositoryProviderPath' => base_path('app\\Providers\\RepositoryProvider.php'),
            ];

            foreach ($providers as $key => $value) {
                $providerContent = file_get_contents($value);
                $insertedContent = $key == 'appServiceProviderPath' ?
                    "'App\\Services\Interfaces\\{$name}ServiceInterface' => 'App\\Services\\{$name}Service',"
                    :
                    "'App\\Repositories\\Interfaces\\{$name}RepositoryInterface' => 'App\Repositories\\{$name}Repository',";

                $position = strpos($providerContent, '];');
                if ($position) {
                    //substr_replace thay chuoi truyen vao vo vi tri chi dinh, do length bang 0 nen tat ca cac ky tu tinh tu
                    //vi tri truyen vao se dung sau chuoi truyen vao
                    $newContent = substr_replace($providerContent, "    " . $insertedContent . "\n    ", $position, 0);
                }

                FILE::put($value, $newContent);
            }
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    private function makeRequest($request)
    {
        try {
            $payload = $request->only(['name', 'module_type']);
            $name = str_replace(' ', '', ucwords($payload['name']));
            $requestTypes = [
                'Store' . $name . 'Request',
                'Update' . $name . 'Request',
                'Delete' . $name . 'Request',
            ];
            $requestTemplates = [
                'StoreRequestTemplate',
                'UpdateRequestTemplate',
                'DeleteRequestTemplate',
            ];
            if ($payload['module_type'] != 1) {
                unset($requestTypes[2]);
                unset($requestTemplates[2]);
            }
            foreach ($requestTemplates as $key => $value) {
                $requestTemplatePath = base_path('app\\Templates\\requests\\' . $value . '.php');
                $requestContent = file_get_contents($requestTemplatePath);
                $requestContent = str_replace('{Module}', $name, $requestContent);
                $requestContent = str_replace('{tableName}', $this->convertModelToTable(ucwords($name)), $requestContent);
                $requestPath = base_path('app\\Http\\Requests\\' . $requestTypes[$key] . '.php');
                FILE::put($requestPath, $requestContent);
            }
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    private function makeView($request)
    {
        try {
            $payload = $request->only(['name', 'module_type']);
            $name = str_replace(' ', '', ucwords($payload['name']));
            $formattedName = $this->convertModelToTable($name);
            $viewPath = resource_path('views\\' . $formattedName);
            if (!FILE::exists($viewPath)) {
                $baseComponentPath = $viewPath . '\\components';
                FILE::makeDirectory($viewPath);
                FILE::makeDirectory($baseComponentPath);
                $partialPath = $payload['module_type'] == 1 ? 'catalogue_template' : 'item_template';
                $baseResourceTemplatePath = base_path('app\\Templates\\views\\' . $partialPath . '\\');
                $baseComponentTemplatePath = base_path('app\\Templates\\views\\' . $partialPath . '\\components\\');
                $resourceTemplateFiles = [
                    'create.blade.php',
                    'delete.blade.php',
                    'edit.blade.php',
                    'index.blade.php',
                ];
                $componentTemplateFiles = [
                    'breadcrumb.blade.php',
                    'filter.blade.php',
                    'table.blade.php',
                ];
                foreach ($resourceTemplateFiles as $value) {
                    $resourceTemplatePath = $baseResourceTemplatePath . $value;
                    $resourceContent = file_get_contents($resourceTemplatePath);
                    $resourceArguments = [
                        'moduleCanonical' => str_replace('_', '-', $formattedName),
                        'tableName' => $formattedName,
                        'Module' => str_replace(' ', '', ucwords($name)),
                    ];
                    foreach ($resourceArguments as $keyy => $valuee) {
                        $resourceContent = str_replace('{' . $keyy . '}', $valuee, $resourceContent);
                    }
                    $resourcePath = $viewPath . '\\' . $value;
                    FILE::put($resourcePath, $resourceContent);
                }
                foreach ($componentTemplateFiles as $value) {
                    $componentTemplatePath = $baseComponentTemplatePath . $value;
                    $componentContent = file_get_contents($componentTemplatePath);
                    $componentArguments = [
                        'moduleCanonical' => str_replace('_', '-', $formattedName),
                        'tableName' => $formattedName,
                        'Module' => str_replace(' ', '', ucwords($name)),
                    ];
                    foreach ($componentArguments as $keyy => $valuee) {
                        $componentContent = str_replace('{' . $keyy . '}', $valuee, $componentContent);
                    }
                    $componentPath = $baseComponentPath . '\\' . $value;
                    FILE::put($componentPath, $componentContent);
                }
            }
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    private function makeRule($request)
    {
        try {
            if ($request->input('module_type') == 1) {
                $name = $request->input('name');
                $formattedName = str_replace(' ', '', ucwords($name));
                $rulePath = base_path('app\\Rules\\Child' . $formattedName . 'Rule.php');
                $ruleTemplatePath = base_path('app\\Templates\\rules\\RuleTemplate.php');
                $ruleContent = file_get_contents($ruleTemplatePath);
                $ruleContent = str_replace('{Module}', $formattedName, $ruleContent);
                if (!FILE::exists($rulePath)) {
                    FILE::put($rulePath, $ruleContent);
                }
            }
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    private function makeRoute($request)
    {
        $name = $request->input('name');
        $routeArguments = [
            'Module' => str_replace(' ', '', ucwords($name)),
            'module' => lcfirst(str_replace(' ', '', ucwords($name))),
            'tableName' => $this->convertModelToTable(ucwords($name)),
            'moduleCanonical' => str_replace('_', '-', $this->convertModelToTable(ucwords($name))),
        ];
        $routePath = base_path('routes\\web.php');
        $routeContent = file_get_contents($routePath);
        $addedContent = <<<ROUTE
        Route::group(['middleware' => ['auth_middleware', 'locale'], 'prefix' => '{$routeArguments['moduleCanonical']}'], function () {
            Route::get('/{{$routeArguments['tableName']}}/edit', [{$routeArguments['Module']}Controller::class, 'edit'])->where(['{$routeArguments['tableName']}' => '[0-9]+']);
            Route::get('/{{$routeArguments['tableName']}}/delete', [{$routeArguments['Module']}Controller::class, 'delete'])->where(['{$routeArguments['tableName']}' => '[0-9]+']);
        });
        Route::group(['middleware' => ['auth_middleware', 'locale']], function () {
            Route::resource('{$routeArguments['moduleCanonical']}', {$routeArguments['Module']}Controller::class)->except(['edit', 'delete']);
        });
        //@@new-module@@
        ROUTE;
        $controllerImport = <<<CONTROLLER
        use App\\Http\\Controllers\\Backend\\{$routeArguments['Module']}Controller;
        //@@new-controller@@
        CONTROLLER;
        $routeContent = str_replace('//@@new-module@@', $addedContent, $routeContent);
        $routeContent = str_replace('//@@new-controller@@', $controllerImport, $routeContent);
        FILE::put($routePath, $routeContent);
        die();
    }

    ///chua tao dc file migration product_catalogue_product
    ///chua tao model dc Product Model(moi tao dc ProductCatalogue)
    //chua tao dc Product Repo và Product Interface(moi tao dc ProductCatalogue)
    //chua tao dc Product Service và Interface(moi tao dc ProductCatalogue)
    public function create(StoreGeneratorRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->makeDatabase($request);
            // $this->makeController($request);
            // $this->makeModel($request);
            // $this->makeRepository($request);
            // $this->makeService($request);
            // $this->makeProvider($request);
            // $this->makeRequest($request);
            // $this->makeView($request);
            // $this->makeRule($request);
            // $this->makeRoute($request);
            die();
            $payload = $request->except(['_token']);
            $payload['user_id'] = Auth::id();
            $this->generatorRepository->create($payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function update(int $id, UpdateGeneratorRequest $request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', '_method']);
            $this->generatorRepository->update($id, $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $this->generatorRepository->delete($id);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }
}
