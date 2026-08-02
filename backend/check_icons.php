<?php
require __DIR__ . '/vendor/autoload.php';
$cases = Filament\Support\Icons\Heroicon::cases();
$needles = ['Bars', 'Photo', 'Document', 'Globe', 'Cog', 'Rectangle'];
foreach ($cases as $case) {
    foreach ($needles as $n) {
        if (str_contains($case->name, $n)) {
            echo $case->name . PHP_EOL;
            break;
        }
    }
}
