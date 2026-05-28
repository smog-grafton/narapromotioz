<?php

namespace App\Support;

use Illuminate\Http\Request;

class FrontendUrlResolver
{
    public static function allowedFrontends(): array
    {
        $configured = array_filter(array_map(
            static fn (string $item): string => trim($item),
            explode(',', (string) env('FRONTEND_URLS', ''))
        ));

        $fallback = trim((string) env('FRONTEND_URL', 'http://localhost:3000'));

        if ($fallback !== '') {
            $configured[] = $fallback;
        }

        $normalized = array_map([self::class, 'normalizeUrl'], $configured);
        $normalized = array_values(array_unique(array_filter($normalized)));

        return $normalized !== [] ? $normalized : ['http://localhost:3000'];
    }

    public static function fallbackFrontend(): string
    {
        return self::allowedFrontends()[0];
    }

    public static function isAllowed(?string $url): bool
    {
        if (! $url) {
            return false;
        }

        $normalized = self::normalizeUrl($url);

        return $normalized !== null && in_array($normalized, self::allowedFrontends(), true);
    }

    public static function resolveFromRequest(Request $request): string
    {
        $candidates = [
            $request->input('frontend'),
            $request->query('frontend'),
            $request->header('origin'),
            $request->header('referer'),
        ];

        foreach ($candidates as $candidate) {
            $normalized = self::normalizeUrl(is_string($candidate) ? $candidate : null);

            if ($normalized !== null && self::isAllowed($normalized)) {
                return $normalized;
            }
        }

        return self::fallbackFrontend();
    }

    public static function normalizeUrl(?string $value): ?string
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        $value = trim($value);

        if (! str_starts_with($value, 'http://') && ! str_starts_with($value, 'https://')) {
            return null;
        }

        $parts = parse_url($value);

        if (! is_array($parts) || empty($parts['scheme']) || empty($parts['host'])) {
            return null;
        }

        $port = isset($parts['port']) ? ':' . $parts['port'] : '';

        return strtolower($parts['scheme']) . '://' . strtolower($parts['host']) . $port;
    }
}
