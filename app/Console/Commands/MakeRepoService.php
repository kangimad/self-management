<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeRepoService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan make:repo-service User
     */
    protected $signature = 'make:repo-service {name : The base name for Repository and Service (e.g. User)}';

    /**
     * The console command description.
     */
    protected $description = 'Create a Repository and Service class under app/Repositories and app/Services';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = Str::studly($this->argument('name'));

        $repositoryPath = app_path("Repositories/{$name}Repository.php");
        $servicePath = app_path("Services/{$name}Service.php");

        // Pastikan foldernya ada
        File::ensureDirectoryExists(app_path('Repositories'));
        File::ensureDirectoryExists(app_path('Services'));

        // === Repository Stub ===
        $repositoryStub = <<<PHP
        <?php

        namespace App\Repositories;

        use App\Models\\{$name};

        class {$name}Repository
        {
            public function all()
            {
                return {$name}::orderBy('id', 'desc')->get();
            }

            public function find(\$id)
            {
                return {$name}::findOrFail(\$id);
            }

            public function create(array \$data)
            {
                return {$name}::create(\$data);
            }

            public function update(\$id, array \$data)
            {
                \$model = {$name}::findOrFail(\$id);
                \$model->update(\$data);
                return \$model;
            }

            public function delete(\$id)
            {
                return {$name}::destroy(\$id);
            }
        }

        PHP;

        // === Service Stub ===
        $serviceStub = <<<PHP
        <?php

        namespace App\Services;

        use App\Repositories\\{$name}Repository;

        class {$name}Service
        {
            protected \$repository;

            public function __construct({$name}Repository \$repository)
            {
                \$this->repository = \$repository;
            }

            public function getAll()
            {
                return \$this->repository->all();
            }

            public function find(\$id)
            {
                return \$this->repository->find(\$id);
            }

            public function create(array \$data)
            {
                return \$this->repository->create(\$data);
            }

            public function update(\$id, array \$data)
            {
                return \$this->repository->update(\$id, \$data);
            }

            public function delete(\$id)
            {
                return \$this->repository->delete(\$id);
            }
        }

        PHP;

        // Buat file jika belum ada
        if (File::exists($repositoryPath)) {
            $this->warn("⚠️ Repository already exists: {$repositoryPath}");
        } else {
            File::put($repositoryPath, $repositoryStub);
            $this->info("✅ Created: {$repositoryPath}");
        }

        if (File::exists($servicePath)) {
            $this->warn("⚠️ Service already exists: {$servicePath}");
        } else {
            File::put($servicePath, $serviceStub);
            $this->info("✅ Created: {$servicePath}");
        }

        $this->info('🎉 Repository & Service generated successfully!');
    }
}
