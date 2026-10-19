<?php

/**
 * Load Profile Import Script - Revised Version 4 (Dynamic Columns)
 */

date_default_timezone_set('Asia/Manila');

// Database connection
require_once('connection.php');

// Configuration
$config = [
    'import_path' => realpath(dirname(__FILE__)) . '/upload/importloadprofile/',
    'log_file' => realpath(dirname(__FILE__)) . '/logs/lp_import_' . date('Y-m-d') . '.log',
    'csv_skip_rows' => 5
];

// Ensure log directory exists
if (!is_dir(dirname($config['log_file']))) {
    mkdir(dirname($config['log_file']), 0755, true);
}

function logMessage($message, $level = 'INFO') {
    global $config;
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] [$level] $message\n";
    file_put_contents($config['log_file'], $log_entry, FILE_APPEND);
    echo $log_entry;
}

function validateFile($filepath) {
    if (!file_exists($filepath)) {
        logMessage("File not found: $filepath", "ERROR");
        return false;
    }
    if (!is_readable($filepath)) {
        logMessage("File not readable: $filepath", "ERROR");
        return false;
    }
    return true;
}

function parseFilename($filename) {
    $parts = explode('_', pathinfo($filename, PATHINFO_FILENAME));
    if (count($parts) < 2) {
        return false;
    }
    return [
        'location' => $parts[0],
        'meter' => $parts[1],
        'full_name' => $filename
    ];
}

function isFileAlreadyImported($conn, $filename) {
    $stmt = $conn->prepare("SELECT id FROM load_profile_imported_files WHERE file_name = ?");
    if (!$stmt) {
        logMessage("Prepare failed: " . $conn->error, "ERROR");
        return false;
    }
    $stmt->bind_param("s", $filename);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows >= 1;
    $stmt->close();
    return $exists;
}

function getMeterDetails($conn, $meter, $location) {
    $stmt = $conn->prepare("SELECT md.id FROM meter_details md JOIN meter_site ms ON md.meter_site_id = ms.id WHERE md.meter_name = ? AND ms.site_code = ? LIMIT 1");
    if (!$stmt) {
        logMessage("Prepare failed: " . $conn->error, "ERROR");
        return null;
    }
    $stmt->bind_param("ss", $meter, $location);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $meter_id = $row['id'];
        $stmt->close();
        return $meter_id;
    }
    $stmt->close();
    logMessage("Meter not found: $meter at $location", "ERROR");
    return null;
}

function updateMeterLoadProfileStatus($conn, $meter_id) {
    $stmt = $conn->prepare("UPDATE meter_details SET meter_load_profile = 'YES' WHERE id = ?");
    if (!$stmt) {
        logMessage("Prepare failed: " . $conn->error, "ERROR");
        return false;
    }
    $stmt->bind_param("i", $meter_id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

function insertLoadProfileRecord($conn, $data) {
    $stmt = $conn->prepare(
        "INSERT INTO load_profile 
        (location, meter_id, meter_details_id, datetime, event_id, 
         ch_1, ch_2, ch_3, ch_4, ch_5, ch_6, ch_7, ch_8, ch_9, ch_10,
         ch_11, ch_12, ch_13, ch_14, ch_15, ch_16, ch_17, ch_18, ch_19, ch_20)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    
    if (!$stmt) {
        logMessage("Prepare failed: " . $conn->error, "ERROR");
        return false;
    }
    
    $stmt->bind_param(
        "ssissssssssssssssssssssss",
        $data['location'],
        $data['meter_id'],
        $data['meter_details_id'],
        $data['datetime'],
        $data['event_id'],
        $data['ch_1'], $data['ch_2'], $data['ch_3'], $data['ch_4'], $data['ch_5'],
        $data['ch_6'], $data['ch_7'], $data['ch_8'], $data['ch_9'], $data['ch_10'],
        $data['ch_11'], $data['ch_12'], $data['ch_13'], $data['ch_14'], $data['ch_15'],
        $data['ch_16'], $data['ch_17'], $data['ch_18'], $data['ch_19'], $data['ch_20']
    );
    
    $success = $stmt->execute();
    if (!$success) {
        logMessage("Insert failed: " . $stmt->error, "ERROR");
    }
    $stmt->close();
    return $success;
}

function recordImportedFile($conn, $filename, $location, $meter) {
    $stmt = $conn->prepare(
        "INSERT INTO load_profile_imported_files (file_name, site_code, meter_name) VALUES (?, ?, ?)"
    );
    if (!$stmt) {
        logMessage("Prepare failed: " . $conn->error, "ERROR");
        return false;
    }
    $stmt->bind_param("sss", $filename, $location, $meter);
    $success = $stmt->execute();
    if (!$success) {
        logMessage("Failed to record imported file: " . $stmt->error, "ERROR");
    }
    $stmt->close();
    return $success;
}

function processFile($conn, $filepath, $file_info) {
    global $config;
    
    $filename = $file_info['full_name'];
    $location = $file_info['location'];
    $meter = $file_info['meter'];
    
    logMessage("Processing file: $filename (Location: $location, Meter: $meter)", "INFO");
    
    if (!validateFile($filepath)) {
        return false;
    }
    
    if (isFileAlreadyImported($conn, $filename)) {
        logMessage("File already imported, skipping: $filename", "INFO");
        return unlink($filepath) ? true : false;
    }
    
    $meter_id = getMeterDetails($conn, $meter, $location);
    if ($meter_id === null) {
        logMessage("Cannot proceed without valid meter ID for $meter at $location", "ERROR");
        return false;
    }
    
    if (!updateMeterLoadProfileStatus($conn, $meter_id)) {
        logMessage("Failed to update meter status for meter_id: $meter_id", "ERROR");
        return false;
    }
    
    $lines = file($filepath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!$lines) {
        logMessage("Failed to read file: $filepath", "ERROR");
        return false;
    }
    
    $record_count = 0;
    $error_count = 0;
    $actual_columns = 0;  // Will be detected from first data line
    $conn->begin_transaction();
    
    try {
        foreach ($lines as $line_num => $line) {
            // Skip header lines
            if ($line_num < $config['csv_skip_rows']) {
                continue;
            }
            
            $columns = str_getcsv($line);
            
            // DYNAMIC: Detect actual column count from first data row
            if ($actual_columns === 0) {
                $actual_columns = count($columns);
                logMessage("Detected $actual_columns columns in CSV file", "INFO");
            }
            
            // Pad columns array to ensure all 20+ channels exist (with default 0)
            while (count($columns) < 22) {
                $columns[] = '0';
            }
            
            // Validate minimum required columns (datetime + event_id at least)
            if ($actual_columns < 3) {
                logMessage("Invalid column count at line " . ($line_num + 1) . ": " . $actual_columns . " (need at least 3: datetime, event_id, and data)", "WARNING");
                $error_count++;
                continue;
            }
            
            $data = [
                'location' => $location,
                'meter_id' => $meter,
                'meter_details_id' => $meter_id,
                'datetime' => isset($columns[0]) ? trim($columns[0]) : '',
                'event_id' => isset($columns[1]) ? trim($columns[1]) : '',
                'ch_1' => isset($columns[2]) ? floatval($columns[2]) : 0,
                'ch_2' => isset($columns[3]) ? floatval($columns[3]) : 0,
                'ch_3' => isset($columns[4]) ? floatval($columns[4]) : 0,
                'ch_4' => isset($columns[5]) ? floatval($columns[5]) : 0,
                'ch_5' => isset($columns[6]) ? floatval($columns[6]) : 0,
                'ch_6' => isset($columns[7]) ? floatval($columns[7]) : 0,
                'ch_7' => isset($columns[8]) ? floatval($columns[8]) : 0,
                'ch_8' => isset($columns[9]) ? floatval($columns[9]) : 0,
                'ch_9' => isset($columns[10]) ? floatval($columns[10]) : 0,
                'ch_10' => isset($columns[11]) ? floatval($columns[11]) : 0,
                'ch_11' => isset($columns[12]) ? floatval($columns[12]) : 0,
                'ch_12' => isset($columns[13]) ? floatval($columns[13]) : 0,
                'ch_13' => isset($columns[14]) ? floatval($columns[14]) : 0,
                'ch_14' => isset($columns[15]) ? floatval($columns[15]) : 0,
                'ch_15' => isset($columns[16]) ? floatval($columns[16]) : 0,
                'ch_16' => isset($columns[17]) ? floatval($columns[17]) : 0,
                'ch_17' => isset($columns[18]) ? floatval($columns[18]) : 0,
                'ch_18' => isset($columns[19]) ? floatval($columns[19]) : 0,
                'ch_19' => isset($columns[20]) ? floatval($columns[20]) : 0,
                'ch_20' => isset($columns[21]) ? floatval($columns[21]) : 0
            ];
            
            if (!strtotime($data['datetime'])) {
                logMessage("Invalid datetime at line " . ($line_num + 1) . ": " . $data['datetime'], "WARNING");
                $error_count++;
                continue;
            }
            
            if (insertLoadProfileRecord($conn, $data)) {
                $record_count++;
            } else {
                $error_count++;
            }
        }
        
        if ($record_count > 0) {
            $conn->commit();
            recordImportedFile($conn, $filename, $location, $meter);
            unlink($filepath);
            logMessage("Import successful: $record_count records inserted (Columns detected: $actual_columns)", "INFO");
        } else {
            $conn->rollback();
            logMessage("No valid records found in file, rolling back transaction", "WARNING");
        }
        
    } catch (Exception $e) {
        $conn->rollback();
        logMessage("Transaction error: " . $e->getMessage(), "ERROR");
        return false;
    }
    
    return $record_count > 0;
}

// Main processing
logMessage("=== Load Profile Import Started ===", "INFO");

$import_dir = $config['import_path'];

if (!is_dir($import_dir)) {
    logMessage("Import directory not found: $import_dir", "ERROR");
    exit(1);
}

$files = glob($import_dir . '*.csv');

if (empty($files)) {
    logMessage("No CSV files found in import directory", "INFO");
    exit(0);
}

$total_processed = 0;
$total_failed = 0;

foreach ($files as $filepath) {
    $filename = basename($filepath);
    $file_info = parseFilename($filename);
    
    if (!$file_info) {
        logMessage("Invalid filename format: $filename (expected: LOCATION_METER_*.csv)", "WARNING");
        $total_failed++;
        continue;
    }
    
    if (processFile($conn, $filepath, $file_info)) {
        $total_processed++;
    } else {
        $total_failed++;
    }
}

logMessage("=== Import Complete: $total_processed processed, $total_failed failed ===", "INFO");

?>
