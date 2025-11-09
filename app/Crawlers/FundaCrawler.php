<?php

namespace App\Crawlers;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FundaCrawler extends WebsiteCrawler
{
    public function getStartUrls(): array
    {
        return [
            'https://www.funda.nl/koop/heel-nederland/',
            'https://www.funda.nl/huur/heel-nederland/',
        ];
    }

    public function shouldCrawl(string $url): bool
    {
        if (!Str::startsWith($url, 'https://www.funda.nl')) {
            return false;
        }

        if (Str::contains($url, ['/hypotheek', '/nieuwbouw-service', '/mijn-funda'])) {
            return false;
        }

        return Str::contains($url, ['/koop/', '/huur/', '/detail/']);
    }

    public function extractLinks(string $content, string $pageUrl): Collection
    {
        $links = collect();

        if (empty($content)) {
            return $links;
        }

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();

        if (Str::contains($content, '<html')) {
            $dom->loadHTML($content);
        } else {
            $dom->loadHTML('<html><body>' . $content . '</body></html>');
        }

        libxml_clear_errors();

        $anchorTags = $dom->getElementsByTagName('a');
        foreach ($anchorTags as $anchor) {
            if ($anchor->hasAttribute('href')) {
                $href = $anchor->getAttribute('href');
                if (!empty($href)) {
                    $links->push($href);
                }
            }
        }

        preg_match_all('/\[([^\]]+)\]\(([^)]+)\)/', $content, $mdMatches);
        if (!empty($mdMatches[2])) {
            foreach ($mdMatches[2] as $url) {
                if (!empty($url)) {
                    $links->push($url);
                }
            }
        }

        return $links
            ->map(fn($url) => $this->normalizeUrl($url, $pageUrl))
            ->filter(fn($url) => !empty($url))
            ->filter(fn($url) => $this->shouldCrawl($url))
            ->unique()
            ->values();
    }

    public function parseData(string $content, string $url): array
    {
        if (!Str::contains($url, '/detail/')) {
            return [];
        }

        $htmlContent = $content;
        $canonicalUrl = $this->normalizePropertyUrl($url);

        $street = $this->extractStreet($htmlContent);
        $number = $this->extractNumber($htmlContent);
        $addition = $this->extractAddition($htmlContent);
        $city = $this->extractCity($htmlContent);

        $name = trim(implode(' ', array_filter([
            $street,
            $number,
            $addition,
            $city,
        ])));

        return [
            'source_url' => $canonicalUrl,
            'external_id' => $this->extractFundaId($canonicalUrl),
            'name' => $name,
            'title' => $this->extractTitle($htmlContent),
            'description' => $this->extractDescription($htmlContent),
            'property_type' => $this->extractPropertyType($htmlContent),
            'transaction_type' => Str::contains($url, '/koop/') ? 'sale' : 'rent',
            'living_type' => $this->extractLivingType($htmlContent),
            'buyprice' => $this->extractPrice($htmlContent),
            'buyprice_label' => $this->extractPriceLabel($htmlContent),
            'ground_lease' => $this->extractGroundLease($htmlContent),
            'address_street' => $street,
            'address_number' => $number,
            'address_addition' => $addition,
            'address_city' => $city,
            'address_postal_code' => $this->extractPostalCode($htmlContent),
            'address_province' => $this->extractProvince($htmlContent),
            'neighborhood' => $this->extractNeighborhood($htmlContent),
            'municipality' => $this->extractMunicipality($htmlContent),
            'surface' => $this->extractLivingArea($htmlContent),
            'lotsize' => $this->extractPlotArea($htmlContent),
            'volume' => $this->extractVolume($htmlContent),
            'outdoor_surface' => $this->extractOutdoorSurface($htmlContent),
            'bedrooms' => $this->extractRooms($htmlContent),
            'sleepingrooms' => $this->extractBedrooms($htmlContent),
            'bathrooms' => $this->extractBathrooms($htmlContent),
            'floors' => $this->extractFloors($htmlContent),
            'construction_year' => $this->extractYear($htmlContent),
            'energy_label' => $this->extractEnergyLabel($htmlContent),
            'orientation' => $this->extractOrientation($htmlContent),
            'parking_lots_data' => $this->extractParkingData($htmlContent),
            'storages_data' => $this->extractStorageData($htmlContent),
            'outdoor_spaces_data' => $this->extractOutdoorSpacesData($htmlContent),
            'images' => $this->extractImages($htmlContent),
            'videos' => $this->extractVideos($htmlContent),
            'floor_plans' => $this->extractFloorPlans($htmlContent),
            'virtual_tour_url' => $this->extractVirtualTourUrl($htmlContent),
            'features' => $this->extractFeatures($htmlContent),
            'amenities' => $this->extractAmenities($htmlContent),
            'data' => $this->extractAdditionalData($htmlContent),
            'agent_name' => $this->extractAgentName($htmlContent),
            'agent_company' => $this->extractAgentCompany($htmlContent),
            'agent_phone' => $this->extractAgentPhone($htmlContent),
            'listing_date' => $this->extractListingDate($htmlContent),
            'acceptance_date' => $this->extractAcceptanceDate($htmlContent),
            'first_seen_at' => now(),
            'last_seen_at' => now(),
        ];
    }

    private function normalizeUrl(string $url, string $baseUrl): string
    {
        $url = trim($url);

        if (empty($url)) {
            return '';
        }

        if (Str::startsWith($url, ['javascript:', 'mailto:', 'tel:', '#'])) {
            return '';
        }

        $normalized = '';

        if (Str::startsWith($url, 'http')) {
            $normalized = $url;
        } elseif (Str::startsWith($url, '//')) {
            $normalized = 'https:' . $url;
        } elseif (Str::startsWith($url, '/')) {
            $parsed = parse_url($baseUrl);
            $normalized = ($parsed['scheme'] ?? 'https') . '://' . ($parsed['host'] ?? 'www.funda.nl') . $url;
        } else {
            $parsed = parse_url($baseUrl);
            $basePath = dirname($parsed['path'] ?? '/');
            $normalized = ($parsed['scheme'] ?? 'https') . '://' . ($parsed['host'] ?? 'www.funda.nl') . rtrim($basePath, '/') . '/' . $url;
        }

        $parsed = parse_url($normalized);
        if ($parsed === false) {
            return '';
        }

        $clean = ($parsed['scheme'] ?? 'https') . '://' . ($parsed['host'] ?? '');
        if (!empty($parsed['path'])) {
            $clean .= $parsed['path'];
        }
        if (!empty($parsed['query'])) {
            $clean .= '?' . $parsed['query'];
        }

        return $clean;
    }

    private function extractFundaId(string $url): ?string
    {
        if (preg_match('/\/(\d+)-/', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    private function extractPrice(string $content): ?float
    {
        if (preg_match('/€\s*([\d.]+)(?:\s*k\.k\.)?/i', $content, $matches)) {
            return (float) str_replace('.', '', $matches[1]);
        }
        return null;
    }

    private function extractTitle(string $content): string
    {
        if (preg_match('/<h1[^>]*data-global-id[^>]*>.*?<span[^>]*>(.*?)<\/span>/s', $content, $matches)) {
            return trim(strip_tags($matches[1]));
        }
        if (preg_match('/<h1[^>]*>(.*?)<\/h1>/s', $content, $matches)) {
            $title = strip_tags($matches[1]);
            $title = preg_replace('/\d{4}\s*[A-Z]{2}\s+.*/s', '', $title);
            return trim($title);
        }
        return 'Funda Property';
    }

    private function extractDescription(string $content): ?string
    {
        if (preg_match('/<h2[^>]*>Omschrijving<\/h2>.*?<div[^>]*><!--\[-->(.+?)<!--\]--><\/div>/s', $content, $matches)) {
            $description = trim(strip_tags($matches[1]));
            $description = preg_replace('/\s+/', ' ', $description);
            return $description;
        }
        if (preg_match('/<div[^>]*class="[^"]*description[^"]*"[^>]*>(.*?)<\/div>/s', $content, $matches)) {
            return trim(strip_tags($matches[1]));
        }
        return null;
    }

    private function extractPropertyType(string $content): string
    {
        if (Str::contains(strtolower($content), ['appartement', 'flat'])) {
            return 'apartment';
        }
        if (Str::contains(strtolower($content), ['villa'])) {
            return 'villa';
        }
        return 'house';
    }

    private function extractStreet(string $content): ?string
    {
        if (preg_match('/<h1[^>]*data-global-id[^>]*>.*?<span[^>]*>(.*?)\s+\d+/s', $content, $matches)) {
            return trim(strip_tags($matches[1]));
        }
        if (preg_match('/([A-Za-z\s]+)\s+\d+/', $content, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    private function extractNumber(string $content): ?string
    {
        if (preg_match('/<h1[^>]*data-global-id[^>]*>.*?<span[^>]*>.*?\s+(\d+)/s', $content, $matches)) {
            return $matches[1];
        }
        if (preg_match('/([A-Za-z\s]+)\s+(\d+)/', $content, $matches)) {
            return $matches[2];
        }
        return null;
    }

    private function extractAddition(string $content): ?string
    {
        if (preg_match('/<h1[^>]*data-global-id[^>]*>.*?<span[^>]*>.*?\s+\d+\s*([A-Za-z\-]+)/s', $content, $matches)) {
            return trim($matches[1]);
        }
        if (preg_match('/([A-Za-z\s]+)\s+\d+([A-Za-z\-]+)/', $content, $matches)) {
            return trim($matches[2]);
        }
        return null;
    }

    private function extractCity(string $content): ?string
    {
        if (preg_match('/<h1[^>]*data-global-id[^>]*>.*?<span[^>]*class="text-neutral-40"[^>]*>.*?\d{4}\s*[A-Z]{2}\s+([^<]+)/s', $content, $matches)) {
            return trim(strip_tags($matches[1]));
        }
        if (preg_match('/\d{4}\s*[A-Z]{2}\s+([A-Za-z\s]+)/', $content, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    private function extractPostalCode(string $content): ?string
    {
        if (preg_match('/<h1[^>]*data-global-id[^>]*>.*?<span[^>]*class="text-neutral-40"[^>]*>.*?(\d{4}\s*[A-Z]{2})/s', $content, $matches)) {
            return str_replace(' ', '', $matches[1]);
        }
        if (preg_match('/(\d{4}\s*[A-Z]{2})/', $content, $matches)) {
            return str_replace(' ', '', $matches[1]);
        }
        return null;
    }

    private function extractLivingArea(string $content): ?int
    {
        if (preg_match('/>Wonen<\/dt>.*?<dd[^>]*>.*?(\d+)\s*m²/is', $content, $matches)) {
            return (int) $matches[1];
        }
        if (preg_match('/(\d+)\s*m²\s*wonen/i', $content, $matches)) {
            return (int) $matches[1];
        }
        if (preg_match('/<span[^>]*>(\d+)\s*m²<\/span>\s*<span[^>]*>wonen/i', $content, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    private function extractPlotArea(string $content): ?int
    {
        if (preg_match('/>Perceel<\/dt>.*?<dd[^>]*>.*?(\d+)\s*m²/is', $content, $matches)) {
            return (int) $matches[1];
        }
        if (preg_match('/(\d+)\s*m²\s*perceel/i', $content, $matches)) {
            return (int) $matches[1];
        }
        if (preg_match('/<span[^>]*>(\d+)\s*m²<\/span>\s*<span[^>]*>perceel/i', $content, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    private function extractRooms(string $content): ?int
    {
        if (preg_match('/(\d+)\s*kamers?/i', $content, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    private function extractBedrooms(string $content): ?int
    {
        if (preg_match('/>Aantal slaapkamers<\/dt>.*?<dd[^>]*>.*?(\d+)/is', $content, $matches)) {
            return (int) $matches[1];
        }
        if (preg_match('/(\d+)\s*slaapkamers?/i', $content, $matches)) {
            return (int) $matches[1];
        }
        if (preg_match('/<span[^>]*>(\d+)<\/span>\s*<span[^>]*>slaapkamers/i', $content, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    private function extractBathrooms(string $content): ?int
    {
        if (preg_match('/>Aantal badkamers<\/dt>.*?<dd[^>]*>.*?(\d+)/is', $content, $matches)) {
            return (int) $matches[1];
        }
        if (preg_match('/(\d+)\s*badkamers?/i', $content, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    private function extractYear(string $content): ?int
    {
        if (preg_match('/>Bouwjaar<\/dt>.*?<dd[^>]*>.*?(19\d{2}|20\d{2})/is', $content, $matches)) {
            return (int) $matches[1];
        }
        if (preg_match('/bouwjaar[^\d]*(19\d{2}|20\d{2})/i', $content, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    private function extractEnergyLabel(string $content): ?string
    {
        if (preg_match('/<span[^>]*>([A-G]\+*)<\/span>\s*<span[^>]*>energielabel/i', $content, $matches)) {
            return strtoupper($matches[1]);
        }
        if (preg_match('/energielabel[^\w]*([A-G]\+*)/i', $content, $matches)) {
            return strtoupper($matches[1]);
        }
        return null;
    }

    private function extractImages(string $content): array
    {
        preg_match_all('/<img[^>]+src="([^"]+)"/', $content, $matches);
        return array_filter($matches[1], fn($url) =>
            Str::contains($url, ['funda']) &&
            !Str::contains($url, ['logo', 'icon'])
        );
    }

    private function extractFeatures(string $content): array
    {
        $features = [];

        if (Str::contains(strtolower($content), ['garage'])) {
            $features[] = 'Garage';
        }
        if (Str::contains(strtolower($content), ['tuin'])) {
            $features[] = 'Tuin';
        }
        if (Str::contains(strtolower($content), ['balkon'])) {
            $features[] = 'Balkon';
        }
        if (Str::contains(strtolower($content), ['parkeer'])) {
            $features[] = 'Parkeerplaats';
        }

        return $features;
    }

    private function extractAgentName(string $content): ?string
    {
        if (preg_match('/makelaar[^\w]*([A-Za-z\s&]+)/i', $content, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    private function extractLivingType(string $content): ?string
    {
        if (preg_match('/>Soort woonhuis<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $type = strip_tags($matches[1]);
            if (Str::contains(strtolower($type), 'eengezinswoning')) {
                return 'woonhuis';
            }
            if (Str::contains(strtolower($type), 'appartement')) {
                return 'appartement';
            }
        }
        return null;
    }

    private function extractPriceLabel(string $content): ?string
    {
        if (preg_match('/€\s*[\d.]+\s*([a-z.\s]+)/i', $content, $matches)) {
            $label = strtolower(trim($matches[1]));
            if (Str::contains($label, 'k.k') || Str::contains($label, 'kosten koper')) {
                return 'kosten koper';
            }
            if (Str::contains($label, 'v.o.n') || Str::contains($label, 'vrij op naam')) {
                return 'vrij op naam';
            }
        }
        return null;
    }

    private function extractGroundLease(string $content): ?int
    {
        if (preg_match('/>Eigendomssituatie<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $ownership = strtolower(strip_tags($matches[1]));
            if (Str::contains($ownership, 'erfpacht') || Str::contains($ownership, 'ground lease')) {
                return 1;
            }
            if (Str::contains($ownership, 'volle eigendom') || Str::contains($ownership, 'full ownership')) {
                return 0;
            }
        }
        return null;
    }

    private function extractProvince(string $content): ?string
    {
        if (preg_match('/province="([^"]+)"/i', $content, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    private function extractNeighborhood(string $content): ?string
    {
        if (preg_match('/neighborhoodidentifier="([^"]+)"/i', $content, $matches)) {
            $neighborhood = $matches[1];
            $neighborhood = preg_replace('/^.*?-(.+)$/', '$1', $neighborhood);
            $neighborhood = str_replace('-', ' ', $neighborhood);
            return ucwords($neighborhood);
        }
        return null;
    }

    private function extractMunicipality(string $content): ?string
    {
        if (preg_match('/city="([^"]+)"/i', $content, $matches)) {
            return trim($matches[1]);
        }
        return $this->extractCity($content);
    }

    private function extractVolume(string $content): ?int
    {
        if (preg_match('/>Inhoud<\/dt>.*?<dd[^>]*>.*?(\d+)\s*m³/is', $content, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    private function extractOutdoorSurface(string $content): ?int
    {
        if (preg_match('/>Gebouwgebonden buitenruimte<\/dt>.*?<dd[^>]*>.*?(\d+)\s*m²/is', $content, $matches)) {
            $gebouwgebonden = (int) $matches[1];
        } else {
            $gebouwgebonden = 0;
        }

        if (preg_match('/>Externe bergruimte<\/dt>.*?<dd[^>]*>.*?(\d+)\s*m²/is', $content, $matches)) {
            $externe = (int) $matches[1];
        } else {
            $externe = 0;
        }

        $total = $gebouwgebonden + $externe;
        return $total > 0 ? $total : null;
    }

    private function extractFloors(string $content): ?int
    {
        if (preg_match('/>Aantal woonlagen<\/dt>.*?<dd[^>]*>.*?(\d+)/is', $content, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    private function extractOrientation(string $content): ?string
    {
        if (preg_match('/>Ligging tuin<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $orientation = strip_tags($matches[1]);
            if (preg_match('/(noord|oost|zuid|west)/i', $orientation, $dirMatch)) {
                return ucfirst(strtolower($dirMatch[1]));
            }
        }
        return null;
    }

    private function extractParkingData(string $content): ?array
    {
        if (preg_match('/>Soort parkeergelegenheid<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            return ['type' => strip_tags($matches[1])];
        }
        return null;
    }

    private function extractStorageData(string $content): ?array
    {
        $storage = [];

        if (preg_match('/>Soort berging<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $storage['type'] = strip_tags($matches[1]);
        }

        if (preg_match('/>Voorzieningen berging<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $storage['facilities'] = strip_tags($matches[1]);
        }

        return !empty($storage) ? $storage : null;
    }

    private function extractOutdoorSpacesData(string $content): ?array
    {
        $outdoor = [];

        if (preg_match('/>Ligging tuin<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $outdoor['location'] = strip_tags($matches[1]);
        }

        if (preg_match('/>Soort tuin<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $outdoor['garden_types'] = strip_tags($matches[1]);
        }

        if (preg_match('/>Achtertuin<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $outdoor['back_garden'] = strip_tags($matches[1]);
        }

        if (preg_match('/>Ligging tuin<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $outdoor['garden_orientation'] = strip_tags($matches[1]);
        }

        return !empty($outdoor) ? $outdoor : null;
    }

    private function extractVideos(string $content): ?array
    {
        preg_match_all('/https:\/\/customer-[^"]+\.cloudflarestream\.com\/[^"\/]+\/manifest\/video\.m3u8/', $content, $matches);
        return !empty($matches[0]) ? array_unique($matches[0]) : null;
    }

    private function extractFloorPlans(string $content): ?array
    {
        preg_match_all('/https:\/\/cloud\.funda\.nl\/[^"]+\.png/', $content, $matches);
        $plans = array_filter($matches[0], fn($url) => !Str::contains($url, 'logo'));
        return !empty($plans) ? array_values(array_unique($plans)) : null;
    }

    private function extractVirtualTourUrl(string $content): ?string
    {
        if (preg_match('/https:\/\/www\.funda\.nl\/detail\/[^"]+\/media\/360-foto\/[^"]+/', $content, $matches)) {
            return $matches[0];
        }
        return null;
    }

    private function extractAmenities(string $content): ?array
    {
        $amenities = [];

        if (preg_match('/>Voorzieningen<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $items = explode(',', strip_tags($matches[1]));
            foreach ($items as $item) {
                $amenities[] = trim($item);
            }
        }

        if (preg_match('/>Isolatie<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $items = explode(',', strip_tags($matches[1]));
            foreach ($items as $item) {
                $amenities[] = trim($item);
            }
        }

        if (preg_match('/>Verwarming<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $items = explode(',', strip_tags($matches[1]));
            foreach ($items as $item) {
                $amenities[] = trim($item);
            }
        }

        if (preg_match('/>Badkamervoorzieningen<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $items = explode(',', strip_tags($matches[1]));
            foreach ($items as $item) {
                $amenities[] = trim($item);
            }
        }

        return !empty($amenities) ? array_unique($amenities) : null;
    }

    private function extractAdditionalData(string $content): ?array
    {
        $data = [];

        if (preg_match('/>Soort woonhuis<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $data['house_type'] = strip_tags($matches[1]);
        }

        if (preg_match('/>Soort bouw<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $data['construction_type'] = strip_tags($matches[1]);
        }

        if (preg_match('/>Soort dak<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $data['roof_type'] = strip_tags($matches[1]);
        }

        if (preg_match('/>Inhoud<\/dt>.*?<dd[^>]*>.*?(\d+)\s*m³/is', $content, $matches)) {
            $data['volume_m3'] = (int) $matches[1];
        }

        if (preg_match('/>Aantal woonlagen<\/dt>.*?<dd[^>]*>.*?(\d+)/is', $content, $matches)) {
            $data['living_floors'] = (int) $matches[1];
        }

        if (preg_match('/>Status<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $data['listing_status'] = strip_tags($matches[1]);
        }

        if (preg_match('/>Eigendomssituatie<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $data['ownership_status'] = strip_tags($matches[1]);
        }

        if (preg_match('/>Warm water<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $data['hot_water'] = strip_tags($matches[1]);
        }

        return !empty($data) ? $data : null;
    }

    private function extractAgentCompany(string $content): ?string
    {
        if (preg_match('/###\s*\[([^\]]+)\]\(https:\/\/www\.funda\.nl\/makelaar\/\d+/i', $content, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    private function extractAgentPhone(string $content): ?string
    {
        if (preg_match('/Bel\s+(\+?[0-9\s\-]{10,})/i', $content, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    private function extractListingDate(string $content): ?string
    {
        if (preg_match('/>Op Funda<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            $dateStr = strip_tags($matches[1]);
            try {
                return \Carbon\Carbon::parse($dateStr)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    private function extractAcceptanceDate(string $content): ?string
    {
        if (preg_match('/>Aanvaarding<\/dt>.*?<dd[^>]*>(.*?)<\/dd>/is', $content, $matches)) {
            return strip_tags($matches[1]);
        }
        return null;
    }

    private function normalizePropertyUrl(string $url): string
    {
        if (preg_match('/(https:\/\/www\.funda\.nl\/detail\/[^\/]+\/[^\/]+\/[^\/]+\/\d+)/', $url, $matches)) {
            return $matches[1];
        }
        return $url;
    }
}
