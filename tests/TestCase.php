<?php

namespace Jcergolj\FilesystemDiskCleanup\Tests;

use Jcergolj\FilesystemDiskCleanup\FilesystemDiskCleanupServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [FilesystemDiskCleanupServiceProvider::class];
    }
}
