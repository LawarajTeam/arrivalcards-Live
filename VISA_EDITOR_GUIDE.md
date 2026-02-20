# Visa Editor Guide — ArrivalCards

> Complete reference for editors adding or updating visa and country data in the ArrivalCards system.

---

## Table of Contents

1. [Database Schema](#1-database-schema)
2. [Active Languages](#2-active-languages)
3. [Admin Workflow](#3-admin-workflow)
4. [Field Reference — `country_translations`](#4-field-reference--country_translations)
5. [Field Reference — `countries`](#5-field-reference--countries)
6. [Field Reference — `country_details`](#6-field-reference--country_details)
7. [How the Country Page Displays Data](#7-how-the-country-page-displays-data)
8. [Data Validation Rules](#8-data-validation-rules)
9. [Official Visa URL Guidelines](#9-official-visa-url-guidelines)
10. [Data Entry Best Practices](#10-data-entry-best-practices)
11. [Example Data Entry](#11-example-data-entry)

---

## 1. Database Schema

The visa system uses four key tables:

| Table | Purpose |
|---|---|
| `countries` | Core country record (code, visa type, region, general info) |
| `country_translations` | Per-language visa/entry content (one row per country × language) |
| `country_details` | Per-language description, known-for, travel tips |
| `languages` | Definition of the 5 active site languages |
| `country_views` | Page-view tracking |
| `country_feedback` | User helpful/not-helpful votes |
| `bilateral_visa_requirements` | Passport-specific (personalized) visa rules |

### Entity Relationships

```
countries (1) ──→ (N) country_translations  (via country_id + lang_code)
countries (1) ──→ (N) country_details       (via country_id + lang_code)
countries (1) ──→ (N) airports              (via country_id)
countries (1) ──→ (1) country_views         (via country_id)
countries (1) ──→ (N) country_feedback      (via country_id)
languages (1) ──→ (N) country_translations  (via lang_code)
```

---

## 2. Active Languages

The site supports **5 active languages**. Every country should have at minimum an **English (en)** translation. The remaining four are optional but recommended.

| Code | Language | Native Name | Flag | Display Order |
|------|----------|-------------|------|---------------|
| `en` | English  | English     | 🇬🇧  | 1 |
| `es` | Spanish  | Español     | 🇪🇸  | 2 |
| `zh` | Chinese  | 中文        | 🇨🇳  | 3 |
| `fr` | French   | Français    | 🇫🇷  | 4 |
| `de` | German   | Deutsch     | 🇩🇪  | 5 |

Languages are stored in the `languages` table and fetched via `getLanguages()` in `includes/functions.php`.

---

## 3. Admin Workflow

### Accessing the Admin Panel

- URL: `/admin/` (requires authentication via `requireAdmin()`)
- Country list: `/admin/countries.php`

### Adding a New Country

1. Navigate to **Admin → Countries → Add New Country** (`/admin/add_country.php`)
2. Fill in **Core Country Information** (country code, flag, region, visa type, official URL)
3. Fill in **Country Details** (capital, population, currency, etc.)
4. Switch between **language tabs** (English, Spanish, Chinese, French, German) to enter translations
5. For each language tab, fill in visa information fields
6. Click **Save** — the system wraps everything in a database transaction

### Editing an Existing Country

1. Navigate to **Admin → Countries** → click **Edit** on a country (`/admin/edit_country.php?id=XX`)
2. The form is organized into three sections:
   - **Core Country Information** — code, flag, region, visa type, official URL, active/popular toggles
   - **Country Details** — capital, population, currency, plug type, leader, timezone, calling code, languages spoken
   - **Translations & Visa Information** — tabbed interface with one tab per language
3. Each language tab contains:
   - Country Name, Entry Summary, Visa Requirements (detailed)
   - Visa Duration, Visa Fee, Processing Time
   - Passport Validity, Arrival Card Required (dropdown: Yes/No/Online only)
   - Official Visa URL (per-language, can differ from the main country URL)
   - Additional Documents (free-text)
   - Country Details sub-section: Description, Known For, Travel Tips
4. Click **Save Changes** — all updates run in a single transaction with audit logging

### Key Behaviors

- **English is required**: The English country name (`translations[en][country_name]`) is mandatory.
- **Non-English translations are optional**: If a non-English country name is left blank, the translation row is deleted.
- **Empty country_details are cleaned up**: If description, known_for, and travel_tips are all empty for a language, the row is removed.
- **`last_verified` auto-updates**: Set to `CURDATE()` on every save.
- **`last_updated` auto-updates**: Set to `CURDATE()` on the countries table.
- **CSRF protection**: Every form includes a CSRF token.
- **Audit logging**: All create/update actions are logged via `logAdminAction()`.

---

## 4. Field Reference — `country_translations`

This is the primary table for visa editor content. One row per country per language.

| Column | Type | Constraints | Description | Example Values |
|--------|------|-------------|-------------|----------------|
| `id` | INT AUTO_INCREMENT | PRIMARY KEY | Row identifier | — |
| `country_id` | INT | NOT NULL, FK → countries(id) ON DELETE CASCADE | Links to parent country | `42` |
| `lang_code` | VARCHAR(5) | NOT NULL, FK → languages(code) ON DELETE CASCADE | Language code | `en`, `es`, `zh`, `fr`, `de` |
| `country_name` | VARCHAR(100) | NOT NULL | Country name in this language | `France`, `Francia`, `法国` |
| `entry_summary` | TEXT | NOT NULL | Brief overview of entry requirements. Displayed as the main description on the country page. | `Most nationalities can enter France visa-free for up to 90 days within a 180-day period as part of the Schengen Agreement.` |
| `visa_requirements` | TEXT | DEFAULT NULL | Detailed visa information. Shown under "Requirements Details" heading. | `Citizens of EU/EEA countries do not need a visa. Others may need a Schengen visa...` |
| `visa_duration` | VARCHAR(100) | DEFAULT NULL | Maximum stay allowed | `90 days`, `30 days`, `6 months` |
| `passport_validity` | VARCHAR(100) | DEFAULT NULL | How long the passport must be valid | `6 months beyond stay`, `3 months after departure`, `Valid for duration of stay` |
| `visa_fee` | VARCHAR(100) | DEFAULT NULL | Cost of the visa | `Free`, `$50 USD`, `€80`, `$160 USD (tourist)` |
| `processing_time` | VARCHAR(100) | DEFAULT NULL | How long processing takes | `Instant`, `3-5 business days`, `15 calendar days`, `2-4 weeks` |
| `official_visa_url` | VARCHAR(500) | DEFAULT NULL | Official government visa portal URL (per-language) | `https://france-visas.gouv.fr/en/` |
| `arrival_card_required` | VARCHAR(50) | DEFAULT NULL | Whether an arrival/departure card is needed | `Yes`, `No`, `Online only` |
| `additional_docs` | TEXT | DEFAULT NULL | Required documents or important notes. Supports structured formatting (see below). | See [Additional Docs Format](#additional-docs-format) |
| `last_verified` | DATE | DEFAULT NULL | Date content was last verified. Auto-set to current date on admin save. | `2026-02-20` |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Row creation timestamp | — |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE | Last modification timestamp | — |

**Unique Constraint**: `(country_id, lang_code)` — one translation per country per language.

### Additional Docs Format

The `additional_docs` field is rendered with intelligent formatting on the country page. Use this structure:

```
HEADING IN ALL CAPS: Content goes here

ANOTHER HEADING: More content
- Bullet point 1
- Bullet point 2

CRITICAL WARNING: This will render with red/warning styling
- Any heading containing CRITICAL, WARNING, RISK, PROHIBITED, or SEVERE gets red styling

REGULAR SECTION: This gets blue/info styling
- Item one
- Item two
```

**Formatting rules applied in `country.php`:**
- Sections are split by double newlines (`\n\n`)
- Lines starting with `ALL CAPS:` become section headings
- Lines starting with `-` or `•` become bullet lists
- Headings containing CRITICAL, WARNING, RISK, PROHIBITED, or SEVERE get red warning styling
- All other headed sections get blue info styling

---

## 5. Field Reference — `countries`

The master country record. Language-independent data only.

| Column | Type | Constraints | Description | Example Values |
|--------|------|-------------|-------------|----------------|
| `id` | INT AUTO_INCREMENT | PRIMARY KEY | Country identifier | — |
| `country_code` | VARCHAR(3) | NOT NULL, UNIQUE | ISO 3166-1 alpha-3 code | `FRA`, `USA`, `JPN`, `AUS` |
| `flag_emoji` | VARCHAR(10) | NOT NULL | Unicode flag emoji | 🇫🇷, 🇺🇸, 🇯🇵 |
| `region` | VARCHAR(50) | NOT NULL | Geographic region | See allowed values below |
| `official_url` | VARCHAR(500) | NOT NULL | Main official government/tourism URL | `https://france-visas.gouv.fr` |
| `visa_type` | ENUM | NOT NULL | Primary visa category | `visa_free`, `visa_on_arrival`, `visa_required`, `evisa` |
| `last_updated` | DATE | NOT NULL | Auto-set to CURDATE() on save | `2026-02-20` |
| `helpful_yes` | INT | DEFAULT 0 | Helpful vote count | — |
| `helpful_no` | INT | DEFAULT 0 | Not-helpful vote count | — |
| `display_order` | INT | DEFAULT 0 | Sort priority (lower = first) | `0`, `10`, `100` |
| `is_active` | TINYINT(1) | DEFAULT 1 | Whether visible on the site | `1` (active), `0` (hidden) |
| `is_popular` | TINYINT(1) | DEFAULT 0 | Marked as popular destination | `0` or `1` |
| `capital` | VARCHAR(100) | DEFAULT NULL | Capital city | `Paris`, `Washington, D.C.` |
| `population` | VARCHAR(50) | DEFAULT NULL | Population (free text) | `67 million`, `331 million` |
| `currency_name` | VARCHAR(100) | DEFAULT NULL | Currency full name | `Euro`, `US Dollar` |
| `currency_code` | VARCHAR(10) | DEFAULT NULL | ISO 4217 currency code | `EUR`, `USD`, `GBP` |
| `currency_symbol` | VARCHAR(10) | DEFAULT NULL | Currency symbol | `€`, `$`, `£`, `¥` |
| `plug_type` | VARCHAR(50) | DEFAULT NULL | Electrical plug types | `Type C, E`, `Type A, B`, `Type G` |
| `leader_name` | VARCHAR(200) | DEFAULT NULL | Head of state/government | `Emmanuel Macron` |
| `leader_title` | VARCHAR(100) | DEFAULT NULL | Leader's title | `President`, `Prime Minister`, `King` |
| `leader_term` | VARCHAR(100) | DEFAULT NULL | Term info | — |
| `time_zone` | VARCHAR(100) | DEFAULT NULL | Time zone(s) | `UTC+1 (CET)`, `UTC-5 to UTC-10` |
| `calling_code` | VARCHAR(20) | DEFAULT NULL | International dialing code | `+33`, `+1`, `+81` |
| `languages` | VARCHAR(200) | DEFAULT NULL | Spoken languages (free text) | `French`, `English, French` |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Record creation | — |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE | Last modification | — |

### Allowed Regions

The admin form provides these 9 region values:

| Region |
|--------|
| Africa |
| Asia |
| Europe |
| North America |
| South America |
| Oceania |
| Central America |
| Caribbean |
| Middle East |

### Visa Type Values

| Value | Display Label | Description |
|-------|---------------|-------------|
| `visa_free` | Visa Free | No visa needed for most nationalities |
| `visa_on_arrival` | Visa on Arrival | Visa issued at port of entry |
| `evisa` | E-Visa | Electronic visa applied for online before travel |
| `visa_required` | Visa Required | Must apply at embassy/consulate before travel |

---

## 6. Field Reference — `country_details`

Supplementary descriptive content per country per language.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT AUTO_INCREMENT | PRIMARY KEY | Row identifier |
| `country_id` | INT | NOT NULL, FK → countries(id) ON DELETE CASCADE | Links to parent country |
| `lang_code` | VARCHAR(10) | NOT NULL | Language code |
| `description` | TEXT | DEFAULT NULL | General country description (shown under "About [Country]") |
| `known_for` | TEXT | DEFAULT NULL | What the country is famous for (shown under "🌟 [Country] is Known For") |
| `travel_tips` | TEXT | DEFAULT NULL | Practical travel advice (shown under "✈️ Travel Tips") |

**Unique Constraint**: `(country_id, lang_code)`

---

## 7. How the Country Page Displays Data

The public-facing country page (`country.php?id=XX`) renders data in this order:

### Hero Section
- **Flag emoji** (from `countries.flag_emoji`)
- **Country name** (from `country_translations.country_name` in current language)
- **Region** (from `countries.region`)

### Top Visa CTA Banner
- Links to `countries.official_url` with "Visit Official Site" button

### Main Content Column

1. **Visa Type Badge** — color-coded badge from `countries.visa_type`
2. **About [Country]** — `country_details.description` (if populated)
3. **[Country] is Known For** — `country_details.known_for` (if populated)
4. **Cultural Highlights** box — `countries.languages`, `countries.currency_name`/`currency_symbol`, `countries.region`
5. **Practical Information** box — `countries.time_zone`, `countries.calling_code`, `countries.plug_type`, `countries.capital`
6. **Visa Requirements** heading — begins the visa section
7. **Entry Summary** — `country_translations.entry_summary` (rendered with `nl2br()`)
8. **Visa Info Cards Grid** (each only shown if field is non-empty):
   - ⏱️ **Duration** — `country_translations.visa_duration`
   - 💰 **Visa Fee** — `country_translations.visa_fee`
   - ⚡ **Processing Time** — `country_translations.processing_time`
   - 🛂 **Passport Validity** — `country_translations.passport_validity`
   - 📝 **Arrival Card** — `country_translations.arrival_card_required`
9. **Official Visa Information** box — `country_translations.official_visa_url` (if populated), shown as a clickable "Apply Now / Learn More" button
10. **Requirements Details** — `country_translations.visa_requirements` (rendered with `nl2br()`)
11. **Additional Requirements & Important Information** — `country_translations.additional_docs` (formatted with headings/bullets/warning styling)
12. **Last Verified** — `country_translations.last_verified` (formatted as "February 20, 2026")
13. **Travel Tips** — `country_details.travel_tips` (if populated)
14. **Entry Requirements Summary** box — visa type label + entry summary + population
15. **Major Airports** — from `airports` table

### Sidebar Column (Quick Facts)

Displays these `countries` fields (each only if non-empty):
- Capital, Population, Languages, Currency (name + symbol), Time Zone, Calling Code, Plug Type

### Bottom CTA
- Another "Visit Official Site" button linking to `countries.official_url`

### User Feedback Widget
- "Was this information helpful?" buttons with vote counts
- "Report an error" link

### Personalized Visa Section (JavaScript)
- If user has selected a passport via localStorage, fetches from `/api/get_personalized_visa_requirements.php`
- Shows passport-specific visa type, duration, cost, processing time, approval rate, requirements, and special notes
- Data comes from the `bilateral_visa_requirements` table

---

## 8. Data Validation Rules

### Server-Side Validation (in `add_country.php` and `edit_country.php`)

| Field | Rule |
|-------|------|
| `country_code` | **Required**. Must be exactly 3 characters. Auto-uppercased. Must be unique across all countries. |
| `flag_emoji` | **Required**. Cannot be empty. |
| `region` | **Required**. Must be one of the 9 allowed values. |
| `visa_type` | **Required**. Must be one of: `visa_free`, `visa_on_arrival`, `visa_required`, `evisa`. |
| `translations[en][country_name]` | **Required**. English country name cannot be empty. |
| Duplicate country code | Checked with DB query — rejects if another country already uses the code. |
| CSRF token | **Required**. Verified via `verifyCSRFToken()`. |

### Implicit Validation (from database schema)

| Field | Constraint |
|-------|-----------|
| `country_name` | Max 100 characters |
| `official_url` / `official_visa_url` | Max 500 characters |
| `visa_duration`, `passport_validity`, `visa_fee`, `processing_time` | Max 100 characters each |
| `arrival_card_required` | Max 50 characters (admin uses dropdown: Yes/No/Online only) |
| `country_translations` unique key | Prevents duplicate (country_id, lang_code) pairs |

### Auto-Set Fields

| Field | Behavior |
|-------|----------|
| `countries.last_updated` | Set to `CURDATE()` on every admin save |
| `country_translations.last_verified` | Set to `CURDATE()` on every admin save |
| `country_translations.updated_at` | Auto-updated by MySQL on row change |

---

## 9. Official Visa URL Guidelines

There are **two URL fields** that serve different purposes:

### `countries.official_url`
- **Purpose**: Main government/immigration website for the country
- **Where used**: Top CTA banner ("Visit Official Site") and bottom CTA banner
- **Stored on**: The `countries` table (language-independent)
- **Example**: `https://france-visas.gouv.fr`

### `country_translations.official_visa_url`
- **Purpose**: Language-specific visa application portal or official visa information page
- **Where used**: Displayed in a highlighted box under "Official Visa Information" with an "Apply Now / Learn More" button
- **Stored on**: Per-language in `country_translations`
- **Can vary by language**: e.g., English page vs. Spanish page of the same government site
- **Example**: `https://france-visas.gouv.fr/en/` (English), `https://france-visas.gouv.fr/es/` (Spanish)

### URL Format Guidelines

- Always use full URLs starting with `https://`
- Prefer official government domains (`.gov`, `.gouv`, `.go.jp`, etc.)
- For countries with eVisa systems, link directly to the eVisa application portal
- Verify URLs are working before saving
- For the per-language `official_visa_url`, use the URL in the matching language when available

---

## 10. Data Entry Best Practices

### Entry Summary (`entry_summary`)
- Keep it to 1-3 sentences
- Should answer: "Do I need a visa for this country?"
- Mention the predominant visa type and common stay duration
- Example: *"Most nationalities can enter Japan visa-free for up to 90 days. Citizens of some countries require a visa in advance."*

### Visa Requirements (`visa_requirements`)
- Provide detailed information including:
  - Which nationalities get visa-free access
  - eVisa process (if applicable)
  - Embassy application requirements
  - Extension possibilities
- Use plain text with line breaks for readability

### Visa Duration (`visa_duration`)
- Simple, concise format
- Examples: `90 days`, `30 days`, `6 months`, `14 days (transit)`, `30-90 days (varies by nationality)`

### Visa Fee (`visa_fee`)
- Include currency
- Examples: `Free`, `$50 USD`, `€80`, `$160 USD (tourist)`, `Free (visa-free) / $30 USD (eVisa)`

### Processing Time (`processing_time`)
- Examples: `Instant`, `On arrival`, `3-5 business days`, `15 calendar days`, `2-4 weeks`

### Passport Validity (`passport_validity`)
- Examples: `6 months beyond stay`, `3 months after departure date`, `Valid for duration of stay`, `6 months from entry`

### Arrival Card Required (`arrival_card_required`)
- Use the dropdown values: `Yes`, `No`, `Online only`
- `Online only` = digital arrival card that must be completed before travel (e.g., Singapore's SG Arrival Card)

### Additional Documents (`additional_docs`)
- Use the structured heading format for best display:

```
REQUIRED DOCUMENTS:
- Valid passport with at least 6 months validity
- Completed visa application form
- Passport-sized photographs (2)
- Proof of accommodation
- Return/onward flight tickets

FINANCIAL REQUIREMENTS:
- Bank statements from the last 3 months
- Minimum $50 USD per day of stay

CRITICAL HEALTH REQUIREMENTS:
- Yellow fever vaccination certificate required
- COVID-19 vaccination may be requested
```

### Country Details
- **Description**: 2-4 paragraphs about the country, its culture, and appeal for travelers
- **Known For**: Key attractions, cultural elements, and unique features
- **Travel Tips**: Practical advice on transport, customs, tipping, safety, etc.

---

## 11. Example Data Entry

### Countries Table Entry (Japan)

| Field | Value |
|-------|-------|
| country_code | `JPN` |
| flag_emoji | 🇯🇵 |
| region | `Asia` |
| official_url | `https://www.mofa.go.jp/j_info/visit/visa/` |
| visa_type | `visa_free` |
| is_active | `1` |
| is_popular | `1` |
| capital | `Tokyo` |
| population | `125 million` |
| currency_name | `Japanese Yen` |
| currency_code | `JPY` |
| currency_symbol | `¥` |
| plug_type | `Type A, B` |
| leader_name | `Shigeru Ishiba` |
| leader_title | `Prime Minister` |
| time_zone | `UTC+9 (JST)` |
| calling_code | `+81` |
| languages | `Japanese` |

### Country Translations Entry (Japan, English)

| Field | Value |
|-------|-------|
| lang_code | `en` |
| country_name | `Japan` |
| entry_summary | `Most nationalities can enter Japan visa-free for short stays of up to 90 days. Japan has visa exemption agreements with 71 countries and territories.` |
| visa_requirements | `Citizens of 71 countries can enter Japan without a visa for tourism or business stays of up to 90 days. Some nationalities are limited to 15 or 30 days. For longer stays, work, or study, a visa must be obtained from a Japanese embassy or consulate before travel.` |
| visa_duration | `90 days` |
| passport_validity | `Valid for duration of stay` |
| visa_fee | `Free (visa-free entry)` |
| processing_time | `Instant (visa-free) / 5-7 business days (visa)` |
| official_visa_url | `https://www.mofa.go.jp/j_info/visit/visa/index.html` |
| arrival_card_required | `Yes` |
| additional_docs | See structured format above |

---

## Quick Reference Card

| What you need to do | Where to go |
|---|---|
| Add a new country | `/admin/add_country.php` |
| Edit existing country | `/admin/edit_country.php?id=XX` |
| View all countries | `/admin/countries.php` |
| Check how a country looks | `/country.php?id=XX` |
| View public country page in a specific language | Add `?lang=es` (or `zh`, `fr`, `de`) to URL |

| Must-fill fields | Optional but recommended |
|---|---|
| Country code (3-letter ISO) | All non-English translations |
| Flag emoji | Country details (description, known_for, travel_tips) |
| Region | Leader name/title |
| Visa type | Display order |
| English country name | Population, currency details |
| English entry summary | Plug type, time zone, calling code |
