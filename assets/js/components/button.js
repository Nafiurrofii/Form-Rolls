/* ─────────────────────────────────────────────────────
   COMPONENTS: BUTTON - Reusable Button Component
   ──────────────────────────────────────────────────── */

import { showNotification, confirmAction, setMultipleValues } from '../utils.js';
import { getFormData, setFormData, resetForm, validateForm, enableEditMode, disableEditMode, updateTraceCode, updateTimeNow, toggleFormInputs } from '../modules/form.js';
import { saveRoll, deleteRoll } from '../modules/api.js';
import { isEditMode, setEditMode, getSelectedRow } from '../state.js';
import { openPrintModal } from './printModal.js';
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
  const btnFormEdit = document.querySelector('.btn-lanjut');
  if (btnFormEdit) {
    btnFormEdit.addEventListener('click', handleLanjut);
  }

  // RESET FILTER button
  const btnResetFilter = document.querySelector('.btn-tool.reset-filter');
  if (btnResetFilter) {
    btnResetFilter.addEventListener('click', handleResetFilter);
  }

  // LIHAT button
  const btnLihat = document.querySelector('.btn-tool.lihat');
  if (btnLihat) {
    btnLihat.addEventListener('click', handleLihat);
  }

  // Auto-filter saat tanggal dipilih
  const periodeAwal = document.getElementById('periode_awal');
  const periodeAkhir = document.getElementById('periode_akhir');
  if (periodeAwal && periodeAkhir) {
    const applyFilter = () => {
      if (periodeAwal.value && periodeAkhir.value) {
        if (window.formRollApp && typeof window.formRollApp.refreshTableData === 'function') {
          // showNotification('Menerapkan filter periode...', 'info');
          window.formRollApp.refreshTableData(periodeAwal.value, periodeAkhir.value);
        }
      }
    };
    periodeAwal.addEventListener('change', applyFilter);
    periodeAkhir.addEventListener('change', applyFilter);
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
  const btnBaru = document.querySelector('.btn-primary');
  if (!btnBaru) return;

  const wasActive = btnBaru.classList.contains('active');

  if (wasActive) {
    // Jika sudah aktif, klik lagi untuk mematikan mode BARU (Kembali ke Normal)
    resetForm();
    // showNotification('Mode Baru dibatalkan', 'info');
  } else {
    // Jika belum aktif, aktifkan mode BARU
    resetForm();
    btnBaru.classList.add('active');
    toggleFormInputs(true, 'full'); // Buka kunci input penuh untuk data baru
    // showNotification('Silakan input data baru', 'info');
  }
}

async function handleSimpan() {
  console.log('🔘 Tombol SIMPAN diklik - akan menyimpan ke DATABASE via API');
  if (!validateForm()) {
    console.warn('⚠️ Validasi form gagal - tidak melanjutkan');
    return;
  }

  const formData = getFormData();
  
  // CEK: Apakah ada mode (BARU/EDIT/LANJUT) yang sedang aktif?
  const anyModeActive = document.querySelector('.btn-primary.active, .btn-warning.active, .btn-lanjut.active');
  
  if (!anyModeActive) {
    console.log('ℹ️ Tidak ada mode aktif (Baru/Edit/Lanjut). Hanya menampilkan modal cetak.');
    const selected = getSelectedRow();
    
    openPrintModal({
      ...formData,
      register: selected ? (selected.reg || '—') : '—',
      barcode: selected ? (selected.barcode || '—') : '—'
    });
    return;
  }

  console.log('📋 Data Form yang akan dikirim ke DATABASE:', formData);

  try {
    const editing = isEditMode();
    const selected = getSelectedRow();
    const rollId = (editing && selected) ? selected.id : null;

    if (editing && !rollId) {
      showNotification('❌ ID Data tidak ditemukan untuk diupdate.', 'error');
      return;
    }

    // showNotification('Sedang menyimpan ke database...', 'info');
    console.log('⏳ Memanggil saveRoll() untuk simpan ke API/DATABASE');
    const result = await saveRoll(formData, rollId);

    console.log('📤 Response dari API:', result);
    if (result.status === 'success') {
      console.log('✅ BERHASIL MENYIMPAN KE DATABASE! ID:', result.data?.id);
      const successMsg = '✅ Data berhasil ' + (editing ? 'diperbarui' : 'disimpan') + ' ke DATABASE!';
      // showNotification(successMsg, 'success');

      // Refresh tabel data
      if (window.formRollApp && typeof window.formRollApp.refreshTableData === 'function') {
        console.log('🔄 Refresh tabel data...');
        await window.formRollApp.refreshTableData();
      }

      if (editing) disableEditMode();

      // Buka print modal dengan data yang baru disimpan
      openPrintModal({
        ...formData,
        // Barcode dan Register otomatis sama dengan nilai URUT dari database
        // Jika sedang EDIT, ambil dari data yang sudah ada di baris tersebut
        register: editing ? (selected.reg || '—') : (result.data?.id || '—'),
        barcode: editing ? (selected.barcode || '—') : (result.data?.id || '—')
      });

      // Reset form setelah modal dibuka
      resetForm();
    } else {
      throw new Error('API returned non-success status: ' + result.status);
    }
  } catch (error) {
    console.error('❌ GAGAL MENYIMPAN KE DATABASE:', error);
    const errorMsg = buildDetailedErrorMessage(error);
    showNotification('❌ ' + errorMsg, 'error');
  }
}

function handleLanjut() {
  const btnLanjut = document.querySelector('.btn-lanjut');
  if (!btnLanjut) return;

  const selected = getSelectedRow();

  // Jika tidak ada data yang dipilih di tabel, jangan aktifkan mode lanjut
  if (!selected) {
    showNotification('Pilih data di tabel terlebih dahulu', 'warning');
    // Pastikan UI tidak dalam status active jika gagal
    btnLanjut.classList.remove('active');
    return;
  }

  // Gunakan toggle UI
  const isActive = btnLanjut.classList.toggle('active');

  if (isActive) {
    // Mode ON: Aktifkan mode edit dulu baru update nilai
    setEditMode(true);

    // Hanya buka input tertentu untuk mode Lanjut
    toggleFormInputs(true, 'lanjut');

    // Update jam ke waktu sekarang agar trace code real-time
    updateTimeNow();

    // Set nilai roll_ke menjadi (nilai asli + 1)
    const nextRoll = parseInt(selected.roll || 0) + 1;

    setMultipleValues({ 'roll_ke': nextRoll });
    updateTraceCode();

    // showNotification('Mode Lanjut: Roll +1', 'info');
  } else {
    // Mode OFF: Kunci kembali form
    disableEditMode();
    toggleFormInputs(false);
    
    // Reset form ke data asli baris yang dipilih jika ada
    const selected = getSelectedRow();
    if (selected) {
      setFormData(selected);
    } else {
      resetForm();
    }
  }
}

function handleEditAction() {
  const btnEdit = document.querySelector('.btn-warning');
  if (!btnEdit) return;

  const isActive = btnEdit.classList.toggle('active');

  if (isActive) {
    const selected = getSelectedRow();
    
    // Validasi: Harus pilih data dari tabel dulu
    if (selected) {
      enableEditMode();
      
      // Matikan mode lain agar tidak bentrok
      document.querySelector('.btn-primary')?.classList.remove('active');
      document.querySelector('.btn-lanjut')?.classList.remove('active');
    } else {
      showNotification('Pilih data di tabel terlebih dahulu', 'warning');
      btnEdit.classList.remove('active');
    }
  } else {
    // Mode OFF: Kunci kembali form
    disableEditMode();
    toggleFormInputs(false);
    
    // Reset form ke data asli baris yang dipilih jika ada
    const selected = getSelectedRow();
    if (selected) {
      setFormData(selected);
    } else {
      resetForm();
    }
  }
}

async function handleHapus() {
  const selected = getSelectedRow();
  
  if (!selected) {
    showNotification('Pilih data di tabel yang akan dihapus terlebih dahulu!', 'warning');
    return;
  }

  if (confirmAction(`Hapus data dengan Register: ${selected.reg}?`)) {
    try {
      // showNotification('Sedang menghapus data...', 'info');
      const result = await deleteRoll(selected.id);
      
      if (result.status === 'success') {
        showNotification('✅ Data berhasil dihapus dari database', 'success');
        
        // Refresh tabel
        if (window.formRollApp && typeof window.formRollApp.refreshTableData === 'function') {
          await window.formRollApp.refreshTableData();
        }
        
        resetForm();
      } else {
        throw new Error(result.message || 'Gagal menghapus data');
      }
    } catch (error) {
      console.error('❌ Delete error:', error);
      showNotification('Gagal menghapus data: ' + error.message, 'error');
    }
  }
}

function handleKeluar() {
  if (confirmAction('Keluar dari form?')) {
    resetForm();
    showNotification('Keluar dari form.', 'info');
  }
}

function handleLihat() {
  const startInput = document.getElementById('periode_awal');
  const endInput = document.getElementById('periode_akhir');
  
  if (!startInput.value || !endInput.value) {
    showNotification('Pilih periode awal dan akhir terlebih dahulu!', 'warning');
    return;
  }
  
  if (window.formRollApp && typeof window.formRollApp.refreshTableData === 'function') {
    showNotification('Menarik data berdasarkan periode...', 'info');
    window.formRollApp.refreshTableData(startInput.value, endInput.value);
  } else {
    showNotification('Aplikasi belum siap. Silakan refresh halaman.', 'error');
  }
}

function handleResetFilter() {
  const startInput = document.getElementById('periode_awal');
  const endInput = document.getElementById('periode_akhir');

  let hasFilter = false;
  if (startInput && startInput.value) { startInput.value = ''; hasFilter = true; }
  if (endInput && endInput.value) { endInput.value = ''; hasFilter = true; }

  if (window.formRollApp && typeof window.formRollApp.refreshTableData === 'function') {
    if (hasFilter) {
      showNotification('Filter di-reset. Menampilkan semua data.', 'info');
    }
    window.formRollApp.refreshTableData();
  } else {
    showNotification('Aplikasi belum siap. Silakan refresh halaman.', 'error');
  }
}

function handleExcel() {
  const startInput = document.getElementById('periode_awal');
  const endInput = document.getElementById('periode_akhir');

  // Mengambil data yang saat ini aktif di tabel (setelah filter/pencarian dll)
  const appState = window.formRollApp;
  if (!appState || !appState.getFilteredData) return;

  const data = appState.getFilteredData();
  if (!data || data.length === 0) {
    showNotification('Tidak ada data untuk diekspor', 'warning');
    return;
  }

  showNotification('Mengekspor ke Excel (XLSX)...', 'info');

  // Header Excel (Kolom Tabel)
  const headers = ['NO', 'TANGGAL', 'JAM', 'ROLL', 'SHIFT', 'MESIN', 'NAMA', 'DENIER', 'PANJANG', 'LEBAR', 'ANYAM', 'BERAT', 'TRACE CODE', 'REGISTER', 'KETERANGAN', 'PIC'];

  // Map data ke bentuk array untuk Excel
  const rows = data.map((item, index) => [
    index + 1,
    item.tgl,
    item.jam,
    item.roll,
    item.shift,
    item.mesin,
    item.nama,
    item.dnr,
    item.pj,
    item.lb,
    item.anyam,
    item.br,
    item.trace,
    item.reg,
    item.keterangan || '',
    item.user || ''
  ]);

  // Cek apakah SheetJS tersedia
  if (typeof XLSX === 'undefined') {
    alert('Library Excel (SheetJS) belum dimuat. Periksa koneksi internet.');
    return;
  }

  // Gabungkan header dan baris data
  const worksheetData = [headers, ...rows];

  // Buat worksheet dan workbook
  const worksheet = XLSX.utils.aoa_to_sheet(worksheetData);
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, "Data Roll");

  // Tentukan nama file
  let fileName = 'Data_Roll_Produksi.xlsx';
  if (startInput && endInput && startInput.value && endInput.value) {
    fileName = `Data_Roll_${startInput.value}_sd_${endInput.value}.xlsx`;
  }

  // Trigger download file .xlsx
  XLSX.writeFile(workbook, fileName);
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
