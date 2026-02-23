# Visa Data Fix — Australian Passport Holders

## What This Fixes

Three critical data issues discovered during the accuracy audit:

### 1. Wrong `visa_type` Classifications
**Before:** ~48 European countries (France, Germany, Italy, Spain, etc.) were classified as `visa_required` instead of `visa_free`. The UK was classified as `evisa` instead of `visa_free`.

**After:** All 192 countries corrected to reflect Australian passport holder perspective.

### 2. Fake Template URLs
**Before:** ~175 countries had auto-generated fake URLs like `https://www.gov.alb/travel-visa` in the "Apply Now" section — these don't resolve to real websites.

**After:** Override countries get verified real government URLs. All other countries get their URL copied from `countries.official_url` (which has real URLs from `add_visa_urls.php`).

### 3. Generic Boilerplate Text
**Before:** ~175 countries shared identical template text regardless of actual visa requirements (e.g., all visa-free = "90 days within 180 days" even when the real duration differs).

**After:** 94 countries have fully researched, country-specific visa data. Remaining 98 use improved templates that don't fabricate data and direct users to check official sources.

## How to Run

```bash
php fix_visa_data_australian.php
```

The script runs three phases:
1. **Phase 1:** Corrects `visa_type` for all countries
2. **Phase 2:** Regenerates visa text data (94 overrides + 98 improved templates)
3. **Phase 3:** Copies `countries.official_url` → `country_translations.official_visa_url` for countries without override URLs

## Coverage

| Category | Countries |
|----------|-----------|
| Specific overrides | 94 (49%) |
| Improved templates | 98 (51%) |
| **Total** | **192** |

| Visa Type | Count |
|-----------|-------|
| visa_free | 120 |
| evisa | 38 |
| visa_on_arrival | 20 |
| visa_required | 14 |

## Key Corrections

| Country | Was | Now |
|---------|-----|-----|
| France, Germany, Italy, Spain (all Schengen) | visa_required | visa_free |
| United Kingdom | evisa | visa_free |
| Turkey | evisa | visa_free |
| Brazil | visa_required | visa_free |
| South Korea | evisa | visa_free |

## After Running

Run verification:
```bash
php task5_spot_check.php
```

Check a few country pages on the live site to confirm:
- France should say "visa-free for 90 days (Schengen)"
- "Apply Now" URLs should resolve to real government websites
- UK should show "6 months, visa-free"
