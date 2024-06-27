<?php

namespace App\Services;

class FileService
{
    public function getMaxUploadSizeFormated()
    {
        $maxUploadSize = $this->getMaximumUploadSize();
        $formattedSize = $this->formatBytes($maxUploadSize);

        return $formattedSize;
    }

    private function getMaximumUploadSize()
    {
        // Získání hodnot z konfigurace PHP
        $upload_max_filesize = ini_get('upload_max_filesize');
        $post_max_size = ini_get('post_max_size');

        // Převedení hodnot na byty
        $max_upload = $this->convertToBytes($upload_max_filesize);
        $max_post = $this->convertToBytes($post_max_size);

        // Vrátíme menší z obou hodnot, protože to bude efektivní maximální velikost
        return min($max_upload, $max_post);
    }

    private function convertToBytes($value)
    {
        $unit = strtolower(substr($value, -1));
        $bytes = (int)$value;

        switch ($unit) {
            case 'g':
                $bytes *= 1024;
            case 'm':
                $bytes *= 1024;
            case 'k':
                $bytes *= 1024;
        }

        return $bytes;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
