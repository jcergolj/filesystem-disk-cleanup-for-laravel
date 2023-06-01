#### Command, that can be scheduled for deleting files based on filesystem disks older than time specified.

Installation
```bash
    composer require jcergolj/filesystem-disk-cleanup-for-laravel
```

Publish config file
```bash
    php artisan vendor:publish --provider="Jcergolj\FilesystemDiskCleanup\FilesystemDiskCleanupServiceProvider" --tag="config"
```

This is the content of the config/filesystem-disk-cleanup.php config file:
```php
// filesystem-disk-cleanup.php
<?php

return [
    'disks' => [
        // jcergolj-test must match the name in filesystem.php disk config file
        'jcergolj-test' => [
            'delete_files_older_than_minutes' => 60 * 24 * 7,
        ],
    ],
];
```

Here is how the config/filesystem.php should look like with `jcergolj-test` disk registered.
```php
// config/filesystem.php
<?php

return [
    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'jcergolj-test' [
            'driver' => 'local',
            'root' => storage_path('app/jceroglj-folder'),
            'throw' => false,
        ],

        // ...
    ],
];
```

The commands can be scheduled in Laravel's console kernel, just like any other command.
```php
    // app/Console/Kernel.php
    $schedule->command('filesystem-disk:cleanup')->daily()->at('01:00');
```
