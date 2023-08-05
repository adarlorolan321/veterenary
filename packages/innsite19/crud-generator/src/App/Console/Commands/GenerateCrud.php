<?php

namespace Innsite19\CrudGenerator\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class GenerateCrud extends Command
{

    protected $signature = 'crud:generate';
    protected $descripton = 'Innsite19 Crud Generator';
    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $model = $this->inputModel();
        $columns = $this->inputColumn();

        $this->createModelStub($model, $columns['fields']);
        $this->createRequestStub($model, $columns['fields']);

        $this->createResourceStub($model);
        $this->createPageControllerStub($model);
        $this->createMigrationStub($model, $columns['columns']);
    }

    public function inputColumn()
    {
        $columns = [];

        $fields = [];

        $initColumn = $this->ask('Enter new column (press enter to finish)');

        while (!empty($initColumn)) {
            $fields[] = $initColumn;

            $type = $this->ask('Enter column type');
            while (empty($type)) {
                $this->info("Column type is required!");
                $type = $this->ask('Enter column type');
            }
            $modifiers = [];
            $modifier = $this->ask('Enter column modifier (press enter to finish)');
            while (!empty($modifier)) {
                $newModifier = [
                    'modifier' => $modifier,
                    'value' => null
                ];
                if (!empty($modifier)) {
                    $newModifier['value'] = $this->ask('Enter column modifier value');
                }
                $modifier = $this->ask('Enter column modifier (press enter to finish)');
                $modifiers[] = $newModifier;
            }

            $modifierString = '';

            foreach ($modifiers as $mod) {
                $modifierString .= '->' . $mod['modifier'] . '(' . (!empty($mod['value']) ? "'" . $mod['value'] . "'" : '') . ')';
            }

            $columns[] = '$table->' . $type . "('" . $initColumn . "')" . $modifierString . ';';

            $initColumn = $this->ask("Enter new column (press enter to finish):");
        }
        return [
            'columns' => $columns,
            'fields' => $fields,
        ];
    }


    public function inputModel()
    {
        $model = Str::studly(Str::singular($this->ask('Enter model name (press enter to finish)')));
        while (empty($model)) {
            $this->info("Model is required!");
            $model = $this->ask('Enter model name (press enter to finish)');
        }
        $folder = "";
        $askFolder = $this->ask('Enter model folder name: (press enter to finish)');
        if (!empty($askFolder)) {
            $folder = Str::studly(Str::title(Str::singular($askFolder)));
        }

        return [
            'rawModel' => $model,
            'modelClass' => $model,
            'model' => (!empty($folder) ? $folder . '\\' : '') . $model,
            'modelNamespace' => 'App\Models\\' . (!empty($folder) ? $folder : ''),
            'pageControllerNamespace' => 'App\Http\Controllers\\' . (!empty($folder) ? $folder : ''),
            'apiControllerNamespace' => 'App\Http\Controllers\Api\V1\\' . (!empty($folder) ? $folder : ''),
            'singular_model' => Str::snake(Str::singular($model)),
            'resources' => [
                (!empty($folder) ? $folder . '\\' : '') . $model . 'ListResource'
            ],
            'requests' => [
                (!empty($folder) ? $folder . '\\' : '') . 'Store' . $model . 'Request',
                (!empty($folder) ? $folder . '\\' : '') . 'Update' . $model . 'Request',
            ],
            'requestNamespace' => 'App\\Http\\Requests\\' . (!empty($folder) ? $folder : ''),
            'resourceNamespace' => 'App\\Http\\Resources\\' . (!empty($folder) ? $folder : ''),

            'table' => Str::snake(Str::pluralStudly($model)),
            'folder' => !empty($folder) ? $folder : '',
        ];
    }

    public function getStub($type)
    {
        $base_path = base_path('packages\innsite19\crud-generator\src\App\Console\Commands\stubs\\' . $type . '.stub');
        return file_get_contents($base_path);
    }

    public function createModelStub($model, $fields)
    {
        $stub = $this->getStub('model');


        $fillable = "";

        foreach ($fields as $field) {
            $fillable .= '"' . $field . '",';
        }



        $modelTemplate = str_replace(
            [
                "{{ class }}",
                "{{ namespace }}",
                "{{ fillable }}"
            ],
            [
                $model['modelClass'],
                $model['modelNamespace'],
                $fillable,
            ],
            $stub
        );

        $path = app_path('\Models\\' . $model['folder']);

        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        file_put_contents($path . '\\' . $model['modelClass'] . '.php', $modelTemplate);
    }

    public function createPageControllerStub($model)
    {
        $stub = $this->getStub('page-controller');
        $modelTemplate = str_replace(
            [
                "{{ class }}",
                "{{ table }}",
                "{{ namespace }}",
                "{{ modelNameSpace }}",
                "{{ requestNamespace }}",
                "{{ resourceNamespace }}"
            ],
            [
                $model['modelClass'],
                $model['table'],
                $model['pageControllerNamespace'],
                $model['modelNamespace'],
                $model['requestNamespace'],
                $model['resourceNamespace'],
            ],
            $stub
        );
        $path = app_path('\Http\\Controllers\\' . $model['folder']);
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }
        file_put_contents($path . '\\' . $model['modelClass'] . 'Controller.php', $modelTemplate);
    }

    public function createMigrationStub($model, $columns)
    {
        $stub = $this->getStub('migration');

        $migration = implode("\n", $columns);

        $migrationTemplate = str_replace(
            [
                "{{ table }}",
                "{{ migration }}",
            ],
            [
                $model['table'],
                $migration
            ],
            $stub
        );
        $path = database_path('\\migrations');
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }
        file_put_contents($path . '\\' . now()->format('Y_m_d_his') . '_create_' . $model['table'] . '_tables.php', $migrationTemplate);
    }

    public function createResourceStub($model)
    {
        $stub = $this->getStub('resource');
        $modelTemplate = str_replace(
            [
                "{{ namespace }}",
                "{{ class }}",
            ],
            [
                $model['resourceNamespace'],
                $model['modelClass'],
            ],
            $stub
        );
        $path = app_path('\Http\\Resources\\' . $model['folder']);

        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }
        file_put_contents($path . '\\' . $model['modelClass'] . 'ListResource.php', $modelTemplate);
    }

    public function createRequestStub($model, $fields)
    {
        $stub = $this->getStub('request');

        $validations = "";

        foreach ($fields as $field) {
            $validations .= '"' . $field . '" => ["required"],';
        }


        $storeStubTemplate = str_replace(
            [
                "{{ class }}",
                "{{ namespace }}",
                "{{ permission }}",
                "{{ type }}",
                "{{ validations }}"
            ],
            [
                $model['modelClass'],
                $model['requestNamespace'],
                'store ' . $model['singular_model'],
                'Store',
                $validations
            ],
            $stub
        );

        $updateStubTemplate = str_replace(
            [
                "{{ class }}",
                "{{ namespace }}",
                "{{ permission }}",
                "{{ type }}",
                "{{ validations }}"
            ],
            [
                $model['modelClass'],
                $model['requestNamespace'],
                'update ' . $model['singular_model'],
                'Update',
                $validations
            ],
            $stub
        );

        $path = app_path('\Http\\Requests\\' . $model['folder']);

        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        file_put_contents($path . '\\Store' . $model['modelClass'] . 'Request.php', $storeStubTemplate);
        file_put_contents($path . '\\Update' . $model['modelClass'] . 'Request.php', $updateStubTemplate);
    }
}
