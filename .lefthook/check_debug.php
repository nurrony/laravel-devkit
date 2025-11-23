<?php

$dangerous = [
    'dd(',
    'dump(',
    'var_dump(',
    'print_r(',
];

$files = array_slice($argv, 1);
$errors = [];

foreach ($files as $file) {
    if (! file_exists($file)) {
        continue;
    }

    $content = file_get_contents($file);

    foreach ($dangerous as $debugFn) {
        if (stripos($content, $debugFn) !== false) {
            $errors[] = "{$file} contains forbidden debug call: {$debugFn}";
        }
    }
}

if (! empty($errors)) {
    echo "\nDangerous debug functions found:\n";
    foreach ($errors as $error) {
        echo " - {$error}\n";
    }
    echo "\nRemove debug calls before committing.\n";
    exit(1); // fail commit
}

exit(0);
