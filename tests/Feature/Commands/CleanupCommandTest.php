<?php

namespace Jcergolj\FilesystemDiskCleanup\Tests\Feature\Commands;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Jcergolj\FilesystemDiskCleanup\Tests\TestCase;

/** @see \Jcergolj\FilesystemDiskCleanup\Commands\CleanupCommand */
class CleanupCommandTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('jcergolj-test');
        Storage::fake('disk-to-skip');

        Config::set('filesystem-disk-cleanup.disks.jcergolj-test', [
            'delete_files_older_than_minutes' => 10,
        ]);
    }

    /** @test */
    public function remove_old_file()
    {
        Storage::disk('disk-to-skip')->put('keep.txt', '');

        Storage::disk('jcergolj-test')->put('remove.txt', '');

        $this->travel(11)->minutes();

        $this->artisan('filesystem-disk:cleanup')->assertExitCode(0);

        Storage::disk('jcergolj-test')->assertMissing('remove.txt');

        Storage::disk('disk-to-skip')->assertExists('keep.txt');
    }

    /** @test */
    public function keep_new_files()
    {
        Storage::disk('disk-to-skip')->put('keep.txt', '');

        Storage::disk('jcergolj-test')->put('keep.txt', '');

        $this->travel(9)->minutes();

        $this->artisan('filesystem-disk:cleanup')->assertExitCode(0);

        Storage::disk('jcergolj-test')->assertExists('keep.txt');

        Storage::disk('disk-to-skip')->assertExists('keep.txt');
    }

    /** @test */
    function do_nothing_if_disk_does_not_exists()
    {
        $this->expectException(\InvalidArgumentException::class);

        Config::set('filesystem-disk-cleanup.disks.invalid', [
            'delete_files_older_than_minutes' => 10,
        ]);

        $this->artisan('filesystem-disk:cleanup')->assertExitCode(0);
    }
}
