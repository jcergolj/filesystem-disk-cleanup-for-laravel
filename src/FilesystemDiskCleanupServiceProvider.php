<?php

namespace Jcergolj\FilesystemDiskCleanup;

use Illuminate\Support\ServiceProvider;
use Jcergolj\FilesystemDiskCleanup\Commands\CleanupCommand;

class FilesystemDiskCleanupServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/filesystem-disk-cleanup.php' => config_path('filesystem-disk-cleanup.php'),
            ], 'config');

            $this->commands([
                CleanupCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/filesystem-disk-cleanup.php', 'filesystem-disk-cleanup');
    }
}
