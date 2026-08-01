<?php
// test.php
require __DIR__ . '/vendor/autoload.php';

// Cek apakah class User bisa ditemukan
if (class_exists('App\Models\User')) {
    echo "Class App\Models\User ditemukan\n";
} else {
    echo "Class App\Models\User TIDAK ditemukan\n";
}

// Cek semua class yang sudah dideklarasikan
$classes = get_declared_classes();
$userClasses = array_filter($classes, function($class) {
    return strpos($class, 'User') !== false;
});

echo "\nClass dengan nama 'User':\n";
print_r($userClasses);