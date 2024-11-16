<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

trait FileManegement
{
    private function downloadFile(string $url): array
    {
        $filename = 'downloads/'.basename($url) ?: uniqid('file_', true).'.gz';

        $response = Http::get($url);

        if ($response->successful()) {
            Storage::disk('public')->put($filename, $response->body());
        }

        return $this->unzipFile($filename);
    }

    private function formatAspasDuplas($array)
    {
        return array_map(function ($item) {
            if (is_array($item)) {
                return $this->formatAspasDuplas($item);
            } elseif (is_string($item)) {
                return str_replace('"', '', $item);
            }

            return $item;
        }, $array);
    }

    private function unzipFile(string $path): array
    {
        $filepath = storage_path("app/public/$path");
        $gzfile = gzopen($filepath, 'rb');
        $count = 0;
        $lines = [];

        while ($line = gzgets($gzfile)) {
            if ($count < 100) {
                $json = $this->formatAspasDuplas(json_decode($line, true));
                array_push($lines, $json);
            }

            $count++;

        }

        gzclose($gzfile);
        unlink($filepath);

        return $lines;
    }
}
