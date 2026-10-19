<?php
/**
 * receive_file_rev2.php
 * 
 * Improved File Upload Handler
 * - Enhanced security with input validation
 * - Better error handling and logging
 * - Code optimization and refactoring
 * - Removed shell_exec() vulnerability
 * 
 * Last Updated: 2026-05-28
 */

date_default_timezone_set('Asia/Manila');

// Configuration
define('UPLOAD_BASE_DIR', './upload/');
define('IMPORT_BASE_DIR', './upload/importloadprofile/');
define('LOG_FILE', '/var/www/html/lp/lp_upload_log.txt');
define('MAX_FILE_SIZE', 100 * 1024 * 1024); // 100 MB
define('ALLOWED_EXTENSIONS', ['txt', 'csv', 'xlsx', 'xls']);

// Include database connection
include('connection.php');

// Initialize response
$response = [
    'status' => 'FAILED',
    'message' => '',
    'file' => '',
    'timestamp' => date('Y-m-d H:i:s')
];

try {
    // 1. VALIDATE INPUT PARAMETERS
    $dest_dir = validateDestinationDirectory($_POST['dest_dir'] ?? '');
    $overwrite = (int)($_POST['overwrite'] ?? 0);
    
    if (!$dest_dir) {
        throw new Exception('Invalid destination directory parameter');
    }
    
    // 2. VALIDATE FILE UPLOAD
    if (!isset($_FILES['file_contents'])) {
        throw new Exception('No file was uploaded');
    }
    
    $file_error = (int)$_FILES['file_contents']['error'];
    if ($file_error !== UPLOAD_ERR_OK) {
        throw new Exception(getUploadErrorMessage($file_error));
    }
    
    // 3. VALIDATE FILE SIZE
    $file_size = (int)$_FILES['file_contents']['size'];
    if ($file_size > MAX_FILE_SIZE) {
        throw new Exception('File size exceeds maximum allowed: ' . formatBytes(MAX_FILE_SIZE));
    }
    
    if ($file_size === 0) {
        throw new Exception('Empty file uploaded');
    }
    
    // 4. VALIDATE FILENAME
    $original_filename = validateFileName($_FILES['file_contents']['name']);
    if (!$original_filename) {
        throw new Exception('Invalid filename');
    }
    
    // 5. VALIDATE FILE EXTENSION
    $file_extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
    if (!in_array($file_extension, ALLOWED_EXTENSIONS, true)) {
        throw new Exception('File type not allowed. Allowed: ' . implode(', ', ALLOWED_EXTENSIONS));
    }
    
    // 6. CREATE UPLOAD DIRECTORY
    $upload_dir = UPLOAD_BASE_DIR . $dest_dir . '/';
    if (!ensureDirectoryExists($upload_dir)) {
        throw new Exception('Failed to create upload directory');
    }
    
    // 7. RESOLVE ABSOLUTE PATHS
    $upload_dir_real = realpath($upload_dir);
    if ($upload_dir_real === false) {
        throw new Exception('Failed to resolve upload directory path');
    }
    
    $upload_file = $upload_dir_real . DIRECTORY_SEPARATOR . basename($original_filename);
    
/*    // 8. PREVENT DIRECTORY TRAVERSAL
    if (strpos(realpath($upload_file), realpath($upload_dir_real)) !== 0) {
        throw new Exception('Invalid file path - potential directory traversal attempt');
    }*/
    
    // 9. CHECK IF FILE EXISTS
    if (file_exists($upload_file) && !$overwrite) {
        throw new Exception('File already exists and overwrite is disabled');
    }
    
    // 10. MOVE UPLOADED FILE
    if (!move_uploaded_file($_FILES['file_contents']['tmp_name'], $upload_file)) {
        throw new Exception('Failed to move uploaded file to destination');
    }
    
    // 11. SET PROPER PERMISSIONS
    chmod($upload_file, 0644);
    
    // 12. PROCESS FILE (COPY TO IMPORT FOLDER IF NEEDED)
    $location_detail = basename($dest_dir);
    if ($dest_dir === 'lp' || strpos($dest_dir, 'lp') === 0) {
        $import_dir = IMPORT_BASE_DIR;
        if (ensureDirectoryExists($import_dir)) {
            $import_filename = $location_detail . '_' . $original_filename;
            $import_file = $import_dir . $import_filename;
            
            // Use PHP file copy instead of shell_exec (safer)
            if (copy($upload_file, $import_file)) {
                chmod($import_file, 0644);
                $response['import_file'] = $import_filename;
            } else {
                logUpload($dest_dir, $original_filename, $upload_file, 'WARNING', 'Import copy failed');
            }
        }
    }
    
    // 13. LOG SUCCESS
    logUpload($dest_dir, $original_filename, $upload_file, 'SUCCESS', '', $file_size, $overwrite);
    
    // 14. GENERATE RESPONSE
    $response['status'] = 'SUCCESS';
    $response['message'] = 'File uploaded successfully';
    $response['file'] = $original_filename;
    $response['size'] = formatBytes($file_size);
    $response['destination'] = $dest_dir;
    
} catch (Exception $e) {
    // LOG ERROR
    logUpload(
        $_POST['dest_dir'] ?? 'UNKNOWN',
        $_FILES['file_contents']['name'] ?? 'UNKNOWN',
        'N/A',
        'ERROR',
        $e->getMessage()
    );
    
    $response['status'] = 'FAILED';
    $response['message'] = $e->getMessage();
}

// 15. SEND RESPONSE
header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
exit;

// ============================================================================
// HELPER FUNCTIONS
// ============================================================================

/**
 * Validate destination directory parameter
 * Prevent directory traversal and invalid paths
 */
function validateDestinationDirectory($dest_dir) {
    if (empty($dest_dir)) {
        return false;
    }
    
    // Remove whitespace
    $dest_dir = trim($dest_dir);
    
    // Allow only alphanumeric, hyphens, and underscores
    if (!preg_match('/^[a-zA-Z0-9_\-\/]+$/', $dest_dir)) {
        return false;
    }
    
    // Prevent directory traversal
    if (strpos($dest_dir, '..') !== false || strpos($dest_dir, '//') !== false) {
        return false;
    }
    
    // Remove leading/trailing slashes
    $dest_dir = trim($dest_dir, '/');
    
    return $dest_dir ?: false;
}

/**
 * Validate filename
 * Remove path components and validate characters
 */
function validateFileName($filename) {
    if (empty($filename)) {
        return false;
    }
    
    // Remove path components
    $filename = basename($filename);
    
    // Check length
    if (strlen($filename) > 255) {
        return false;
    }
    
    // Allow alphanumeric, dots, hyphens, underscores
    if (!preg_match('/^[a-zA-Z0-9._\-]+$/', $filename)) {
        return false;
    }
    
    return $filename;
}

/**
 * Ensure directory exists and create if needed
 */
function ensureDirectoryExists($directory) {
    if (is_dir($directory)) {
        return true;
    }
    
    // Create directory with proper permissions
    if (!@mkdir($directory, 0755, true)) {
        return false;
    }
    
    return is_dir($directory);
}

/**
 * Get human-readable upload error message
 */
function getUploadErrorMessage($error_code) {
    $errors = [
        UPLOAD_ERR_OK => 'No error',
        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive',
        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE form directive',
        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'PHP extension blocked the upload'
    ];
    
    return $errors[$error_code] ?? 'Unknown upload error';
}

/**
 * Format bytes to human-readable format
 */
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB'];
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}

/**
 * Log upload activity
 */
function logUpload($dest_dir, $filename, $filepath, $status, $message = '', $file_size = 0, $overwrite = 0) {
    $timestamp = date('Y-m-d H:i:s');
    
    $log_entry = sprintf(
        "[%s] Status: %s | Dir: %s | File: %s | Size: %s | Overwrite: %d | Path: %s | Message: %s",
        $timestamp,
        $status,
        $dest_dir,
        $filename,
        formatBytes($file_size),
        $overwrite,
        $filepath,
        $message
    );
    
    // Append to log file
    @file_put_contents(
        LOG_FILE,
        $log_entry . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
}

?>
