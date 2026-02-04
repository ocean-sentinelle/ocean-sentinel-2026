<?php

declare(strict_types=1);

if (defined('OS_ARAG_LIMIT') && OS_ARAG_LIMIT !== 1.75) {
    throw new RuntimeException('OS_ARAG_LIMIT is immutable and must remain 1.75.');
}
if (defined('OS_ARAG_VIGILANCE') && OS_ARAG_VIGILANCE !== 1.80) {
    throw new RuntimeException('OS_ARAG_VIGILANCE is immutable and must remain 1.80.');
}

define('OS_ARAG_LIMIT', 1.75);
define('OS_ARAG_VIGILANCE', 1.80);

define('OS_SENTINEL_DATA_CSV', WP_CONTENT_DIR . '/sentinel-data/data.csv');

define('OS_OCEAN_DATA_TRANSIENT', 'os_latest_ocean_data_v1');

define('OS_OCEAN_DATA_TTL', 15 * MINUTE_IN_SECONDS);

add_filter('cron_schedules', static function (array $schedules): array {
    if (!isset($schedules['os_15min'])) {
        $schedules['os_15min'] = [
            'interval' => 15 * MINUTE_IN_SECONDS,
            'display' => 'Ocean Sentinel: every 15 minutes',
        ];
    }

    if (!isset($schedules['os_14days'])) {
        $schedules['os_14days'] = [
            'interval' => 14 * DAY_IN_SECONDS,
            'display' => 'Ocean Sentinel: every 14 days',
        ];
    }

    return $schedules;
});

add_action('init', static function (): void {
    if (!wp_next_scheduled('os_sync_ocean_data_event_15min')) {
        wp_schedule_event(time() + 60, 'os_15min', 'os_sync_ocean_data_event_15min');
    }

    if (!wp_next_scheduled('os_sync_ocean_data_event_14days')) {
        wp_schedule_event(time() + 120, 'os_14days', 'os_sync_ocean_data_event_14days');
    }
});

add_action('os_sync_ocean_data_event_15min', 'os_sync_ocean_data');
add_action('os_sync_ocean_data_event_14days', 'os_sync_ocean_data');

function os_get_latest_ocean_data(bool $allow_sync_if_stale = true): array
{
    $cached = get_transient(OS_OCEAN_DATA_TRANSIENT);

    $csv_mtime = @filemtime(OS_SENTINEL_DATA_CSV);

    if (is_array($cached)) {
        $cached_mtime = $cached['csv_mtime'] ?? null;
        if (is_int($csv_mtime) && is_int($cached_mtime) && $csv_mtime === $cached_mtime) {
            return $cached;
        }

        if (!$allow_sync_if_stale) {
            return $cached;
        }
    }

    if ($allow_sync_if_stale) {
        os_sync_ocean_data();
        $cached_after = get_transient(OS_OCEAN_DATA_TRANSIENT);
        if (is_array($cached_after)) {
            return $cached_after;
        }
    }

    return [
        'ok' => false,
        'source' => 'none',
        'timestamp' => null,
        'ph' => null,
        'temp' => null,
        'salinity' => null,
        'omega_arag' => null,
        'state' => 'unknown',
        'csv_mtime' => is_int($csv_mtime) ? $csv_mtime : null,
    ];
}

function os_get_ocean_state(?float $omega_arag): string
{
    if ($omega_arag === null) {
        return 'unknown';
    }

    if ($omega_arag < OS_ARAG_LIMIT) {
        return 'critical';
    }

    if ($omega_arag < OS_ARAG_VIGILANCE) {
        return 'vigilance';
    }

    return 'normal';
}

function os_sync_ocean_data(): void
{
    $csv_path = OS_SENTINEL_DATA_CSV;

    if (!is_readable($csv_path)) {
        set_transient(OS_OCEAN_DATA_TRANSIENT, [
            'ok' => false,
            'source' => 'csv',
            'error' => 'csv_not_readable',
            'timestamp' => null,
            'ph' => null,
            'temp' => null,
            'salinity' => null,
            'omega_arag' => null,
            'state' => 'unknown',
            'csv_mtime' => null,
        ], OS_OCEAN_DATA_TTL);
        return;
    }

    $handle = fopen($csv_path, 'rb');
    if ($handle === false) {
        return;
    }

    $headers = fgetcsv($handle);
    if (!is_array($headers)) {
        fclose($handle);
        return;
    }

    $header_map = [];
    foreach ($headers as $i => $name) {
        $key = strtolower(trim((string) $name));
        $header_map[$key] = $i;
    }

    $last_row = null;
    while (($row = fgetcsv($handle)) !== false) {
        if (!is_array($row) || count($row) === 0) {
            continue;
        }
        $last_row = $row;
    }

    fclose($handle);

    if (!is_array($last_row)) {
        return;
    }

    $timestamp = os_csv_value($last_row, $header_map, 'timestamp');
    $ph = os_csv_float($last_row, $header_map, 'ph');
    $temp = os_csv_float($last_row, $header_map, 'temp');
    $salinity = os_csv_float($last_row, $header_map, 'salinity');

    $omega_arag = os_csv_float($last_row, $header_map, 'omega_arag');

    $csv_mtime = @filemtime($csv_path);

    $ok = $omega_arag !== null;
    $error = $ok ? null : 'missing_omega_arag';

    $payload = [
        'ok' => $ok,
        'source' => 'csv',
        'error' => $error,
        'timestamp' => $timestamp,
        'ph' => $ph,
        'temp' => $temp,
        'salinity' => $salinity,
        'omega_arag' => $omega_arag,
        'state' => os_get_ocean_state($omega_arag),
        'csv_mtime' => is_int($csv_mtime) ? $csv_mtime : null,
    ];

    set_transient(OS_OCEAN_DATA_TRANSIENT, $payload, OS_OCEAN_DATA_TTL);
}

function os_csv_value(array $row, array $header_map, string $key): ?string
{
    if (!array_key_exists($key, $header_map)) {
        return null;
    }

    $i = $header_map[$key];
    $raw = $row[$i] ?? null;

    if ($raw === null) {
        return null;
    }

    $raw = trim((string) $raw);
    return $raw === '' ? null : $raw;
}

function os_csv_float(array $row, array $header_map, string $key): ?float
{
    $raw = os_csv_value($row, $header_map, $key);
    if ($raw === null) {
        return null;
    }

    $raw = str_replace(',', '.', $raw);
    if (!is_numeric($raw)) {
        return null;
    }

    return (float) $raw;
}
