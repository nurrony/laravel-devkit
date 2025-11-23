<?php

// Get current branch name (cross-platform)
$branch = trim(shell_exec('git rev-parse --abbrev-ref HEAD'));

// Allowed GitFlow branch name patterns
$patterns = [
    '/^feature\/[a-z0-9._-]+$/i',
    '/^bugfix\/[a-z0-9._-]+$/i',
    '/^hotfix\/[a-z0-9._-]+$/i',
    '/^release\/[0-9]+\.[0-9]+\.[0-9]+$/i',
    '/^develop$/',
    '/^main$/',
    '/^master$/',
];

$valid = false;

foreach ($patterns as $regex) {
    if (preg_match($regex, $branch)) {
        $valid = true;
        break;
    }
}

if (! $valid) {
    echo "\nInvalid branch name: \"{$branch}\"\n";
    echo "Allowed patterns:\n";
    echo "  • feature/<name>\n";
    echo "  • bugfix/<name>\n";
    echo "  • hotfix/<name>\n";
    echo "  • release/<version>\n";
    echo "  • develop\n";
    echo "  • main\n";
    echo "  • master\n\n";
    echo "  • <ticket-number>-<short-description>\n";
    exit(1);
}

exit(0);
