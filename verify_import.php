<?php
date_default_timezone_set('Asia/Manila');
require_once('connection.php');

echo "\n================================\n";
echo "LOAD PROFILE VERIFICATION REPORT\n";
echo "================================\n\n";

// 1. Total files imported
$sql = "SELECT COUNT(*) as cnt FROM load_profile_imported_files";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_files = $row['cnt'];

echo "1. FILES IMPORTED: " . $total_files . "\n\n";

// 2. Total records
$sql = "SELECT COUNT(*) as cnt FROM load_profile";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_records = $row['cnt'];

echo "2. TOTAL RECORDS: " . number_format($total_records) . "\n\n";

// 3. Sample data
echo "3. SAMPLE DATA (First 5 records):\n";
echo "─────────────────────────────────────────────────────────\n";
$sql = "SELECT location, meter_id, datetime, event_id, ch_1 FROM load_profile LIMIT 5";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "Location: " . $row['location'] . "\n";
    echo "  Meter: " . $row['meter_id'] . " | DateTime: " . $row['datetime'] . "\n";
    echo "  Event: " . $row['event_id'] . " | Ch_1: " . $row['ch_1'] . "\n\n";
}

// 4. Data quality
echo "4. DATA QUALITY:\n";
echo "─────────────────────────────────────────────────────────\n";
$sql = "SELECT 
    COUNT(CASE WHEN datetime IS NULL THEN 1 END) as null_datetime,
    COUNT(CASE WHEN event_id IS NULL THEN 1 END) as null_event_id
    FROM load_profile";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo "NULL DateTimes: " . $row['null_datetime'] . "\n";
echo "NULL Event IDs: " . $row['null_event_id'] . "\n";

if ($row['null_datetime'] == 0 && $row['null_event_id'] == 0) {
    echo "✓ No NULL values in critical fields\n";
} else {
    echo "⚠ WARNING: NULL values detected\n";
}

echo "\n";

// 5. Records by location
echo "5. RECORDS BY LOCATION:\n";
echo "─────────────────────────────────────────────────────────\n";
$sql = "SELECT location, COUNT(*) as cnt FROM load_profile GROUP BY location ORDER BY cnt DESC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "  " . $row['location'] . ": " . number_format($row['cnt']) . " records\n";
}

echo "\n";

// 6. Date range
echo "6. DATA DATE RANGE:\n";
echo "─────────────────────────────────────────────────────────\n";
$sql = "SELECT MIN(datetime) as earliest, MAX(datetime) as latest FROM load_profile";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo "Earliest: " . $row['earliest'] . "\n";
echo "Latest:   " . $row['latest'] . "\n";

echo "\n";

// 7. Meter status
echo "7. METER LOAD PROFILE STATUS:\n";
echo "─────────────────────────────────────────────────────────\n";
$sql = "SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN meter_load_profile = 'YES' THEN 1 END) as with_profile
    FROM meter_details";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo "Total meters: " . $row['total'] . "\n";
echo "With profile: " . $row['with_profile'] . "\n";

echo "\n";

// 8. Summary
echo "8. SUMMARY:\n";
echo "─────────────────────────────────────────────────────────\n";

if ($total_records > 0) {
    echo "✓ Data Import SUCCESSFUL\n";
    echo "  Records: " . number_format($total_records) . "\n";
    echo "  Files: " . $total_files . "\n";
    echo "  Avg per file: " . number_format(round($total_records / max($total_files, 1))) . "\n";
} else {
    echo "✗ No data found\n";
}

echo "\n================================\n\n";

$conn->close();
?>
