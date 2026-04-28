<?php

namespace App\Support;

use RuntimeException;

class EnvEditor
{
    public static function read(string $path): string
    {
        if (!is_file($path)) {
            throw new RuntimeException("Env file not found: {$path}");
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            throw new RuntimeException("Unable to read env file: {$path}");
        }

        return $contents;
    }

    /**
     * @return array<int, array{raw:string, key:?string, value:?string, is_kv:bool}>
     */
    public static function parseLines(string $contents): array
    {
        $lines = preg_split("/\r\n|\n|\r/", $contents) ?: [];
        $parsed = [];

        foreach ($lines as $line) {
            $raw = $line;
            $trim = ltrim($line);

            if ($trim === '' || str_starts_with($trim, '#')) {
                $parsed[] = ['raw' => $raw, 'key' => null, 'value' => null, 'is_kv' => false];
                continue;
            }

            $eqPos = strpos($line, '=');
            if ($eqPos === false) {
                $parsed[] = ['raw' => $raw, 'key' => null, 'value' => null, 'is_kv' => false];
                continue;
            }

            $key = trim(substr($line, 0, $eqPos));
            $value = substr($line, $eqPos + 1);
            $value = self::stripInlineComment($value);
            $value = self::unquote(trim($value));

            $parsed[] = ['raw' => $raw, 'key' => $key !== '' ? $key : null, 'value' => $value, 'is_kv' => $key !== ''];
        }

        return $parsed;
    }

    /**
     * @return array<string, string> last occurrence wins
     */
    public static function toKeyValueMap(string $contents): array
    {
        $map = [];
        foreach (self::parseLines($contents) as $row) {
            if ($row['is_kv'] && $row['key'] !== null) {
                $map[$row['key']] = (string) ($row['value'] ?? '');
            }
        }
        return $map;
    }

    /**
     * Update all occurrences of keys; append missing keys at the end.
     *
     * @param array<string, string|null> $updates null means set to empty string
     */
    public static function applyUpdates(string $contents, array $updates): string
    {
        $rows = self::parseLines($contents);
        $seenKeys = [];

        foreach ($rows as $i => $row) {
            if (!$row['is_kv'] || $row['key'] === null) {
                continue;
            }

            $key = $row['key'];
            if (!array_key_exists($key, $updates)) {
                continue;
            }

            $seenKeys[$key] = true;
            $value = $updates[$key] ?? '';
            $rows[$i]['raw'] = $key . '=' . self::quoteIfNeeded((string) $value);
        }

        foreach ($updates as $key => $value) {
            if (isset($seenKeys[$key])) {
                continue;
            }

            $rows[] = ['raw' => $key . '=' . self::quoteIfNeeded((string) ($value ?? '')), 'key' => $key, 'value' => (string) ($value ?? ''), 'is_kv' => true];
        }

        return implode("\n", array_map(fn ($r) => $r['raw'], $rows)) . "\n";
    }

    public static function write(string $path, string $contents): void
    {
        $ok = file_put_contents($path, $contents);
        if ($ok === false) {
            throw new RuntimeException("Unable to write env file: {$path}");
        }
    }

    private static function stripInlineComment(string $value): string
    {
        $value = rtrim($value);
        if ($value === '') {
            return $value;
        }

        $first = $value[0];
        if ($first === '"' || $first === "'") {
            return $value;
        }

        $hashPos = strpos($value, '#');
        if ($hashPos === false) {
            return $value;
        }

        return rtrim(substr($value, 0, $hashPos));
    }

    private static function unquote(string $value): string
    {
        $len = strlen($value);
        if ($len >= 2) {
            $first = $value[0];
            $last = $value[$len - 1];
            if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                return substr($value, 1, -1);
            }
        }
        return $value;
    }

    private static function quoteIfNeeded(string $value): string
    {
        if ($value === '') {
            return '';
        }

        if (preg_match('/\s|#|=|"/', $value) === 1) {
            $escaped = str_replace('"', '\\"', $value);
            return '"' . $escaped . '"';
        }

        return $value;
    }
}

