/* ─────────────────────────────────────────────────────
   APP.JS - Application Entry Point
   ──────────────────────────────────────────────────── */

// Import all modules
import { getFilteredData, setFilteredData, setCurrentPage } from './state.js';
import { renderTable, getEntriesPerPage } from './modules/table.js';
import { setupSearchListener, setupPerPageListener, applyFilter } from './modules/filter.js';
import { attachButtonHandlers } from './components/button.js';
import { getPreferences, savePreferences } from './modules/storage.js';
import { showNotification } from './utils.js';
import { fetchRolls } from './modules/api.js';
import { resetForm } from './modules/form.js';

/**
 * Initialize aplikasi
 */
async function initializeApp() {
  console.log('🚀 Initializing Form Roll Application...');

  loadPreferences();
  setupEventListeners();

  // Fetch data dari API
  await refreshTableData();

  attachButtonHandlers();
  
  // Kosongkan form pada saat awal aplikasi dimuat
  resetForm();
  
  console.log('✅ App initialized successfully');
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
  setupSearchListener(() => {
    const perPage = getEntriesPerPage();
    renderTableData(getFilteredData(), perPage);
  });

  setupPerPageListener(() => {
    const perPage = getEntriesPerPage();
    renderTableData(getFilteredData(), perPage);
  });

  window.addEventListener('pageChanged', () => {
    const perPage = getEntriesPerPage();
    renderTableData(getFilteredData(), perPage);
  });
}

/**
 * Render table dengan data
 */
function renderTableData(data, perPage) {
  const total = data.length;
  renderTable(data, perPage, total);
}

/**
 * ✨ REFRESH DATA - Reload dari API
 */
async function refreshTableData() {
  try {
    console.log('🔄 Refreshing data...');
    const data = await fetchRolls();
    setFilteredData(data);
    setCurrentPage(1);
    
    const perPage = getEntriesPerPage();
    renderTableData(data, perPage);
    
    // showNotification('✅ Data diperbarui', 'success'); // Dihapus karena mengganggu user
    console.log(`✅ Loaded ${data.length} records`);
  } catch (error) {
    console.error('❌ Fetch error:', error);
    showNotification('Gagal memuat data', 'error');
  }
}

/**
 * ✨ UPDATE DATA - Ganti row tertentu
 */
function updateSingleRow(updatedRow) {
  const currentData = getFilteredData();
  const index = currentData.findIndex(row => row.reg === updatedRow.reg);
  
  if (index !== -1) {
    currentData[index] = updatedRow;
    setFilteredData([...currentData]);
    
    const perPage = getEntriesPerPage();
    renderTableData(currentData, perPage);
    
    showNotification('Data row berhasil diupdate', 'success');
  }
}

/**
 * ✨ RESET FILTER - Tampilkan semua data
 */
function resetFilterAndRefresh() {
  setCurrentPage(1);
  refreshTableData();
}

/**
 * Save preferences
 */
window.addEventListener('beforeunload', () => {
  const prefs = getPreferences();
  prefs.perPage = getEntriesPerPage();
  savePreferences(prefs);
});

/**
 * Error handlers
 */
window.addEventListener('error', (event) => {
  console.error('❌ Global error:', event.error);
});

window.addEventListener('unhandledrejection', (event) => {
  console.error('❌ Unhandled promise rejection:', event.reason);
});

// ─── START APPLICATION ───────────────────────────
document.addEventListener('DOMContentLoaded', initializeApp);

// ─── EXPORT UNTUK DEBUGGING & USAGE ───────────────
window.formRollApp = {
  // State management
  getFilteredData,
  setFilteredData,
  
  // Render functions
  renderTableData,
  getEntriesPerPage,
  
  // API & Data management
  refreshTableData,
  updateSingleRow,
  resetFilterAndRefresh,
  fetchRolls,
  applyFilter,
  
  // Diagnostic functions
  testAPI: async function() {
    console.log('🧪 Testing API connection...');
    try {
      const response = await fetch('backend/roll.php?action=get');
      console.log('Status:', response.status);
      const data = await response.json();
      console.log('Response:', data);
      return data;
    } catch (error) {
      console.error('API Error:', error);
      return error;
    }
  },
  
  testSave: async function(testData) {
    console.log('🧪 Testing SAVE to API...');
    const { saveRoll } = await import('./modules/api.js');
    const data = testData || {
      tanggal: new Date().toISOString().split('T')[0],
      jam: new Date().toTimeString().substring(0, 5),
      roll_ke: 1,
      group: 'A',
      mesin: '16',
      nama: 'TEST PRODUCT',
      denier: 600,
      panjang: 100,
      lebar: 50,
      anyam: 'NORMAL',
      berat: 20.5,
      kode_trace: 'TEST001',
      keterangan: 'Test dari console',
      pic: 'UTM'
    };
    console.log('📤 Sending data:', data);
    try {
      const result = await saveRoll(data);
      console.log('✅ Save result:', result);
      return result;
    } catch (error) {
      console.error('❌ Save error:', error);
      return error;
    }
  }
};

console.log('📦 Form Roll App loaded');
console.log('💡 Gunakan window.formRollApp.testAPI() atau window.formRollApp.testSave() untuk test');