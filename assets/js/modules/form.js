/* ─────────────────────────────────────────────────────
   MODULES: FORM - Form Handling Functions
   ──────────────────────────────────────────────────── */

import { setMultipleValues, clearMultipleValues, getMultipleValues, getDOMElement, showNotification } from '../utils.js';
import { setSelectedRow, setEditMode } from '../state.js';

// Field IDs untuk form
const formFieldIds = [
  'tanggal', 'jam', 'roll_ke', 'group', 'mesin', 'lanjut',
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
    'jam': data.jam ? data.jam.substring(0, 5) : '',
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
  setEditMode(false);
  showNotification('Form dikosongkan.', 'info');
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
  const numericFields = { 'denier': 'Denier', 'panjang': 'Panjang', 'lebar': 'Lebar', 'berat': 'Berat' };
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
  if (editBtnMsg) editBtnMsg.value = 'Mode Edit Aktif';
  setEditMode(true);
}

/**
 * Disable edit mode
 */
export function disableEditMode() {
  const editBtnMsg = getDOMElement('lanjut');
  if (editBtnMsg) editBtnMsg.value = 'Edit';
  setEditMode(false);
}

/**
 * Focus ke field pertama
 */
export function focusFirstField() {
  const elem = getDOMElement(formFieldIds[0]);
  if (elem) elem.focus();
}
