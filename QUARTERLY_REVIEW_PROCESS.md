# Quarterly Review Process — ArrivalCards

> Schedule and procedures for keeping visa information accurate and up to date.

---

## Review Schedule

| Quarter | Review Month | Deadline | Focus |
|---------|-------------|----------|-------|
| Q1 | February | Feb 28 | Post-holiday policy changes, new year updates |
| Q2 | May | May 31 | Summer travel season prep, Schengen updates |
| Q3 | August | Aug 31 | Mid-year policy changes, e-visa updates |
| Q4 | November | Nov 30 | Year-end changes, holiday travel prep |

**Time estimate**: 4–6 hours per quarterly review

---

## Pre-Review Checklist

Before starting the review:

- [ ] Back up the database (`mysqldump -u [user] -p arrivalcards > backup_YYYY-MM-DD.sql`)
- [ ] Check admin dashboard for user feedback (Admin → Feedback)
- [ ] Review contact submissions for "incorrect info" reports (Admin → Contacts)
- [ ] Pull the latest country views data (Admin → Analytics) to prioritize high-traffic pages

---

## Review Procedure

### Step 1: Prioritize Countries (30 min)

1. **High-traffic countries first** — Check the analytics dashboard for the top 20 most-viewed countries
2. **Countries with negative feedback** — Check the Feedback dashboard for countries with low satisfaction scores
3. **Countries flagged by users** — Review contact submissions tagged as error reports
4. **Countries not verified in 90+ days** — Run this query:

```sql
SELECT c.country_code, c.flag_emoji, ct.country_name, ct.last_verified,
       DATEDIFF(CURDATE(), ct.last_verified) AS days_since_verified
FROM country_translations ct
JOIN countries c ON ct.country_id = c.id
WHERE ct.lang_code = 'en'
  AND (ct.last_verified IS NULL OR ct.last_verified < DATE_SUB(CURDATE(), INTERVAL 90 DAY))
ORDER BY days_since_verified DESC;
```

### Step 2: Verify Visa Requirements (2–3 hours)

For each prioritized country:

1. **Visit the official government visa portal** (stored in `official_visa_url`)
2. **Cross-reference** with these trusted sources:
   - [IATA Travel Centre](https://www.iatatravelcentre.com/)
   - [Sherpa](https://apply.joinsherpa.com/)
   - [VisaHQ](https://www.visahq.com/)
   - Country's embassy website
3. **Check for changes** in:
   - Visa type (free → e-visa, etc.)
   - Duration of stay
   - Visa fees
   - Required documents
   - Processing times
   - Passport validity requirements
4. **Update the data** via Admin → Countries → Edit
5. The `last_verified` date auto-updates on save

### Step 3: Check Official URLs (30 min)

Run a link check on all official URLs:

```sql
SELECT c.country_code, ct.country_name, c.official_url, ct.official_visa_url
FROM countries c
JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = 'en'
ORDER BY ct.country_name;
```

For each URL:
- Verify it loads (no 404, no redirect to error page)
- Verify it still points to the official visa/immigration page
- Update if the government has changed their portal URL

### Step 4: Review Content Quality (30 min)

Spot-check 10–15 countries for:
- [ ] `visa_requirements` text is ≥500 characters and comprehensive
- [ ] `entry_summary` is concise and accurate
- [ ] `additional_docs` formatting renders correctly
- [ ] No placeholder text ("N/A" where real data should exist)
- [ ] Consistent formatting across countries

### Step 5: Check Translations (30 min)

Verify non-English translations for the top 10 countries:
- Use Admin → Language Check to find missing translations
- Ensure Spanish (es), Chinese (zh), French (fr), and German (de) are populated
- Pay special attention to visa fee currencies (should match local expectations)

### Step 6: Update Analytics (15 min)

- Record total page views this quarter
- Note top 5 countries by traffic
- Note any trending countries (unusual traffic spikes)
- Document satisfaction rate from feedback system

---

## Post-Review Checklist

After completing the review:

- [ ] All updated countries have `last_verified` within the current month
- [ ] All broken URLs fixed or flagged
- [ ] All user-reported errors addressed
- [ ] Create backup of updated database
- [ ] Update this section with completion date:

### Review Log

| Quarter | Date Completed | Countries Reviewed | Changes Made | Reviewer |
|---------|---------------|-------------------|--------------|----------|
| Q1 2026 | — | — | — | — |
| Q2 2026 | — | — | — | — |
| Q3 2026 | — | — | — | — |
| Q4 2026 | — | — | — | — |

---

## Monitoring Between Reviews

### Weekly (5 min)
- Glance at the Feedback dashboard for any spike in "Not Helpful" votes
- Check contact submissions for urgent error reports

### Monthly (15 min)
- Review countries with the most negative feedback
- Check for any major geopolitical changes (sanctions, new visa agreements)
- Monitor news for countries changing their visa policies

### Trigger-Based (immediate)
- **Country changes visa policy**: Update immediately, don't wait for quarterly review
- **User reports incorrect info**: Verify and fix within 48 hours
- **Official URL goes dead**: Find new URL and update same day

---

## SQL Utilities

### Countries never verified
```sql
SELECT c.country_code, ct.country_name
FROM country_translations ct
JOIN countries c ON ct.country_id = c.id
WHERE ct.lang_code = 'en' AND ct.last_verified IS NULL;
```

### Countries with most negative feedback
```sql
SELECT c.country_code, c.flag_emoji, ct.country_name,
       c.helpful_yes, c.helpful_no,
       (c.helpful_yes - c.helpful_no) AS net_score
FROM countries c
JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = 'en'
WHERE c.helpful_no > 0
ORDER BY net_score ASC
LIMIT 20;
```

### Missing translations by language
```sql
SELECT c.country_code, en.country_name,
       CASE WHEN es.id IS NULL THEN '❌' ELSE '✅' END AS es,
       CASE WHEN zh.id IS NULL THEN '❌' ELSE '✅' END AS zh,
       CASE WHEN fr.id IS NULL THEN '❌' ELSE '✅' END AS fr,
       CASE WHEN de.id IS NULL THEN '❌' ELSE '✅' END AS de
FROM countries c
JOIN country_translations en ON c.id = en.country_id AND en.lang_code = 'en'
LEFT JOIN country_translations es ON c.id = es.country_id AND es.lang_code = 'es'
LEFT JOIN country_translations zh ON c.id = zh.country_id AND zh.lang_code = 'zh'
LEFT JOIN country_translations fr ON c.id = fr.country_id AND fr.lang_code = 'fr'
LEFT JOIN country_translations de ON c.id = de.country_id AND de.lang_code = 'de'
WHERE es.id IS NULL OR zh.id IS NULL OR fr.id IS NULL OR de.id IS NULL
ORDER BY en.country_name;
```

### Stale countries (oldest verified first)
```sql
SELECT c.country_code, ct.country_name, ct.last_verified,
       DATEDIFF(CURDATE(), ct.last_verified) AS days_stale
FROM country_translations ct
JOIN countries c ON ct.country_id = c.id
WHERE ct.lang_code = 'en'
ORDER BY ct.last_verified ASC
LIMIT 30;
```

---

## Calendar Reminders

Set these recurring reminders:

| When | Reminder |
|------|---------|
| 1st of Feb, May, Aug, Nov | "ArrivalCards quarterly review due this month" |
| 15th of Feb, May, Aug, Nov | "ArrivalCards quarterly review — halfway checkpoint" |
| Last weekday of Feb, May, Aug, Nov | "ArrivalCards quarterly review — deadline today" |
| Every Monday | "Quick check: ArrivalCards feedback & error reports" |

---

**Last Updated**: February 2026  
**Document Owner**: Admin Team  
**Related Documents**: [VISA_EDITOR_GUIDE.md](VISA_EDITOR_GUIDE.md), [IMPLEMENTATION_TODO.md](IMPLEMENTATION_TODO.md)
