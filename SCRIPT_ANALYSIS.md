# Load Profile Import Script Analysis

## Critical Findings

### Issue 1: Event_ID Corruption (HIGH PRIORITY)
**Problem:** The `event_id` field is being corrupted in the database. Sample data shows:
- Row 2: `000000002022-03-31 00:10:00` (datetime concatenated into event_id)
- Row 3: `000000002022-03-31 00:15:00` (datetime concatenated into event_id)

**Root Cause:** The CSV parsing assumes the first two columns are:
1. datetime
2. event_id
3. ch_1 through ch_20

However, looking at the corrupted data, the **actual CSV format appears to be different**. The event_id data is being overwritten with datetime values during line parsing.

**Location in Code:** `lp_import_rev2.php` lines 284-286
```php
'datetime' => trim($columns[0]),
'event_id' => trim($columns[1]),
```

**Solution:** Verify the actual CSV format and adjust column indices accordingly. The CSV header should be examined to confirm column positions.

---

### Issue 2: Location Field Always "Not Set"
**Problem:** All records show `location: "Not Set"` instead of actual location names.

**Root Cause:** The `location` field is being populated from the filename parsing (line 276):
```php
'location' => $location,  // This comes from parseFilename()
```

But the `parseFilename()` function extracts location from the filename (line 49-52):
```php
return [
    'location' => $parts[0],   // First part of filename before underscore
    'meter' => $parts[1],
    'full_name' => $filename
];
```

**Why it shows "Not Set":** The database `load_profile` table's `location` field defaults to "Not Set" OR the location is being set at database schema level with a default value. The location metadata should be fetched from the `meter_details` table instead.

**Fix Required:**
1. After retrieving `meter_details_id`, query to get the actual `meter_site_name` (location)
2. Use that value instead of the filename-parsed location

---

### Issue 3: CSV Format Mismatch
**Problem:** The script expects 22 columns (line 26):
```php
'expected_columns' => 22  // datetime, event_id, ch_1 through ch_20
```

But the corrupted data suggests the CSV might have a different column structure or format.

**Evidence:** Looking at the corrupted event_id values that contain full datetime strings, it appears:
- The datetime field might be taking more than one column
- Or the columns are shifted in the actual CSV file

---

## Code Quality Issues

### Missing Global $config Reference
**Line 289:** Inside the `processFile()` function, `$config['csv_skip_rows']` is accessed but `$config` is not declared as global.
```php
if ($line_num < $config['csv_skip_rows']) {  // ERROR: $config undefined
    continue;
}
```
**Fix:** Add at top of function:
```php
global $config;
```

---

## Recommendations

### Immediate Actions:
1. **Inspect actual CSV file structure:**
   ```bash
   head -10 upload/importloadprofile/PELCO_75159913_lp1_2026-05-28.csv
   ```
   And look at the header row to understand actual column positions

2. **Fix the global $config issue** in `processFile()` function

3. **Verify meter_details table structure:**
   ```sql
   SELECT * FROM meter_details LIMIT 1;
   ```

4. **Update the import script to fetch location from meter_details:**
   ```php
   // After getting meter_id, fetch the location
   $location_result = $conn->prepare("SELECT meter_site_name FROM meter_details WHERE id = ?");
   $location_result->bind_param("i", $meter_id);
   $location_result->execute();
   $location_row = $location_result->get_result()->fetch_assoc();
   $data['location'] = $location_row['meter_site_name'];
   ```

### Testing Protocol:
1. Create a test CSV file with known good data
2. Run import on test data
3. Verify all fields are correctly populated
4. Check sample queries from database

---

## Summary
- **Data IS being inserted** (11.7M+ records) ✓
- **Data quality IS compromised** due to:
  - Event_ID corruption (format/column mapping issue)
  - Location not being populated from meter metadata
  - Potential global variable scope issue causing incorrect column parsing

