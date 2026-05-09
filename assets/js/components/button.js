/* ─────────────────────────────────────────────────────
   COMPONENTS: BUTTON - Reusable Button Component
   ──────────────────────────────────────────────────── */

import { showNotification, confirmAction } from '../utils.js';
import { getFormData, resetForm, validateForm, enableEditMode, disableEditMode } from '../modules/form.js';
import { saveRoll } from '../modules/api.js';
// NOTE: Do NOT import storage functions - we save to DATABASE via API, not localStorage!

/**
 * Create dan attach button handlers
 */
export function attachButtonHandlers() {
  // BARU - Reset form
  const btnBaru = document.querySelector('.btn-primary');
  if (btnBaru) {
    btnBaru.addEventListener('click', handleBaru);
  }

  // SIMPAN - Save form data
  const btnSimpan = document.querySelector('.btn-success');
  if (btnSimpan) {
    btnSimpan.addEventListener('click', handleSimpan);
  }

  // EDIT - Enable edit mode
  const btnEdit = document.querySelector('.btn-warning');
  if (btnEdit) {
    btnEdit.addEventListener('click', handleEditAction);
  }

  // HAPUS - Delete data
  const btnHapus = document.querySelector('.btn-danger');
  if (btnHapus) {
    btnHapus.addEventListener('click', handleHapus);
  }

  // KELUAR - Close/Exit
  const btnKeluar = document.querySelector('.btn-neutral');
  if (btnKeluar) {
    btnKeluar.addEventListener('click', handleKeluar);
  }

  // Edit button dalam form
  const btnFormEdit = document.querySelector('.btn-sm');
  if (btnFormEdit) {
    btnFormEdit.addEventListener('click', handleEdit);
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

async function handleSimpan() {
  console.log('🔘 Tombol SIMPAN diklik - akan menyimpan ke DATABASE via API');
  if (!validateForm()) {
    console.warn('⚠️ Validasi form gagal - tidak melanjutkan');
    return;
  }
  
  const formData = getFormData();
  console.log('📋 Data Form yang akan dikirim ke DATABASE:', formData);
  
  try {
    showNotification('Sedang menyimpan ke database...', 'info');
    console.log('⏳ Memanggil saveRoll() untuk simpan ke API/DATABASE');
    const result = await saveRoll(formData);
    
    console.log('📤 Response dari API:', result);
    if (result.status === 'success') {
      console.log('✅ BERHASIL MENYIMPAN KE DATABASE! ID:', result.data?.id);
      showNotification('✅ Data berhasil disimpan ke DATABASE!', 'success');
      
      // Refresh tabel data agar yang baru tersimpan muncul
      if (window.formRollApp && typeof window.formRollApp.refreshTableData === 'function') {
        console.log('🔄 Refresh tabel data...');
        await window.formRollApp.refreshTableData();
      }
      
      // Optional: Reset form setelah simpan
      // resetForm(); 
    } else {
      throw new Error('API returned non-success status: ' + result.status);
    }
  } catch (error) {
    console.error('❌ GAGAL MENYIMPAN KE DATABASE:', error);
    const errorMsg = buildDetailedErrorMessage(error);
    showNotification('❌ ' + errorMsg, 'error');
  }
}

function handleEdit() {
  enableEditMode();
  showNotification('Mode edit diaktifkan.', 'info');
}

function handleEditAction() {
  const formData = getFormData();
  if (Object.values(formData).some(v => v)) {
    enableEditMode();
    showNotification('Edit data terpilih.', 'info');
  } else {
    showNotification('Pilih atau isi data terlebih dahulu', 'warning');
  }
}

function handleHapus() {
  if (confirmAction('Hapus data ini?')) {
    resetForm();
    showNotification('Data dihapus.', 'success');
  }
}

function handleKeluar() {
  if (confirmAction('Keluar dari form?')) {
    resetForm();
    showNotification('Keluar dari form.', 'info');
  }
}

function handleLihat() {
  showNotification('Menampilkan data periode terpilih.', 'info');
  // Implementasi: Filter berdasarkan periode
}

function handleExcel() {
  showNotification('Mengekspor ke Excel...', 'info');
  // Implementasi: Buat export ke Excel
}

/**
 * Build detailed error message
 */
function buildDetailedErrorMessage(error) {
  let message = 'Gagal menyimpan data';
  
  if (error.message) {
    message += ': ' + error.message;
  }
  
  // Jika error dari network/fetch
  if (error instanceof TypeError) {
    message = 'Gagal koneksi ke server. Periksa URL API atau server status.';
  }
  
  console.error('Error details:', error);
  return message;
}

/**
 * Detach semua event handlers
 */
export function detachButtonHandlers() {
  // Implementasi jika diperlukan
}
