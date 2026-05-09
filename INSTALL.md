# Form Roll - Complete Installation Guide

Panduan lengkap untuk setup project Form Roll (Frontend + Backend).

## 📋 Prerequisites

- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau MariaDB 10.3+
- Web Server (Apache, Nginx, atau PHP Built-in)
- Modern Web Browser

## 🚀 Quick Start

### 1. Clone / Download Project
```bash
cd /path/to/Form-Roll
```

### 2. Setup Database

#### Option A: PHPMyAdmin
1. Login ke PHPMyAdmin
2. Buat database baru: `form_roll`
3. Import file `backend/schema.sql`:
   - Pilih database `form_roll`
   - Tab SQL → Copy-paste isi `schema.sql` → Execute

#### Option B: MySQL CLI
```bash
mysql -u root -p < backend/schema.sql
```

#### Option C: Command Line
```bash
# Login ke MySQL
mysql -u root -p

# Run SQL commands
CREATE DATABASE form_roll CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE form_roll;
source backend/schema.sql;
```

### 3. Update Backend Configuration

Edit `backend/config/database.php`:

```php
define('DB_HOST', 'localhost');    // Your MySQL host
define('DB_NAME', 'form_roll');    // Database name
define('DB_USER', 'root');         // MySQL user
define('DB_PASS', '');             // MySQL password
define('DB_CHARSET', 'utf8mb4');   // Charset
```

### 4. Test API Backend

**Option A: Browser**
```
http://localhost/Form-Roll/backend/roll.php?action=get
```

**Option B: cURL**
```bash
curl "http://localhost/Form-Roll/backend/roll.php?action=get"
```

**Option C: API Tester UI**
```
http://localhost/Form-Roll/backend/api-tester.html
```

### 5. Open Frontend

```
http://localhost/Form-Roll/index.html
```

## 📁 Project Structure

```
Form-Roll/
│
├── index.html                    # Frontend entry point
├── README.md                     # Frontend documentation
├── INTEGRATION_GUIDE.md          # How to integrate
│
├── assets/
│   ├── css/
│   │   ├── variables.css        # CSS variables & theme
│   │   ├── components.css       # Component styles
│   │   └── main.css            # Layout & structure
│   │
│   └── js/
│       ├── app.js              # Main application
│       ├── state.js            # State management
│       ├── utils.js            # Utility functions
│       │
│       ├── modules/
│       │   ├── form.js         # Form handling
│       │   ├── table.js        # Table rendering
│       │   ├── filter.js       # Search & filter
│       │   └── storage.js      # LocalStorage
│       │
│       └── components/
│           ├── button.js       # Button handlers
│           └── modal.js        # Modal dialogs
│
├── backend/
│   ├── roll.php                # API entry point
│   ├── schema.sql              # Database schema
│   ├── api-tester.html         # API testing UI
│   ├── FormRollAPI.js          # JavaScript API wrapper
│   ├── README.md               # Backend documentation
│   │
│   ├── config/
│   │   └── database.php        # Database connection
│   │
│   ├── models/
│   │   └── Roll.php            # Roll model (CRUD)
│   │
│   └── controllers/
│       └── rollController.php   # Business logic
│
└── data/
    └── dummy.json              # Sample data
```

## 🔧 Configuration

### Backend Configuration

**File**: `backend/config/database.php`

```php
// Database credentials
define('DB_HOST', 'localhost');      // Change if needed
define('DB_NAME', 'form_roll');      // Database name
define('DB_USER', 'root');           // MySQL username
define('DB_PASS', 'password');       // MySQL password
define('DB_CHARSET', 'utf8mb4');     // Character set
```

### Environment Variables (Optional)

Create `.env` file in `backend/`:

```bash
cp backend/.env.example backend/.env
```

Edit `backend/.env`:
```
DB_HOST=localhost
DB_NAME=form_roll
DB_USER=root
DB_PASS=
```

Update `backend/config/database.php` to read from `.env`:
```php
$dotenv = parse_ini_file('.env');
define('DB_HOST', $dotenv['DB_HOST'] ?? 'localhost');
// ... dst
```

## 🚀 Running the Application

### Option 1: PHP Built-in Server

```bash
# From project root
php -S localhost:8000

# Or from backend
php -S localhost:8001 -t backend/
```

Then open browser:
- Frontend: `http://localhost:8000/`
- API: `http://localhost:8001/roll.php?action=get`

### Option 2: Apache/Nginx

Point your web server to project root directory.

Example Apache VirtualHost:
```apache
<VirtualHost *:80>
    ServerName form-roll.local
    DocumentRoot /path/to/Form-Roll
    
    <Directory /path/to/Form-Roll>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Option 3: XAMPP / WAMP / LAMP

1. Copy project ke `htdocs/` (XAMPP) atau `www/` (WAMP)
2. Start Apache & MySQL
3. Open: `http://localhost/Form-Roll/`

## ✅ Verification Checklist

After installation, verify:

- [ ] **Database**: Connect ke MySQL dan run `SELECT * FROM rolls;`
  ```bash
  mysql -u root -p form_roll -e "SELECT * FROM rolls;"
  ```

- [ ] **Backend API**: Test endpoint
  ```bash
  curl "http://localhost/Form-Roll/backend/roll.php?action=get"
  ```
  Should return JSON with rolls data

- [ ] **Frontend**: Open index.html
  - Form fields visible
  - Table displays sample data
  - Search works
  - Pagination works
  - Buttons respond

- [ ] **Integration**: 
  - Click row → loads form
  - Click SIMPAN → data saved (check database)
  - Check database: `SELECT * FROM rolls;`

## 🐛 Troubleshooting

### Issue: "Could not find driver"
**Cause**: PDO MySQL extension not enabled
**Solution**:
```bash
# Check PHP extensions
php -m | grep pdo_mysql

# Enable in php.ini
# Uncomment: extension=pdo_mysql
```

### Issue: "Access denied for user 'root'@'localhost'"
**Cause**: Wrong database credentials
**Solution**: Update `backend/config/database.php` with correct credentials

### Issue: "Table 'form_roll.rolls' doesn't exist"
**Cause**: Database schema not imported
**Solution**: Run `backend/schema.sql`

### Issue: Frontend shows no data
**Cause**: API not returning data
**Solution**:
1. Check browser console for errors
2. Verify API endpoint: `http://localhost/Form-Roll/backend/roll.php?action=get`
3. Check database connection in `config/database.php`

### Issue: CORS Error in Browser Console
**Cause**: Cross-origin request blocked
**Solution**: Update CORS headers di `backend/roll.php`:
```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
```

### Issue: 404 Not Found on API
**Cause**: Incorrect API URL
**Solution**:
- Check backend folder path
- Verify `roll.php` exists
- Check action parameter: `?action=get`

## 📊 Database Schema Overview

```sql
CREATE TABLE rolls (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tanggal DATE NOT NULL,
  jam TIME NOT NULL,
  roll INT NOT NULL,
  group_name VARCHAR(10),
  mesin VARCHAR(50),
  nama VARCHAR(100) NOT NULL,
  denier INT NOT NULL,
  panjang INT,
  lebar INT,
  anyam VARCHAR(50),
  berat FLOAT,
  trace_code VARCHAR(100),
  keterangan TEXT,
  pic VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## 🔒 Security Recommendations

1. **Change Database Password**
   ```php
   // backend/config/database.php
   define('DB_PASS', 'your_strong_password');
   ```

2. **Disable Debug Mode for Production**
   ```php
   // backend/config/database.php
   ini_set('display_errors', 0);
   ini_set('log_errors', 1);
   ```

3. **Use Environment Variables**
   ```bash
   # .env
   DB_HOST=localhost
   DB_NAME=form_roll
   DB_USER=formroll_user
   DB_PASS=strong_password_here
   ```

4. **Restrict API Access**
   ```php
   // Add to backend/roll.php
   $allowed_origins = ['http://localhost', 'http://127.0.0.1'];
   if (!in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
       die('Unauthorized');
   }
   ```

5. **Enable HTTPS in Production**
   - Use SSL certificate
   - Redirect HTTP to HTTPS
   - Update API URLs to use https://

## 📚 Documentation Files

| File | Purpose |
|------|---------|
| `README.md` | Frontend documentation |
| `backend/README.md` | Backend API documentation |
| `INTEGRATION_GUIDE.md` | How to integrate frontend & backend |
| `INSTALL.md` | This file |

## 🎓 Learning Resources

- [PHP PDO Documentation](https://www.php.net/manual/en/book.pdo.php)
- [REST API Best Practices](https://restfulapi.net/)
- [JavaScript Fetch API](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API)
- [MySQL Documentation](https://dev.mysql.com/doc/)

## 📞 Support & Help

### Check These First:
1. Browser Developer Tools (F12) → Console tab
2. PHP error log: `php -S localhost:8000` output
3. MySQL error log
4. Backend API response: `http://localhost/Form-Roll/backend/roll.php?action=get`

### Common Commands:

```bash
# Check PHP version
php -v

# Check MySQL version
mysql --version

# Test database connection
mysql -u root -p form_roll -e "SELECT COUNT(*) FROM rolls;"

# View PHP error log
tail -f /var/log/php-errors.log

# Test API with curl
curl -X GET "http://localhost/Form-Roll/backend/roll.php?action=get"
```

## 🎉 Next Steps

After successful installation:

1. **Customize Database**: Add more fields if needed
2. **Extend API**: Add more endpoints
3. **Enhance Frontend**: Add more features
4. **Deploy**: Push to production server
5. **Monitor**: Setup error logging and monitoring

---

**Installation Status**: ✅ Complete  
**Version**: 1.0  
**Last Updated**: May 2026

For issues or questions, refer to:**
- `README.md` - Frontend guide
- `backend/README.md` - Backend guide
- `INTEGRATION_GUIDE.md` - Integration steps
