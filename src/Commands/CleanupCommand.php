<?php

namespace Jcergolj\FilesystemDiskCleanup\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class CleanupCommand extends Command
{
    /** @var string */
    protected $signature = 'filesystem-disk:cleanup';

    /** @var string */
    protected $description = 'Remove old files from filesystem disk.';

    /** @return int */
    public function handle()
    {
        foreach (config('filesystem-disk-cleanup.disks') as $name => $disk) {
            try {
                config('filesystem-disk-cleanup.disks.'.$name);
            } catch (InvalidArgumentException $e) {
                continue;
            }

            collect(Storage::disk($name)->listContents('/', true))
                ->each(function ($file) use ($name, $disk) {
                    if ($file['lastModified'] < now()->subMinutes($disk['delete_files_older_than_minutes'])->getTimestamp()) {
                        Storage::disk($name)->delete($file['path']);
                    }
                });
        }

        return Command::SUCCESS;
    }
}
