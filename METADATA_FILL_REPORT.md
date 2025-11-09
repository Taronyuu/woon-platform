# Funda Property Metadata Fill Report

## Summary

Successfully filled metadata and date fields for **7 Funda property units** from crawled page data.

## Properties Processed

### Property #9, #10, #11, #12, #13: Fernandelstraat 32, Almere
- **Title**: Fernandelstraat 32 (and "Funda Property" variants for sub-pages)
- **Source**: https://www.funda.nl/detail/koop/almere/huis-fernandelstraat-32/43146528/

### Property #14, #15: Lijmbeekstraat 222, Eindhoven
- **Title**: Lijmbeekstraat 222 (and "Funda Property" variant)
- **Source**: https://www.funda.nl/detail/koop/eindhoven/huis-lijmbeekstraat-222/89500146/

## Fields Successfully Filled

### ✅ description
- **Status**: Successfully filled for all 7 properties
- **Data Quality**: Complete property descriptions extracted from Funda metadata
- Properties #12 and #13 have full detailed descriptions
- Properties #9, #10, #11, #14, #15 have shorter page title descriptions

### ✅ amenities (JSON array)
- **Status**: Successfully filled for all 7 properties
- **Data Quality**: 10 amenities per property extracted from:
  - Voorzieningen (facilities)
  - Isolatie (insulation)
  - Verwarming (heating)
  - Badkamervoorzieningen (bathroom facilities)

**Fernandelstraat 32 amenities** (10 items):
1. Alarminstallatie
2. Dakraam
3. Glasvezelkabel
4. Mechanische ventilatie
5. Schuifpui
6. Stadsverwarming
7. Gedeeltelijke vloerverwarming
8. Douche
9. Toilet
10. Wastafelmeubel

**Lijmbeekstraat 222 amenities** (10 items):
1. Glasvezelkabel
2. Natuurlijke ventilatie
3. Rolluiken
4. Dubbel glas
5. Muurisolatie
6. Cv-ketel
7. Toilet
8. Wastafel
9. Wastafelmeubel
10. Zitbad

### ✅ data (JSON structured data)
- **Status**: Successfully filled for all 7 properties
- **Data Quality**: 8 fields of additional structured metadata

**Fields extracted** (8 items):
1. `house_type`: Type of house (e.g., "Eengezinswoning, hoekwoning")
2. `construction_type`: Construction status (e.g., "Bestaande bouw")
3. `roof_type`: Roof type (e.g., "Dwarskap bedekt met pannen")
4. `volume_m3`: Volume in cubic meters (460 m³ for Fernandelstraat, 453 m³ for Lijmbeekstraat)
5. `living_floors`: Number of living floors (3 for both)
6. `listing_status`: Current status (all "Beschikbaar")
7. `ownership_status`: Ownership situation (all "Volle eigendom")
8. `hot_water`: Hot water system (Stadsverwarming or Cv-ketel)

### ✅ listing_date
- **Status**: Successfully filled for all 7 properties
- **Value**: 2025-11-09 (extracted from "Op Funda" date on listing pages)
- **Data Quality**: Accurate listing date from Funda

### ✅ acceptance_date
- **Status**: Successfully filled for all 7 properties
- **Data Quality**: Mixed
  - Properties #12, #13: "februari 2026" (specific acceptance period)
  - Properties #9, #10, #11, #14, #15: "In overleg" (negotiable)

### ❌ viewing_date
- **Status**: NOT FILLED
- **Reason**: No viewing date information available in the crawled pages
- **Value**: NULL for all properties
- **Note**: Funda listings typically don't include pre-scheduled viewing dates in the page content

### ❌ last_changed_at
- **Status**: NOT FILLED
- **Reason**: No last modified timestamp in the crawled page metadata
- **Value**: NULL for all properties
- **Note**: This would require the Firecrawl metadata to include a `lastModified` field, which was not present

## Extraction Logic

The script (`fill_property_metadata.php`) extracted data from crawled pages using:

1. **Content parsing**: Regex patterns to extract structured data from Funda's HTML content
2. **Metadata parsing**: JSON metadata from Firecrawl crawler
3. **Smart URL handling**: Automatically finds the main property page when given sub-pages (kadaster, kaart, media, etc.)

### Patterns Used

- **Amenities**: `Voorzieningen`, `Isolatie`, `Verwarming`, `Badkamervoorzieningen` sections
- **Data fields**: `Soort woonhuis`, `Soort bouw`, `Soort dak`, `Inhoud`, `Aantal woonlagen`, etc.
- **Dates**:
  - Listing date: `(\d{1,2}-\d{1,2}-\d{4})\s*\n\s*Op Funda`
  - Acceptance: `Aanvaarding\s*\n\s*(.+)`

## Data Quality Notes

1. **Duplicate entries**: Properties #9-#13 are all the same property (Fernandelstraat 32) crawled from different URLs (main page, kadaster, kaart, print, media sub-pages). They all received the same metadata.

2. **Properties #14-#15**: Same situation for Lijmbeekstraat 222 (main page and media sub-page).

3. **Description completeness**: Properties linked to the main detail page (#12, #13) have complete descriptions. Others have shorter title-based descriptions.

4. **Missing fields**: `viewing_date` and `last_changed_at` could not be filled because this information was not available in the crawled page data.

## Recommendations

1. **Deduplicate property entries**: Consider merging duplicate entries (#9-#13 and #14-#15) into single records
2. **viewing_date**: This field may need to be populated from external sources or user input (not available on Funda listings)
3. **last_changed_at**: Could be tracked by monitoring future crawls and comparing content hashes
4. **Acceptance date parsing**: Consider parsing "februari 2026" into a structured date format if needed for searches/filtering

## Script Location

The extraction script is located at:
`/Users/zander/Documents/Workspace/wooon-nl/fill_property_metadata.php`

To re-run the script:
```bash
php fill_property_metadata.php
```
