# COMPREHENSIVE ANALYSIS REPORT: receive_file.php

## 1. PURPOSE & OVERVIEW
`receive_file.php` is a file upload handler script designed to manage file uploads in a load profile (LP) system. It handles file reception, directory management, file logging, and error handling for uploaded files.

---

## 2. INITIALIZATION & CONFIGURATION

**Timezone Setting:**
- Sets timezone to 'Asia/Manila' for consistent timestamp logging

**Dependencies:**
- Includes `connection.php` for database connectivity (though not actively used in current logic)

**Input Parameters:**
- `$_POST['dest_dir']` - Destination directory for file storage (trimmed)
- `$_POST['overwrite']` - Flag indicating whether to overwrite existing files (converted to numeric value)

---

## 3. DIRECTORY & PATH SETUP

```
Upload Base Path: ./upload/{dest_dir}/
```

**Directory Operations:**
1. Checks if target directory exists using `is_dir()`
2. Creates directory with `mkdir()` (permissions: 0755) if it doesn't exist
3. Resolves real path using `realpath()` to get absolute directory reference
4. Extracts directory depth information (7th level from path array)

---

## 4. FILE HANDLING WORKFLOW

### A. File Information Extraction
```
Filename Components:
├─ Full filename: $_FILES['file_contents']['name']
├─ Temporary location: $_FILES['file_contents']['tmp_name']
├─ Error code: $_FILES['file_contents']['error']
└─ Parsed segments: Split by underscore delimiter
```

### B. File Processing
1. **File Movement**: Uses `move_uploaded_file()` to transfer from temp directory to target location
2. **Path Generation**: Creates full file path using `realpath()` concatenation
3. **CSV Conversion Check**: Attempts to identify corresponding `.csv` file (replaces `.txt` extension)

### C. Conditional Copy Operation
- **Trigger**: If directory at path level 6 is 'lp'
- **Action**: Copies uploaded file to `/upload/importloadprofile/` with location prefix
- **Execution**: Uses `shell_exec()` for file copying via `cp` command

---

## 5. UPLOAD RESULT HANDLING

### Success Path (Error Code = 0)
```
Response: 'SUCCESS' (echoed to client)
Logging:  Timestamp + destination + overwrite flag + file path
Log Location: /var/www/html/lp/lp_upload_log.txt
```

### Failure Path (Error Code ≠ 0)
```
Response: 'FAILED' (echoed to client)
Error Mapping: Translates PHP error codes (1-8) to human-readable descriptions
```

**Error Codes Handled:**

| Code | Description |
|------|-------------|
| 1 | File exceeds `upload_max_filesize` |
| 2 | File exceeds form's `MAX_FILE_SIZE` |
| 3 | File only partially uploaded |
| 4 | No file uploaded |
| 6 | Missing temporary folder |
| 7 | Failed to write file to disk |
| 8 | PHP extension blocked upload |

---

## 6. LOGGING MECHANISM

**Log Format:**
```
" OVERWRITE FILE {timestamp} {dest_dir} {overwrite_flag} {uploadfile} {uploaddir} "
```

**Log Properties:**
- Appends to existing log file
- Uses file locking (`LOCK_EX`) to prevent concurrent write issues
- Includes full path information and operation metadata

---

## 7. SECURITY & OPERATIONAL NOTES

**Implemented Security:**
- ✅ Path normalization via `realpath()`
- ✅ Directory validation and creation
- ✅ File existence handling (overwrite capability)

**Potential Security Concerns:**
- ⚠️ No file type validation (accepts any uploaded file)
- ⚠️ Direct use of user input in shell_exec() (directory variable)
- ⚠️ No sanitization of `$_POST['dest_dir']`
- ⚠️ Weak error suppression operator usage (`@`)

---

## 8. COMPLETE WORKFLOW SEQUENCE

```
1. Receive POST request with file + destination directory
2. Parse and trim destination directory
3. Construct upload directory path (./upload/{dest_dir}/)
4. Generate timestamp for logging (Asia/Manila timezone)
5. Check if directory exists
   └─ If not: Create with 0755 permissions
6. Prepare upload path using realpath()
7. If parent directory is 'lp':
   └─ Schedule copy to importloadprofile folder
8. Attempt move_uploaded_file() from temp to target
9. Check upload error code:
   ├─ If 0 (success):
   │  ├─ Echo 'SUCCESS'
   │  ├─ Log operation details
   │  └─ Copy file to importloadprofile if applicable
   └─ If non-zero (failure):
      ├─ Echo 'FAILED'
      └─ Determine and map error description
```

---

## 9. CURRENTLY DISABLED FEATURES

The script contains commented-out conditional logic for:
- Alternative file handling when overwrite flag is false
- Dynamic file dating using exploded filename components
- Conditional copy operations based on file name structure

---

## 10. DATA FLOW DIAGRAM

```
HTTP POST Upload
      ↓
Parse Parameters (dest_dir, overwrite)
      ↓
Create/Validate Directory (./upload/{dest_dir}/)
      ↓
Move Uploaded File from Temp
      ↓
Success?
├─ YES → Log success + Copy to importloadprofile (if lp) + Echo 'SUCCESS'
└─ NO  → Map error code + Echo 'FAILED'
      ↓
Log to lp_upload_log.txt
```

---

## 11. SUMMARY

This script is the backend handler for a file upload system in a load profile management application. It:
- Manages directory structure automatically
- Handles file uploads with error checking
- Maintains detailed operation logs
- Supports file overwriting
- Provides error feedback via specific error codes
- Integrates with an import pipeline for load profile data

The script is currently **active** on your server and ready to process file uploads to the `/var/www/html/lp/` directory structure.

---

**Generated:** 2026-05-29  
**Analysis Date:** 2026-05-29  
**File Location:** /var/www/html/lp/receive_file.php
