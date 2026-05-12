/* ─────────────────────────────────────────────────────
   MODULES: FORM - Form Handling Functions
   ──────────────────────────────────────────────────── */

import { setMultipleValues, clearMultipleValues, getMultipleValues, getDOMElement, showNotification } from '../utils.js';
import { setSelectedRow, setEditMode, getSelectedRow, isEditMode } from '../state.js';

// Field IDs untuk form
const formFieldIds = [
  'tanggal', 'jam', 'roll_ke', 'group', 'mesin',
  'nama', 'denier', 'panjang', 'lebar',
  'anyam', 'berat', 'kode_trace', 'keterangan', 'pic'
];

/**
 * Get nilai form saat ini
 * @returns {object} - Semua nilai form
 */
export function getFormData() {
  return getMultipleValues(formFieldIds);
}

/**
 * Set nilai form
 * @param {object} data - Data yang akan diisi ke form
 */
export function setFormData(data) {
  const mappedData = {
    'tanggal': data.tgl,
    'jam': data.jam, // Gunakan jam asli dari data di database
    'roll_ke': data.roll,
    'group': data.shift,
    'mesin': data.mesin,
    'nama': data.nama,
    'denier': data.dnr,
    'panjang': data.pj,
    'lebar': data.lb,
    'anyam': data.anyam,
    'berat': data.br,
    'kode_trace': data.trace,
    'keterangan': data.keterangan || '',
    'pic': data.user,
  };
  setMultipleValues(mappedData);
}

/**
 * Reset form ke semua kosong
 */
export function resetForm() {
  clearMultipleValues(formFieldIds);
  setSelectedRow(null);
  disableEditMode(); // Memastikan UI reset (termasuk tombol Lanjut)

  // Reset status tombol BARU & LANJUT
  const btnBaru = document.querySelector('.btn-primary');
  if (btnBaru) btnBaru.classList.remove('active');

  const btnLanjut = document.querySelector('.btn-lanjut');
  if (btnLanjut) btnLanjut.classList.remove('active');

  const inputLanjut = document.getElementById('lanjut');
  if (inputLanjut) inputLanjut.value = 'Lanjut';

  toggleFormInputs(true, 'lanjut'); // Sesuai permintaan: mode netral hanya buka 6 field
  updateTimeNow();
  // showNotification('Form dikosongkan.', 'info');
}

/**
 * Update field jam & tanggal ke waktu sekarang
 */
export function updateTimeNow() {
  const jamInput = getDOMElement('jam');
  const tglInput = getDOMElement('tanggal');
  const now = new Date();

  // Jam berjalan REAL-TIME jika:
  // 1. Sedang dalam mode BARU (tombol Baru active / tidak ada data terpilih)
  // 2. Sedang dalam mode EDIT (tombol Edit active)
  // 3. Sedang dalam mode LANJUT (tombol Lanjut active)
  const isBaruActive = document.querySelector('.btn-primary.active');
  const isEditActive = document.querySelector('.btn-warning.active');
  const isLanjutActive = document.querySelector('.btn-lanjut.active');
  const noDataSelected = !getSelectedRow();

  if (jamInput && (isBaruActive || isEditActive || isLanjutActive || noDataSelected)) {
    const h = String(now.getHours()).padStart(2, '0');
    const m = String(now.getMinutes()).padStart(2, '0');
    const s = String(now.getSeconds()).padStart(2, '0');
    jamInput.value = `${h}:${m}:${s}`;
  }

  // Update Tanggal (hanya jika kosong, atau saat mode BARU)
  const y = now.getFullYear();
  const mo = String(now.getMonth() + 1).padStart(2, '0');
  const d = String(now.getDate()).padStart(2, '0');
  const fullDate = `${y}-${mo}-${d}`;

  if (tglInput && (!tglInput.value || (!getSelectedRow() && !isEditMode()))) {
    tglInput.value = fullDate;
  }

  // Update Periode Akhir (Selalu ikuti hari ini jika kosong atau belum disetel)
  const periodeAkhirInput = document.getElementById('periode_akhir');
  if (periodeAkhirInput && !periodeAkhirInput.value) {
    periodeAkhirInput.value = fullDate;
  }

  // Update Periode Awal (Default: 1 Minggu Sebelumnya)
  const periodeAwalInput = document.getElementById('periode_awal');
  if (periodeAwalInput && !periodeAwalInput.value) {
    const lastWeek = new Date();
    lastWeek.setDate(now.getDate() - 7);
    
    const ly = lastWeek.getFullYear();
    const lmo = String(lastWeek.getMonth() + 1).padStart(2, '0');
    const ld = String(lastWeek.getDate()).padStart(2, '0');
    periodeAwalInput.value = `${ly}-${lmo}-${ld}`;
  }
  
  // Update trace code juga jika memungkinkan
  updateTraceCode();
}

/**
 * Menjalankan clock real-time jika sedang mode BARU (tidak ada baris terpilih)
 */
export function startRealTimeClock() {
  setInterval(() => {
    // Panggil updateTimeNow setiap detik. 
    // Logika apakah jam harus update atau diam ditangani di dalam updateTimeNow().
    updateTimeNow();
  }, 1000);
}

/**
 * Validasi form - check required fields dan format data
 * @returns {boolean}
 */
export function validateForm() {
  const formData = getFormData();
  const hasValue = Object.values(formData).some(v => v && v.toString().trim() !== '');
  
  if (!hasValue) {
    showNotification('Isi minimal satu field!', 'warning');
    return false;
  }

  // Validasi required fields
  const requiredFields = {
    'tanggal': 'Tanggal harus diisi',
    'jam': 'Jam harus diisi',
    'nama': 'Nama produk harus diisi'
  };

  for (const [field, message] of Object.entries(requiredFields)) {
    if (!formData[field] || formData[field].toString().trim() === '') {
      showNotification(message, 'warning');
      return false;
    }
  }

  // Validasi format tanggal (YYYY-MM-DD)
  if (formData.tanggal && !/^\d{4}-\d{2}-\d{2}$/.test(formData.tanggal)) {
    showNotification('Format tanggal tidak valid (YYYY-MM-DD)', 'warning');
    return false;
  }

  // Validasi format jam (HH:MM atau HH:MM:SS)
  if (formData.jam && !/^\d{2}:\d{2}(:\d{2})?$/.test(formData.jam)) {
    showNotification('Format jam tidak valid (HH:MM)', 'warning');
    return false;
  }

  // Validasi angka positif untuk field tertentu
  const numericFields = { 'panjang': 'Panjang', 'lebar': 'Lebar' };
  for (const [field, label] of Object.entries(numericFields)) {
    if (formData[field] && (isNaN(formData[field]) || parseFloat(formData[field]) < 0)) {
      showNotification(`${label} harus berupa angka positif`, 'warning');
      return false;
    }
  }

  return true;
}

/**
 * Enable edit mode
 */
export function enableEditMode() {
  const editBtnMsg = getDOMElement('lanjut');
  if (editBtnMsg) editBtnMsg.value = 'Lanjut';

  const btnLanjut = document.querySelector('.btn-lanjut');
  // Jangan langsung active, biarkan user klik manual jika ingin mode Lanjut
  if (btnLanjut) {
    btnLanjut.classList.remove('active');
  }

  toggleFormInputs(true); // Buka form saat edit
  setEditMode(true);
}

/**
 * Disable edit mode
 */
export function disableEditMode() {
  const editBtnMsg = getDOMElement('lanjut');
  if (editBtnMsg) editBtnMsg.value = 'Lanjut';

  const btnLanjut = document.querySelector('.btn-lanjut');
  if (btnLanjut) {
    btnLanjut.classList.remove('active');
  }

  setEditMode(false);
}

/**
 * Focus ke field pertama
 */
export function focusFirstField() {
  const elem = getDOMElement(formFieldIds[0]);
  if (elem) elem.focus();
}

/**
 * Update kode trace secara otomatis berdasarkan tanggal, jam, group, mesin, roll_ke
 */
export function updateTraceCode() {
  // Generate otomatis hanya jika:
  // 1. Tidak ada baris yang dipilih (Mode BARU)
  // 2. ATAU sedang dalam mode edit/lanjut (isEditMode)
  if (getSelectedRow() && !isEditMode()) {
    return;
  }

  const tanggal = getDOMElement('tanggal')?.value || '';
  const jam = getDOMElement('jam')?.value || '';
  const group = getDOMElement('group')?.value || '';
  const mesin = getDOMElement('mesin')?.value || '';
  const roll = getDOMElement('roll_ke')?.value || '';

  if (!tanggal || !jam || !group || !mesin || !roll) return;

  // Format tanggal: YYYY-MM-DD -> YYMMDD
  const tglParts = tanggal.split('-');
  let tglFormatted = '';
  if (tglParts.length === 3) {
    tglFormatted = tglParts[0].substring(2) + tglParts[1] + tglParts[2];
  }

  // Format jam: HH:MM -> HHMM
  const jamFormatted = jam.replace(':', '').substring(0, 4);

  const traceCode = `${tglFormatted}-${jamFormatted}-${group}-${mesin}-${roll}`;
  
  const traceInput = getDOMElement('kode_trace');
  if (traceInput) {
    traceInput.value = traceCode;
  }
}

/**
 * Aktifkan atau nonaktifkan semua input form
 * @param {boolean} enabled 
 * @param {string} mode - 'full' atau 'lanjut'
 */
export function toggleFormInputs(enabled, mode = 'full') {
  const editableInLanjut = ['mesin', 'panjang', 'lebar', 'berat', 'group', 'pic'];

  formFieldIds.forEach(id => {
    const el = getDOMElement(id);
    if (!el) return;

    let shouldEnable = enabled;
    
    // Jika mode lanjut, hanya field tertentu yang bisa diedit
    if (enabled && mode === 'lanjut') {
      shouldEnable = editableInLanjut.includes(id);
    }

    el.readOnly = !shouldEnable;
    
    // Khusus untuk select (group, mesin)
    if (['group', 'mesin'].includes(id)) {
      el.disabled = !shouldEnable;
    }

    // Beri style visual jika readonly/disabled
    if (!shouldEnable) {
      el.classList.add('readonly-field');
    } else {
      el.classList.remove('readonly-field');
    }
  });
}

/**
 * Pasang event listener untuk update otomatis kode trace
 */
export function attachTraceCodeListeners() {
  const fields = ['tanggal', 'jam', 'group', 'mesin', 'roll_ke'];
  fields.forEach(id => {
    const el = getDOMElement(id);
    if (el) {
      el.addEventListener('input', updateTraceCode);
      el.addEventListener('change', updateTraceCode);
    }
  });
}
