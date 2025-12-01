<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeModuleCommand extends Command
{
    protected $signature = 'make:module {name}';
    protected $description = 'Create a new module structure';

    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $name = ucfirst($this->argument('name'));
        $modulePath = base_path("Modules/{$name}");

        if ($this->files->exists($modulePath)) {
            $this->error("Module {$name} already exists!");
            return;
        }

        // Create directories
        $this->makeDirectories($modulePath, [
            'Http/Controllers',
            'Http/Requests',
            'Models',
            'Database/migrations',
            'Database/seeders',
            'Resources/views',
            'Routes',
            'Services',
            'Providers',
        ]);

        // Create stub files
        $this->makeController($name, $modulePath);
        $this->makeRoutes($name, $modulePath);
        $this->makeServiceProvider($name, $modulePath);
        $this->makeModuleJson($name, $modulePath);

        $this->info("Module {$name} created successfully!");
        $this->info("Register {$name}ServiceProvider in config/app.php");
    }

    protected function makeDirectories($base, $dirs)
    {
        foreach ($dirs as $dir) {
            $this->files->makeDirectory("{$base}/{$dir}", 0755, true, true);
        }
    }

    protected function makeController($name, $path)
    {
        $controller = <<<PHP
<?php

namespace Modules\\{$name}\\Http\\Controllers;

use App\\Http\\Controllers\\Controller;

class {$name}Controller extends Controller
{
    public function index()
    {
        return response()->json(['message' => '{$name} module working!']);
    }
}
PHP;

        $this->files->put("{$path}/Http/Controllers/{$name}Controller.php", $controller);
    }

    protected function makeRoutes($name, $path)
    {
        $web = <<<PHP
<?php

use Illuminate\\Support\\Facades\\Route;
use Modules\\{$name}\\Http\\Controllers\\{$name}Controller;

Route::middleware('web')
    ->prefix(strtolower('{$name}'))
    ->group(function () {
        Route::get('/', [{$name}Controller::class, 'index']);
    });
PHP;

        $api = <<<PHP
<?php

use Illuminate\\Support\\Facades\\Route;
use Modules\\{$name}\\Http\\Controllers\\{$name}Controller;

Route::middleware('api')
    ->prefix('api/' . strtolower('{$name}'))
    ->group(function () {
        Route::get('/', [{$name}Controller::class, 'index']);
    });
PHP;

        $this->files->put("{$path}/Routes/web.php", $web);
        $this->files->put("{$path}/Routes/api.php", $api);
    }

    protected function makeServiceProvider($name, $path)
    {
        $provider = <<<PHP
<?php

namespace Modules\\{$name}\\Providers;

use Illuminate\\Support\\ServiceProvider;

class {$name}ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \$this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        \$this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        \$this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        \$this->loadViewsFrom(__DIR__.'/../Resources/views', strtolower('{$name}'));
    }
}
PHP;

        $this->files->put("{$path}/Providers/{$name}ServiceProvider.php", $provider);
    }

    protected function makeModuleJson($name, $path)
    {
        $json = [
            'name' => $name,
            'namespace' => "Modules\\{$name}",
            'version' => '1.0.0',
            'author' => 'Your Name',
        ];

        $this->files->put("{$path}/module.json", json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
