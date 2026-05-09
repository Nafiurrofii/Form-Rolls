# Form Roll - Backend API (PHP Native)

REST-like API sederhana, clean, dan modular menggunakan PHP native tanpa framework.

## 📁 Struktur Backend

```
backend/
├── config/
│   └── database.php          # Database connection (PDO)
│
├── models/
│   └── Roll.php              # Roll data model (CRUD)
│
├── controllers/
│   └── rollController.php    # Business logic & validation
│
├── roll.php                  # API entry point & routing
├── schema.sql                # Database schema & sample data
└── README.md                 # This file
```

## 🚀 Setup

### 1. Setup Database

**Opsi A: MySQL CLI**
```bash
# Login ke MySQL
mysql -u root -p

# Run SQL schema
source backend/schema.sql;
```

**Opsi B: PHPMyAdmin**
1. Copy-paste isi `schema.sql` ke tab SQL
2. Execute

### 2. Update Database Configuration

Edit `backend/config/database.php`:

```php
define('DB_HOST', 'localhost');    // Your host
define('DB_NAME', 'form_roll');    // Your database
define('DB_USER', 'root');         // Your user
define('DB_PASS', '');             // Your password
```

### 3. Test API

Option A: cURL
```bash
curl "http://localhost/Form-Roll/backend/roll.php?action=get"
```

Option B: Browser
```
http://localhost/Form-Roll/backend/roll.php?action=get
```

Option C: Postman
- URL: `http://localhost/Form-Roll/backend/roll.php?action=get`
- Method: GET

## 📡 API Endpoints

### Base URL
```
http://localhost/Form-Roll/backend/roll.php
```

### 1. GET All Rolls (Read - List)
```
GET /roll.php?action=get
Query Parameters:
  - page: int (default: 1)
  - limit: int (default: 10, max: 100)
  - search: string (optional)

Example:
  /roll.php?action=get
  /roll.php?action=get&page=1&limit=20
  /roll.php?action=get&search=PUTIH
  /roll.php?action=get&page=2&limit=5&search=mesin
```

**Response Success:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "tanggal": "2026-05-06",
      "jam": "06:09:55",
      "roll": 4,
      "group_name": "B",
      "mesin": "16",
      "nama": "PP PUTIH JUMBO",
      "denier": 1600,
      "panjang": 500,
      "lebar": 180,
      "anyam": "197",
      "berat": "78.6",
      "trace_code": "06-0609-C-16-4",
      "keterangan": "K10/52.5",
      "pic": "197",
      "created_at": "2026-05-06 10:30:00",
      "updated_at": "2026-05-06 10:30:00"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 100,
    "total_pages": 10
  }
}
```

### 2. GET Single Roll (Read - Detail)
```
GET /roll.php?action=get_one&id=1
Query Parameters:
  - id: int (required)

Example:
  /roll.php?action=get_one&id=5
```

**Response Success:**
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "tanggal": "2026-05-06",
    "jam": "06:09:55",
    ...
  }
}
```

### 3. GET Statistics
```
GET /roll.php?action=statistics

Example:
  /roll.php?action=statistics
```

**Response Success:**
```json
{
  "status": "success",
  "data": {
    "total_rolls": 100,
    "total_days": 15,
    "total_machines": 42,
    "avg_weight": "75.5",
    "max_weight": "200.5",
    "min_weight": "15.2"
  }
}
```

### 4. CREATE Roll (Create)
```
POST /roll.php?action=store
Content-Type: application/json

Body:
{
  "tanggal": "2026-05-06",
  "jam": "06:09:55",
  "roll": 4,
  "group_name": "B",
  "mesin": "16",
  "nama": "PP PUTIH JUMBO",
  "denier": 1600,
  "panjang": 500,
  "lebar": 180,
  "anyam": "197",
  "berat": 78.6,
  "trace_code": "06-0609-C-16-4",
  "keterangan": "K10/52.5",
  "pic": "197"
}
```

**Response Success:**
```json
{
  "status": "success",
  "message": "Data berhasil disimpan",
  "id": 101
}
```

**Response Error:**
```json
{
  "status": "error",
  "message": "Validation failed: Tanggal, Nama, and Denier are required"
}
```

### 5. UPDATE Roll (Update)
```
POST /roll.php?action=update&id=1
Content-Type: application/json

Body (partial update allowed):
{
  "nama": "PP PUTIH BARU",
  "berat": 85.5,
  "keterangan": "Updated"
}
```

**Response Success:**
```json
{
  "status": "success",
  "message": "Data berhasil diupdate"
}
```

### 6. DELETE Single Roll (Delete)
```
GET /roll.php?action=delete&id=1
-- or --
DELETE /roll.php?action=delete&id=1

Example:
  /roll.php?action=delete&id=5
```

**Response Success:**
```json
{
  "status": "success",
  "message": "Data berhasil dihapus"
}
```

### 7. DELETE Multiple Rolls (Delete Batch)
```
POST /roll.php?action=delete_multiple
Content-Type: application/json

Body:
{
  "ids": [1, 2, 3, 4, 5]
}
```

**Response Success:**
```json
{
  "status": "success",
  "message": "Data berhasil dihapus",
  "affected_rows": 5
}
```

## 🔧 Integration dengan Frontend

### Using Fetch API

**GET All**
```javascript
fetch('backend/roll.php?action=get&page=1&limit=10')
  .then(res => res.json())
  .then(data => console.log(data));
```

**POST Create**
```javascript
const payload = {
  tanggal: '2026-05-06',
  nama: 'PP PUTIH',
  denier: 1600,
  // ... other fields
};

fetch('backend/roll.php?action=store', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify(payload)
})
  .then(res => res.json())
  .then(data => console.log(data));
```

**PUT Update**
```javascript
const payload = {
  nama: 'PP PUTIH UPDATED',
  berat: 85.5
};

fetch('backend/roll.php?action=update&id=1', {
  method: 'PUT',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify(payload)
})
  .then(res => res.json())
  .then(data => console.log(data));
```

**DELETE**
```javascript
fetch('backend/roll.php?action=delete&id=1', {
  method: 'DELETE'
})
  .then(res => res.json())
  .then(data => console.log(data));
```

## 📊 Data Validation

### Required Fields (Create)
- `tanggal` (DATE format: YYYY-MM-DD)
- `nama` (String, not empty)
- `denier` (Integer)

### Optional Fields
- `jam`, `roll`, `group_name`, `mesin`, `panjang`, `lebar`
- `anyam`, `berat`, `trace_code`, `keterangan`, `pic`

### Validation Rules
- `tanggal`: Must be YYYY-MM-DD format
- `denier`, `panjang`, `lebar`: Must be numeric
- String fields: Trimmed and sanitized
- Whitelist fields: Only known fields are accepted

## 🔒 Security Features

1. **Prepared Statements** - Prevents SQL injection
2. **Input Validation** - Checks required fields and format
3. **Field Whitelist** - Only allows known fields
4. **Error Logging** - Log errors without exposing sensitive info
5. **CORS Headers** - Allows cross-origin requests (optional, configure as needed)

## ⚠️ Performance Optimization

1. **Pagination** - Default limit 10, max 100
2. **Database Indexes** - Created on frequently searched columns
   - `idx_tanggal` - Date searches
   - `idx_mesin` - Machine searches
   - `idx_nama` - Name searches
   - `ft_search` - Full-text search
3. **Limit Queries** - Prevent fetching too much data

## 🐛 Error Handling

### HTTP Status Codes
- `200` - Success
- `400` - Bad Request (validation error)
- `404` - Not Found (action not found)
- `405` - Method Not Allowed
- `500` - Internal Server Error

### Error Response Format
```json
{
  "status": "error",
  "message": "Error description"
}
```

## 📝 Database Schema

| Field | Type | Nullable | Notes |
|-------|------|----------|-------|
| id | INT | NO | Primary Key, Auto Increment |
| tanggal | DATE | NO | |
| jam | TIME | NO | |
| roll | INT | NO | |
| group_name | VARCHAR(10) | YES | |
| mesin | VARCHAR(50) | YES | |
| nama | VARCHAR(100) | NO | |
| denier | INT | NO | |
| panjang | INT | YES | |
| lebar | INT | YES | |
| anyam | VARCHAR(50) | YES | |
| berat | FLOAT | YES | |
| trace_code | VARCHAR(100) | YES | |
| keterangan | TEXT | YES | |
| pic | VARCHAR(50) | YES | |
| created_at | TIMESTAMP | NO | Auto on insert |
| updated_at | TIMESTAMP | NO | Auto on update |

## 🔄 File Dependencies

```
roll.php (Entry Point)
  ├── config/database.php (Database connection)
  │   └── PDO (PHP built-in)
  │
  └── controllers/rollController.php (Business Logic)
      └── models/Roll.php (CRUD Operations)
          └── PDO (PHP built-in)
```

## 📚 PHP Extensions Required

- `PDO` (PHP Data Objects)
- `php_pdo_mysql` (MySQL driver for PDO)

Check installation:
```bash
php -m | grep -i pdo
```

## 🧪 Testing Checklist

- [ ] GET /roll.php?action=get (Fetch all)
- [ ] GET /roll.php?action=get&page=2&limit=5 (Pagination)
- [ ] GET /roll.php?action=get&search=PUTIH (Search)
- [ ] GET /roll.php?action=get_one&id=1 (Fetch single)
- [ ] GET /roll.php?action=statistics (Statistics)
- [ ] POST /roll.php?action=store (Create)
- [ ] POST /roll.php?action=update&id=1 (Update)
- [ ] GET /roll.php?action=delete&id=1 (Delete)
- [ ] POST /roll.php?action=delete_multiple (Delete batch)

## 📞 Support

For errors, check:
1. Database connection in `config/database.php`
2. Error logs in PHP error log file
3. Browser console for JSON parsing errors
4. Ensure all required fields are provided

---

**Version**: 1.0  
**Last Updated**: May 2026  
**Status**: ✅ Production Ready
