<?php

namespace Mabrouk\Mediable\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MediablePublishRoutesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mediable:publish-routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the routes for the Mediable package';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $routesPublishSubDirectory = config('mediable.routes_publish_subdirectory');

        if (File::exists(base_path("routes/{$routesPublishSubDirectory}mediable_routes.php"))) {
            $this->info("Routes have already been published in routes/{$routesPublishSubDirectory} directory.");
            return Command::SUCCESS;
        }

        File::copy(
            __DIR__ . "/../../routes/mediable_routes.php",
            base_path("routes/{$routesPublishSubDirectory}mediable_routes.php")
        );

        $this->publishConfiguration();

        $this->info('Routes have been published successfully.');

        return Command::SUCCESS;
    }

    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => 'Mabrouk\Mediable\MediableServiceProvider',
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params, new \Symfony\Component\Console\Output\NullOutput());
    }
}