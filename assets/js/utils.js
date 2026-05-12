/* ─────────────────────────────────────────────────────
   UTILS - Helper & Utility Functions
   ──────────────────────────────────────────────────── */

/**
 * Format angka dengan separator ribuan
 * @param {number|string} num - Angka yang akan diformat
 * @returns {string} - Angka terformat (e.g., "1,250")
 */
export function formatNumber(num) {
  return parseInt(num).toLocaleString();
}

/**
 * Generate ID unik
 * @returns {string} - ID unik berdasarkan timestamp
 */
export function generateId() {
  return Date.now().toString(36) + Math.random().toString(36).substr(2);
}

/**
 * Parse jam (HH:MM:SS) menjadi HH:MM
 * @param {string} time - Format HH:MM:SS
 * @returns {string} - Format HH:MM
 */
export function parseTime(time) {
  if (!time) return '';
  return time.substring(0, 5);
}

/**
 * Validasi apakah input valid
 * @param {string} value - Nilai yang akan divalidasi
 * @returns {boolean}
 */
export function isValidInput(value) {
  return value !== null && value !== undefined && value.toString().trim() !== '';
}

/**
 * Clone object deep
 * @param {object} obj - Object yang akan di-clone
 * @returns {object} - Clone dari object
 */
export function deepClone(obj) {
  return JSON.parse(JSON.stringify(obj));
}

/**
 * Debounce function
 * @param {function} func - Function yang akan di-debounce
 * @param {number} wait - Delay dalam ms
 * @returns {function} - Debounced function
 */
export function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

/**
 * Cari dalam array berdasarkan query
 * @param {array} data - Array data
 * @param {string} query - Query pencarian
 * @returns {array} - Data hasil filter
 */
export function searchData(data, query) {
  if (!query) return data;
  const q = query.toLowerCase();
  return data.filter(item =>
    Object.values(item).some(value =>
      String(value).toLowerCase().includes(q)
    )
  );
}

/**
 * Get DOM element dengan selama cek null
 * @param {string} id - Element ID
 * @returns {HTMLElement|null}
 */
export function getDOMElement(id) {
  const elem = document.getElementById(id);
  if (!elem) {
    console.warn(`Element dengan ID "${id}" tidak ditemukan`);
  }
  return elem;
}

/**
 * Set multiple element values
 * @param {object} values - {elementId: value, ...}
 */
export function setMultipleValues(values) {
  Object.entries(values).forEach(([id, value]) => {
    const elem = getDOMElement(id);
    if (elem) elem.value = value || '';
  });
}

/**
 * Get multiple element values
 * @param {array} ids - Array of element IDs
 * @returns {object} - {elementId: value, ...}
 */
export function getMultipleValues(ids) {
  const result = {};
  ids.forEach(id => {
    const elem = getDOMElement(id);
    if (elem) result[id] = elem.value;
  });
  return result;
}

/**
 * Clear multiple element values
 * @param {array} ids - Array of element IDs
 */
export function clearMultipleValues(ids) {
  ids.forEach(id => {
    const elem = getDOMElement(id);
    if (elem) elem.value = '';
  });
}

/**
 * Show notification/alert
 * @param {string} message - Pesan notifikasi
 * @param {string} type - 'success', 'error', 'warning', 'info'
 */
export function showNotification(message, type = 'success') {
  const toast = document.getElementById('pmToast');
  const toastTxt = document.getElementById('pmToastTxt');
  const toastIco = document.getElementById('pmToastIco');

  if (toast && toastTxt) {
    toastTxt.innerText = message;
    
    // Set icon & color based on type
    if (toastIco) {
      if (type === 'success') {
        toastIco.innerText = '✓';
        toast.style.borderLeft = '4px solid #16a34a';
      } else if (type === 'error') {
        toastIco.innerText = '✕';
        toast.style.borderLeft = '4px solid #dc2626';
      } else if (type === 'warning') {
        toastIco.innerText = '⚠';
        toast.style.borderLeft = '4px solid #ea6c00';
      } else {
        toastIco.innerText = 'ℹ';
        toast.style.borderLeft = '4px solid #2563eb';
      }
    }

    toast.classList.add('show');
    
    // Auto hide after 3 seconds
    setTimeout(() => {
      toast.classList.remove('show');
    }, 3000);
  }

  // Tambahkan browser alert agar lebih terlihat (Permintaan User)
  alert(message);

  // Tetap log ke console untuk debugging
  console.log(`[${type.toUpperCase()}] ${message}`);
}

/**
 * Confirm action
 * @param {string} message - Pesan konfirmasi
 * @returns {boolean}
 */
export function confirmAction(message) {
  return confirm(message);
}
