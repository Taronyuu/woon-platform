<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageProxyController extends Controller
{
    private const ALLOWED_DOMAINS = [
        'funda.nl',
        'cloud.funda.nl',
    ];

    private const CONTENT_TYPE_MAP = [
        'image/jpeg' => 'jpg',
        'image/jpg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];

    public function show(string $token): Response
    {
        try {
            $url = decrypt($token);
        } catch (\Exception) {
            abort(404);
        }

        if (!$this->isAllowedDomain($url)) {
            abort(404);
        }

        $hash = md5($url);
        $existingFile = $this->findCachedFile($hash);

        if ($existingFile) {
            return $this->serveFile($existingFile);
        }

        return $this->downloadAndServe($url, $hash);
    }

    private function isAllowedDomain(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);

        if (!$host) {
            return false;
        }

        foreach (self::ALLOWED_DOMAINS as $domain) {
            if ($host === $domain || Str::endsWith($host, '.' . $domain)) {
                return true;
            }
        }

        return false;
    }

    private function findCachedFile(string $hash): ?string
    {
        $extensions = ['jpg', 'png', 'gif', 'webp'];

        foreach ($extensions as $ext) {
            $path = "proxied-images/{$hash}.{$ext}";
            if (Storage::disk('public')->exists($path)) {
                return $path;
            }
        }

        return null;
    }

    private function downloadAndServe(string $url, string $hash): Response
    {
        try {
            $response = Http::timeout(10)->get($url);

            if (!$response->successful()) {
                abort(404);
            }

            $contentType = $response->header('Content-Type');
            $extension = $this->getExtensionFromContentType($contentType);
            $path = "proxied-images/{$hash}.{$extension}";

            Storage::disk('public')->put($path, $response->body());

            return $this->serveFile($path);
        } catch (\Exception) {
            abort(404);
        }
    }

    private function getExtensionFromContentType(?string $contentType): string
    {
        if (!$contentType) {
            return 'jpg';
        }

        $contentType = strtolower(explode(';', $contentType)[0]);

        return self::CONTENT_TYPE_MAP[$contentType] ?? 'jpg';
    }

    private function serveFile(string $path): Response
    {
        $content = Storage::disk('public')->get($path);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $contentType = $this->getContentTypeFromExtension($extension);

        return response($content)
            ->header('Content-Type', $contentType)
            ->header('Cache-Control', 'public, max-age=31536000');
    }

    private function getContentTypeFromExtension(string $extension): string
    {
        return match ($extension) {
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => 'image/jpeg',
        };
    }
}
