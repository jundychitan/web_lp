# receive_file_rev2.php - Improved File Upload Handler

## Overview
`receive_file_rev2.php` is a completely refactored and hardened version of the original `receive_file.php`. It maintains the core functionality while introducing professional-grade security, error handling, and code quality improvements.

---

## Key Improvements

### 1. **Security Enhancements**

#### Input Validation
- **validateDestinationDirectory()**: Prevents directory traversal attacks
  - Blocks `..` and `//` sequences
  - Only allows alphanumeric, hyphens, underscores, and forward slashes
  - Sanitizes input before use
  
- **validateFileName()**: Protects against malicious filenames
  - Extracts basename (removes path components)
  - Enforces 255-character limit
  - Allows only safe characters (alphanumeric, dots, hyphens, underscores)

#### File Upload Validation
- Replaced `shell_exec()` with PHP's `copy()` function (eliminates command injection risk)
- File extension whitelist: only `[txt, csv, xlsx, xls]` allowed
- Maximum file size enforcement: 100 MB limit (configurable)
- File size zero check (prevents empty file uploads)

#### Path Security
- Uses `realpath()` to resolve symbolic links and prevent path manipulation
- Directory traversal detection before file move
- Safe path construction using `DIRECTORY_SEPARATOR`

### 2. **Error Handling**

#### Try-Catch Exception Model
```php
try {
    // All operations wrapped in try block
} catch (Exception $e) {
    // Centralized error handling
    logUpload(..., 'ERROR', $e->getMessage());
}
```

#### Comprehensive Error Messages
Maps all 8 PHP upload error codes to human-readable descriptions:
- UPLOAD_ERR_OK (0): No error
- UPLOAD_ERR_INI_SIZE (1): Exceeds php.ini limit
- UPLOAD_ERR_FORM_SIZE (2): Exceeds HTML form limit
- UPLOAD_ERR_PARTIAL (3): Partial upload
- UPLOAD_ERR_NO_FILE (4): No file uploaded
- UPLOAD_ERR_NO_TMP_DIR (6): Missing temp folder
- UPLOAD_ERR_CANT_WRITE (7): Cannot write to disk
- UPLOAD_ERR_EXTENSION (8): PHP extension blocked

### 3. **Code Quality Improvements**

#### Modular Design
- 6 dedicated helper functions for specific tasks
- Each function has single responsibility
- Well-documented with PHPDoc comments

#### Configuration Management
- Centralized constants at file top:
  ```php
  define('UPLOAD_BASE_DIR', './upload/');
  define('IMPORT_BASE_DIR', './upload/importloadprofile/');
  define('LOG_FILE', '/var/www/html/lp/lp_upload_log.txt');
  define('MAX_FILE_SIZE', 100 * 1024 * 1024);
  define('ALLOWED_EXTENSIONS', ['txt', 'csv', 'xlsx', 'xls']);
  ```
- Single point of configuration changes

#### JSON Response Format
Original script echoed plain text (`SUCCESS`/`FAILED`). New version returns structured JSON:
```json
{
  "status": "SUCCESS",
  "message": "File uploaded successfully",
  "file": "report.txt",
  "size": "2.5 MB",
  "destination": "lp",
  "timestamp": "2026-05-28 21:01:05",
  "import_file": "lp_report.txt"
}
```

### 4. **Logging Improvements**

#### Enhanced Log Format
**Original:**
```
OVERWRITE FILE 2026-05-28 21:01:05 | lp 1 /path/to/file /upload/lp/
```

**Improved:**
```
[2026-05-28 21:01:05] Status: SUCCESS | Dir: lp | File: report.txt | Size: 2.5 MB | Overwrite: 1 | Path: /var/www/html/lp/upload/lp/report.txt | Message: 
```

Benefits:
- Structured format easy to parse
- Includes file size information
- Clear status indicators
- Timestamp for every entry
- Extensible message field

### 5. **Feature Additions**

#### File Permissions Management
```php
chmod($upload_file, 0644);      // Set readable by all
chmod($import_file, 0644);      // Same for import copy
```

#### Graceful Import Handling
- Logs import failures as "WARNING" without stopping upload
- Main upload succeeds even if import copy fails
- Reports import filename in response if successful

#### File Size Reporting
- Converts bytes to human-readable format
- Shows "2.5 MB" instead of raw bytes in response

---

## Migration Guide

### For Direct Replacement
The new script maintains backward compatibility with POST parameters:
- `$_POST['dest_dir']` - destination directory (now validated)
- `$_POST['overwrite']` - overwrite flag
- `$_FILES['file_contents']` - uploaded file

### Response Format Change
**Update client-side code** to parse JSON instead of text:

**Old way:**
```php
if ($response === 'SUCCESS') { ... }
```

**New way:**
```php
$response = json_decode($output, true);
if ($response['status'] === 'SUCCESS') {
    echo "File: " . $response['file'];
}
```

### Configuration Adjustment
Before deploying, verify/adjust constants if paths differ:
```php
define('UPLOAD_BASE_DIR', './upload/');           // Change if needed
define('MAX_FILE_SIZE', 100 * 1024 * 1024);       // Adjust size limit
define('ALLOWED_EXTENSIONS', [...]); // Add/remove file types
```

---

## Function Reference

### `validateDestinationDirectory($dest_dir)`
Validates and sanitizes destination directory parameter.
- **Returns:** Sanitized directory name or `false` on invalid input
- **Security:** Blocks directory traversal attacks

### `validateFileName($filename)`
Sanitizes and validates uploaded filename.
- **Returns:** Safe filename or `false` if invalid
- **Security:** Prevents path injection and special characters

### `ensureDirectoryExists($directory)`
Creates directory if not exists with proper permissions.
- **Returns:** `true` if directory exists/created, `false` on failure
- **Permissions:** 0755 (rwxr-xr-x)

### `getUploadErrorMessage($error_code)`
Converts PHP error codes to human-readable messages.
- **Returns:** Description string
- **Handles:** All 8 possible PHP upload error codes

### `formatBytes($bytes, $precision = 2)`
Converts bytes to human-readable format.
- **Returns:** String like "2.5 MB" or "150 KB"
- **Example:** `formatBytes(2621440)` → `"2.5 MB"`

### `logUpload($dest_dir, $filename, $filepath, $status, $message, $file_size, $overwrite)`
Records upload activity to log file.
- **Parameters:**
  - `$status`: 'SUCCESS', 'FAILED', 'ERROR', or 'WARNING'
  - `$message`: Optional error/warning message
  - `$file_size`: File size in bytes
  - `$overwrite`: Boolean overwrite flag
- **Output:** Appends to LOG_FILE

---

## Security Checklist

- ✅ Input validation on all user-supplied data
- ✅ Directory traversal attack prevention
- ✅ File type whitelist enforcement
- ✅ File size limits
- ✅ Removed dangerous `shell_exec()` function
- ✅ Path safety with `realpath()` and `basename()`
- ✅ Proper file permissions (0644)
- ✅ Exception-based error handling
- ✅ Comprehensive audit logging
- ✅ No hardcoded credentials

---

## Performance Notes

- Minimal performance overhead compared to original
- File copy instead of shell_exec is actually faster
- JSON response encoding negligible
- Comprehensive logging has minimal I/O impact

---

## Testing Recommendations

1. **Happy Path:** Upload valid file with overwrite=0 and overwrite=1
2. **Edge Cases:** 
   - Empty filename
   - Maximum file size limit
   - Directory traversal attempts: `../../../etc/passwd`
   - Invalid extensions
3. **Error Scenarios:**
   - Missing dest_dir parameter
   - Missing file in POST
   - Non-existent destination (should auto-create)
4. **Integration:** Verify JSON response parsing in client application

---

## Backward Compatibility Notes

- **Breaking Change:** Response format changed from text to JSON
- **Non-Breaking:** POST parameters remain the same
- **Migration:** Update client code to parse JSON responses

---

**Version:** 2.0  
**Created:** 2026-05-28  
**Author:** System Improvements  
**License:** Same as parent application
