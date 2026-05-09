# Form Roll - Application Modular

Aplikasi Form Roll yang telah di-refactor menjadi struktur modular menggunakan ES Modules (import/export).

## 📁 Struktur Project

```
form-roll-app/
│
├── index.html                    # HTML entry point
│
├── assets/
│   ├── css/
│   │   ├── variables.css        # CSS variables, warna, font, spacing
│   │   ├── components.css       # Stilir button, input, form element
│   │   └── main.css             # Layout utama, grid, struktur halaman
│   │
│   ├── js/
│   │   ├── app.js               # Application entry point
│   │   ├── state.js             # Global state management
│   │   ├── utils.js             # Helper & utility functions
│   │   │
│   │   ├── modules/             # Business logic modules
│   │   │   ├── form.js          # Form handling (get, set, reset, validate)
│   │   │   ├── table.js         # Table rendering & pagination
│   │   │   ├── filter.js        # Search & filter logic
│   │   │   └── storage.js       # localStorage handling
│   │   │
│   │   └── components/          # Reusable UI components
│   │       ├── button.js        # Button handlers & events
│   │       └── modal.js         # Modal dialog component
│   │
│   ├── fonts/                   # Font files (optional)
│   └── images/                  # Image assets (optional)
│
├── data/
│   └── dummy.json              # Data baku (optional, untuk future use)
│
└── README.md                   # Dokumentasi project
```

## 🎯 Penjelasan Pembagian File

### CSS Files

#### `variables.css`
- **Tanggung jawab**: Menyimpan semua variable CSS (warna, font, spacing, shadow)
- **Isi**: CSS custom properties (`:root`), color scheme, typography
- **Alasan**: Centralized theme management, mudah update warna/font global

#### `components.css`
- **Tanggung jawab**: Styling untuk komponen UI yang reusable
- **Isi**: Styling untuk button, input, select, field-row, badge, pagination
- **Alasan**: Komponen yang bisa digunakan di berbagai tempat

#### `main.css`
- **Tanggung jawab**: Layout struktur halaman utama
- **Isi**: Topbar, container, card, form grid, table, media queries
- **Alasan**: Struktur layout yang spesifik untuk halaman ini

### JavaScript Modules

#### `state.js` - Global State
- **Tanggung jawab**: Centralized state management
- **Isi**: rawData, appState object, getter/setter functions
- **Exports**: 
  - `rawData` - Sample data (20 records)
  - `totalVirtualRecords` - Virtual record count (1250)
  - `appState` - Global state object
  - `setCurrentPage()`, `getCurrentPage()`
  - `setFilteredData()`, `getFilteredData()`
  - `setSelectedRow()`, `getSelectedRow()`
  - `setEditMode()`, `isEditMode()`
  - `resetState()`

#### `utils.js` - Utility Functions
- **Tanggung jawab**: Helper functions yang reusable
- **Exports**:
  - `formatNumber()` - Format angka dengan separator
  - `generateId()` - Generate ID unik
  - `parseTime()` - Parse jam format
  - `isValidInput()` - Validasi input
  - `deepClone()` - Clone object deep
  - `debounce()` - Debounce function
  - `searchData()` - Cari dalam array
  - `getDOMElement()` - Safe DOM access
  - `setMultipleValues()` - Set multiple element values
  - `getMultipleValues()` - Get multiple element values
  - `clearMultipleValues()` - Clear multiple values
  - `showNotification()` - Notification utility
  - `confirmAction()` - Confirmation dialog

#### `modules/form.js` - Form Handling
- **Tanggung jawab**: Semua logic terkait form
- **Exports**:
  - `getFormData()` - Get semua nilai form
  - `setFormData(data)` - Set nilai form dari row data
  - `resetForm()` - Reset semua field ke kosong
  - `validateForm()` - Validasi form minimal ada value
  - `enableEditMode()` - Enable edit mode
  - `disableEditMode()` - Disable edit mode
  - `focusFirstField()` - Focus ke field pertama

#### `modules/table.js` - Table & Pagination
- **Tanggung jawab**: Render table dan pagination
- **Exports**:
  - `renderTable(data, perPage, totalVirtual)` - Render table
  - `renderPagination(totalPages, currentPage)` - Render pagination
  - `getEntriesPerPage()` - Get entries per page dari select

#### `modules/filter.js` - Search & Filter
- **Tanggung jawab**: Filter dan search logic
- **Exports**:
  - `getSearchQuery()` - Get search input value
  - `applyFilter()` - Apply filter berdasarkan query
  - `clearFilter()` - Clear all filters
  - `setupSearchListener(callback)` - Setup search event listener
  - `setupPerPageListener(callback)` - Setup per-page select listener

#### `modules/storage.js` - LocalStorage
- **Tanggung jawab**: Semua localStorage handling
- **Exports**:
  - `saveToStorage(key, value)` - Save ke localStorage
  - `getFromStorage(key, defaultValue)` - Get dari localStorage
  - `removeFromStorage(key)` - Remove dari localStorage
  - `clearAllStorage()` - Clear semua data
  - `saveFormData(formData)` - Save form data
  - `getSavedFormData()` - Get saved form data
  - `saveLastViewedRow(rowData)` - Save last viewed row
  - `getLastViewedRow()` - Get last viewed row
  - `savePreferences(prefs)` - Save user preferences
  - `getPreferences()` - Get user preferences

#### `components/button.js` - Button Component
- **Tanggung jawab**: Semua button handler dan event attachment
- **Exports**:
  - `attachButtonHandlers()` - Attach semua button event listeners
  - `detachButtonHandlers()` - Detach event listeners
- **Fitur**: BARU, SIMPAN, EDIT, HAPUS, KELUAR, LIHAT, EXCEL buttons

#### `components/modal.js` - Modal Component
- **Tanggung jawab**: Modal dialog functionality
- **Exports**:
  - `createModal(id, title, content, buttons)` - Create modal element
  - `showModal(id)` - Show modal
  - `closeModal(id)` - Close modal
  - `showConfirmModal(message, onConfirm, onCancel)` - Confirmation modal
  - `showAlertModal(title, message, type)` - Alert modal
  - `showFormModal(title, fields, onSubmit)` - Form modal
  - `submitFormModal(modalId, fields, callback)` - Submit form modal
  - `confirmModalAction(modalId, callback)` - Helper untuk confirm action

#### `app.js` - Application Entry Point
- **Tanggung jawab**: Initialize aplikasi dan orchestrate modules
- **Fitur**:
  - `initializeApp()` - Initialize semua module
  - `loadPreferences()` - Load user preferences
  - `setupEventListeners()` - Setup semua event listener
  - `renderTableData(data, perPage)` - Render table dengan data
  - Global error handling
  - Window object untuk debugging: `window.formRollApp`

## 🚀 Cara Menggunakan

### 1. Development
```bash
# Buka index.html di browser (support modern browser dengan ES Modules)
# Atau gunakan live server
npm install -g live-server
live-server .
```

### 2. Import Module
```javascript
// Di dalam file JS lain
import { getFormData, resetForm } from './modules/form.js';
import { formatNumber, showNotification } from './utils.js';

// Gunakan function
formatNumber(1250); // Output: "1,250"
```

### 3. State Management
```javascript
import { appState, setCurrentPage, getCurrentPage } from './state.js';

// Get page
const page = getCurrentPage(); // 1

// Set page
setCurrentPage(2);

// Get updated page
const newPage = getCurrentPage(); // 2
```

## ✅ Fitur Yang Tetap Berjalan

Semua fitur asli **tetap berfungsi dengan baik**:

✓ **Form Input** - Semua field form berfungsi normal  
✓ **Data Table** - Tampilan table dengan 20 sample data  
✓ **Pagination** - Navigasi halaman (1-7)  
✓ **Search** - Cari data berdasarkan input  
✓ **Per Page Select** - Pilih jumlah entries  
✓ **Row Click** - Load row data ke form  
✓ **Buttons** - BARU, SIMPAN, EDIT, HAPUS, KELUAR  
✓ **Tools** - LIHAT, EXCEL buttons  
✓ **Responsive** - Responsive layout dengan CSS Grid & Media Query  

## 🔧 Maintenance & Extension

### Menambah Function Baru di Module
```javascript
// Di modules/form.js
export function newFunction() {
  // implementasi
}
```

### Import di File Lain
```javascript
import { newFunction } from './modules/form.js';

// Gunakan
newFunction();
```

### Update State
```javascript
import { appState, setCurrentPage } from './state.js';

// Tambah property baru di appState
appState.newProperty = value;

// Atau buat getter/setter
export function setNewProperty(value) {
  appState.newProperty = value;
}
```

## 📊 Dependencies

**Tidak ada external dependency!** Hanya menggunakan:
- HTML5
- CSS3 (Custom Properties)
- ES Modules (JavaScript 2015+)
- LocalStorage API

## 🎨 CSS Architecture

### Variables (`:root`)
- Color scheme (blue, green, red, yellow, gray)
- Typography (IBM Plex Sans, IBM Plex Mono)
- Spacing & Effects (gap, shadow, radius)

### Responsive Design
- `1100px` breakpoint: 4 column → 2 column
- `640px` breakpoint: 2 column → 1 column
- Mobile-first approach

## 📝 Notes

1. **Data Sample**: Menggunakan 20 record yang di-virtual menjadi 1250 record
2. **localStorage Prefix**: Semua data disimpan dengan prefix `formroll_`
3. **Error Handling**: Semua error di-log ke console (production bisa update)
4. **Performance**: Debounce search untuk optimize re-render

## ⚡ Performance Tips

1. Gunakan `debounce()` untuk event yang frequently fired (search, resize)
2. Limit pagination items yang rendered (hanya 7 buttons max)
3. LocalStorage store hanya essential data
4. Modular import memastikan hanya code yang dibutuhkan saja yang loaded

## 🐛 Debugging

Akses debug object di console:
```javascript
// Di browser console
formRollApp.rawData              // Lihat semua raw data
formRollApp.getFilteredData()    // Lihat filtered data
formRollApp.renderTableData(data, 10)  // Manual render
formRollApp.getEntriesPerPage()  // Get entries per page
```

## 📄 License

MIT License - Bebas digunakan dan dimodifikasi

---

**Last Updated**: May 2026  
**Version**: 2.0 (Modular Refactor)  
**Status**: ✅ All features working
