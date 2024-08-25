<?php
spl_autoload_register(function ($class) {
    // Define the base directory for your namespace prefix
    $baseDir = __DIR__ . '/';

    // Replace the namespace prefix with the base directory
    $class = str_replace('\\', '/', $class);

    // Define paths for different namespaces
    $paths = [
        'Presentation/Controllers/',
        'Core/Models/',
        'Infrastructure/Services/',
        'Core/Data/',
        'Core/',
        'Presentation/Views/Home',
        'Presentation/Views/Login',
        'Presentation/Views/Post',
        'Presentation/Views/Profile',
        'Presentation/Views/Shared',
    ];

    // foreach ($paths as $path) {
    //     $file = $baseDir . $path . $class . '.php';
    //     if (file_exists($file)) {
    //         require_once $file;
    //         return;
    //     }
    // }
});
