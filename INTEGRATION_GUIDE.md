# Backend - Frontend Integration Guide

Panduan cara mengintegrasikan Backend PHP dengan Frontend JavaScript yang sudah dibuat.

## 📌 Overview

Backend API sudah siap untuk menerima dan mengirim data. Frontend JavaScript perlu diupdate untuk menggunakan API Backend.

## 🔗 API Connection

### Backend Structure
```
backend/
├── roll.php (Entry point: http://localhost/Form-Roll/backend/roll.php)
├── config/database.php
├── models/Roll.php
├── controllers/rollController.php
└── FormRollAPI.js (JavaScript helper)
```

### Frontend Structure
```
assets/
└── js/
    ├── app.js
    ├── state.js
    ├── utils.js
    ├── modules/
    │   ├── form.js
    │   ├── table.js
    │   ├── filter.js
    │   └── storage.js
    └── components/
        ├── button.js
        └── modal.js
```

## 🚀 Setup

### 1. Add API Service ke Frontend

Copy file `FormRollAPI.js` dari backend ke `assets/js/`:

```
assets/
├── js/
│   ├── FormRollAPI.js  ← Add this file
│   ├── app.js
│   └── ...
```

### 2. Update app.js untuk menggunakan API

Edit `assets/js/app.js`:

```javascript
/* ─────────────────────────────────────────────────────
   APP.JS - Application Entry Point (Updated)
   ──────────────────────────────────────────────────── */

import { rawData, totalVirtualRecords, getFilteredData, setFilteredData } from './state.js';
import { renderTable, getEntriesPerPage } from './modules/table.js';
import { setupSearchListener, setupPerPageListener, applyFilter } from './modules/filter.js';
import { attachButtonHandlers } from './components/button.js';
import { getPreferences, savePreferences } from './modules/storage.js';

// ✨ ADD: Import or create API instance
class FormRollAPI {
  constructor(baseUrl = '../backend/roll.php') {
    this.baseURL = baseUrl;
  }
  
  async request(action, options = {}) {
    // ... (copy dari FormRollAPI.js)
  }
  
  // ... (semua methods dari FormRollAPI.js)
}

const api = new FormRollAPI('../backend/roll.php');

// ✨ Store API instance globally for use in other modules
window.formRollAPI = api;

/**
 * Initialize aplikasi
 */
async function initializeApp() {
  console.log('🚀 Initializing Form Roll Application...');

  // Step 1: Load preferences
  loadPreferences();

  // Step 2: Setup event listeners
  setupEventListeners();

  // ✨ Step 3: Fetch data dari API Backend
  await loadDataFromBackend();

  // Step 4: Render initial table
  const perPage = getEntriesPerPage();
  renderTableData(getFilteredData(), perPage);

  // Step 5: Attach button handlers
  attachButtonHandlers();

  console.log('✅ App initialized successfully');
}

/**
 * ✨ NEW: Load data dari Backend API
 */
async function loadDataFromBackend() {
  try {
    const response = await api.getAllRolls(1, 1000); // Load all data
    
    if (response.status === 'success' && response.data) {
      // Update state dengan data dari API
      setFilteredData(response.data);
      console.log(`📊 Loaded ${response.data.length} records from API`);
    } else {
      console.warn('Failed to load data from API:', response.message);
    }
  } catch (error) {
    console.error('Error loading data from API:', error);
  }
}

/**
 * Load user preferences
 */
function loadPreferences() {
  const prefs = getPreferences();
  console.log('📋 Preferences loaded:', prefs);

  const perPageSelect = document.getElementById('per_page');
  if (perPageSelect) {
    perPageSelect.value = prefs.perPage || 10;
  }
}

/**
 * Setup all event listeners
 */
function setupEventListeners() {
  // Search input listener
  setupSearchListener(() => {
    const perPage = getEntriesPerPage();
    const filtered = getFilteredData().length > 0 ? getFilteredData() : applyFilter();
    renderTableData(filtered, perPage);
  });

  // Per page select listener
  setupPerPageListener(() => {
    const perPage = getEntriesPerPage();
    const filtered = getFilteredData().length > 0 ? getFilteredData() : [];
    // Load from API if needed
    loadDataFromBackend().then(() => {
      const perPage = getEntriesPerPage();
      renderTableData(getFilteredData(), perPage);
    });
  });

  // Page change event
  window.addEventListener('pageChanged', () => {
    const perPage = getEntriesPerPage();
    const filtered = getFilteredData();
    renderTableData(filtered, perPage);
  });
}

/**
 * Render table dengan data
 */
function renderTableData(data, perPage) {
  if (data.length === 0) {
    data = getFilteredData();
  }
  renderTable(data, perPage, totalVirtualRecords);
}

/**
 * Save preferences ketika aplikasi ditutup
 */
window.addEventListener('beforeunload', () => {
  const prefs = getPreferences();
  prefs.perPage = getEntriesPerPage();
  savePreferences(prefs);
});

/**
 * Global error handler
 */
window.addEventListener('error', (event) => {
  console.error('❌ Global error:', event.error);
});

/**
 * Unhandled promise rejection handler
 */
window.addEventListener('unhandledrejection', (event) => {
  console.error('❌ Unhandled promise rejection:', event.reason);
});

// ─── START APPLICATION ───────────────────────────
document.addEventListener('DOMContentLoaded', initializeApp);

// Export untuk debugging
window.formRollApp = {
  getFilteredData,
  renderTableData,
  getEntriesPerPage,
  loadDataFromBackend, // ✨ NEW
  api // ✨ NEW
};

console.log('📦 Form Roll App loaded, awaiting DOM content...');
```

### 3. Update button.js untuk menggunakan API

Edit `assets/js/components/button.js`:

```javascript
/* ─────────────────────────────────────────────────────
   COMPONENTS: BUTTON - Update untuk API
   ──────────────────────────────────────────────────── */

import { showNotification, confirmAction } from '../utils.js';
import { getFormData, resetForm, validateForm } from '../modules/form.js';

/**
 * Attach button handlers dengan API integration
 */
export function attachButtonHandlers() {
  // ✨ Get API instance
  const api = window.formRollAPI;
  
  // BARU button
  const btnBaru = document.querySelector('.btn-primary');
  if (btnBaru) {
    btnBaru.addEventListener('click', handleBaru);
  }

  // SIMPAN button - ✨ UPDATE dengan API
  const btnSimpan = document.querySelector('.btn-success');
  if (btnSimpan) {
    btnSimpan.addEventListener('click', () => handleSimpan(api));
  }

  // EDIT button
  const btnEdit = document.querySelector('.btn-warning');
  if (btnEdit) {
    btnEdit.addEventListener('click', () => handleEditAction(api));
  }

  // HAPUS button - ✨ UPDATE dengan API
  const btnHapus = document.querySelector('.btn-danger');
  if (btnHapus) {
    btnHapus.addEventListener('click', () => handleHapus(api));
  }

  // KELUAR button
  const btnKeluar = document.querySelector('.btn-neutral');
  if (btnKeluar) {
    btnKeluar.addEventListener('click', handleKeluar);
  }

  // LIHAT button
  const btnLihat = document.querySelector('.btn-tool.lihat');
  if (btnLihat) {
    btnLihat.addEventListener('click', handleLihat);
  }

  // EXCEL button
  const btnExcel = document.querySelector('.btn-tool.excel');
  if (btnExcel) {
    btnExcel.addEventListener('click', handleExcel);
  }
}

/**
 * ─── HANDLER FUNCTIONS ───────────────────────────
 */

function handleBaru() {
  if (confirmAction('Kosongkan semua field?')) {
    resetForm();
  }
}

// ✨ UPDATE: SIMPAN dengan API
async function handleSimpan(api) {
  if (!validateForm()) return;
  
  const formData = getFormData();
  
  try {
    // Jika ada ID (edit), gunakan update
    const selectedRow = window.formRollApp?.selectedRow;
    let response;
    
    if (selectedRow?.id) {
      response = await api.updateRoll(selectedRow.id, formData);
    } else {
      // Jika baru, gunakan create
      response = await api.createRoll(formData);
    }
    
    if (response.status === 'success') {
      showNotification(response.message || 'Data berhasil disimpan!', 'success');
      // Reload data dari API
      if (window.formRollApp?.loadDataFromBackend) {
        await window.formRollApp.loadDataFromBackend();
      }
      resetForm();
    } else {
      showNotification(response.message || 'Gagal menyimpan data', 'error');
    }
  } catch (error) {
    showNotification('Error: ' + error.message, 'error');
  }
}

// ... (handlers lainnya tetap sama atau update sesuai kebutuhan)

// ✨ UPDATE: HAPUS dengan API
async function handleHapus(api) {
  const selectedRow = window.formRollApp?.selectedRow;
  
  if (!selectedRow?.id) {
    showNotification('Pilih data terlebih dahulu', 'warning');
    return;
  }
  
  if (confirmAction('Hapus data ini?')) {
    try {
      const response = await api.deleteRoll(selectedRow.id);
      
      if (response.status === 'success') {
        showNotification(response.message || 'Data dihapus.', 'success');
        resetForm();
        // Reload data dari API
        if (window.formRollApp?.loadDataFromBackend) {
          await window.formRollApp.loadDataFromBackend();
        }
      } else {
        showNotification(response.message || 'Gagal menghapus data', 'error');
      }
    } catch (error) {
      showNotification('Error: ' + error.message, 'error');
    }
  }
}

// ... (handlers lainnya)
```

## 📝 Usage Examples

### Create New Roll
```javascript
const api = window.formRollAPI;

await api.createRoll({
  tanggal: '2026-05-07',
  jam: '07:30:00',
  roll: 100,
  nama: 'PP BIRU',
  denier: 1200,
  mesin: '20'
});
```

### Get All Rolls
```javascript
const response = await api.getAllRolls(1, 10, 'PUTIH');
console.log(response.data); // Array of rolls
console.log(response.pagination); // Pagination info
```

### Update Roll
```javascript
const response = await api.updateRoll(1, {
  nama: 'PP PUTIH UPDATED',
  berat: 99.9
});
```

### Delete Roll
```javascript
const response = await api.deleteRoll(1);
console.log(response.message); // "Data berhasil dihapus"
```

### Delete Multiple
```javascript
const response = await api.deleteMultiple([1, 2, 3]);
console.log(response.affected_rows); // 3
```

## 🔍 Testing

### Method 1: API Tester UI
Open `backend/api-tester.html` in browser for interactive testing

### Method 2: Browser Console
```javascript
// Test API
const api = window.formRollAPI;

// Get all
await api.getAllRolls();

// Get one
await api.getRoll(1);

// Create
await api.createRoll({ tanggal: '2026-05-07', nama: 'TEST', denier: 1000 });

// Update
await api.updateRoll(1, { nama: 'UPDATED' });

// Delete
await api.deleteRoll(1);
```

### Method 3: Postman
- GET: `http://localhost/Form-Roll/backend/roll.php?action=get`
- POST: `http://localhost/Form-Roll/backend/roll.php?action=store`
- PUT: `http://localhost/Form-Roll/backend/roll.php?action=update&id=1`
- DELETE: `http://localhost/Form-Roll/backend/roll.php?action=delete&id=1`

## ⚠️ CORS & Security

Jika frontend dan backend di origin berbeda, update header di `backend/roll.php`:

```php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
```

## 🛠️ Troubleshooting

### "CORS Error"
- Check origin di backend/roll.php CORS headers
- Ensure API URL path is correct

### "Database Connection Failed"
- Check database credentials di config/database.php
- Ensure MySQL is running
- Check database exists

### "Invalid JSON"
- Ensure Content-Type header is application/json
- Validate JSON format

### "404 Not Found"
- Check action parameter is correct
- Verify roll.php exists in backend folder

## 📚 File Reference

| File | Purpose |
|------|---------|
| `backend/FormRollAPI.js` | JavaScript wrapper untuk API |
| `backend/roll.php` | API entry point |
| `backend/config/database.php` | Database connection |
| `backend/models/Roll.php` | CRUD operations |
| `backend/controllers/rollController.php` | Business logic |
| `backend/api-tester.html` | Interactive API tester |

## ✅ Integration Checklist

- [ ] Database created dan populated
- [ ] Backend credentials updated
- [ ] FormRollAPI.js copied ke assets/js/
- [ ] app.js updated dengan API integration
- [ ] button.js updated untuk use API
- [ ] API endpoints tested di browser
- [ ] Frontend loads data dari backend saat first load
- [ ] Create, Read, Update, Delete working dengan API
- [ ] Error handling implemented
- [ ] Performance optimized (pagination, searching)

---

**Status**: ✅ Ready for Integration  
**Version**: 1.0
