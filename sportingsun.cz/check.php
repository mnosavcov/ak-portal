<?php

// Umístění, kam se uloží přijatý soubor
$uploadDir = dirname(dirname(dirname(__DIR__))) . '/backups-pvtrusted-cz/*';

// Datum před 12 hodinami
$timestamp = time() - (12 * 60 * 60);

// Seznam souborů
$files = glob($uploadDir);

$actual = false;
foreach ($files as $file) {
    // Kontrola, zda je soubor mladší než 12 hodin
    if (filemtime($file) > $timestamp) {
        $actual = true;
    }
}

if (!$actual) {
    echo 'více než 12 hodin neproběhla záloha';
    die();
}

$timestamp = time() - (7 * 24 * 60 * 60);

// Seznam souborů
$files = glob($uploadDir);
foreach ($files as $file) {
    if (filemtime($file) < $timestamp) {
        if (unlink($file)) {
            //
        } else {
            echo "Nepodařilo se smazat starý soubor zálohy.";
            die();
        }
    }
}

echo 'success';
