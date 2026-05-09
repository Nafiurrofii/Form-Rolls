/* ─────────────────────────────────────────────────────
   MODULES: STORAGE - LocalStorage Handling
   ──────────────────────────────────────────────────── */

const STORAGE_PREFIX = 'formroll_';

/**
 * Save data ke localStorage
 * @param {string} key - Key untuk menyimpan
 * @param {any} value - Value yang disimpan
 */
export function saveToStorage(key, value) {
  try {
    const fullKey = STORAGE_PREFIX + key;
    const serialized = JSON.stringify(value);
    localStorage.setItem(fullKey, serialized);
    console.log(`Saved to storage: ${fullKey}`);
    return true;
  } catch (error) {
    console.error('Error saving to storage:', error);
    return false;
  }
}

/**
 * Get data dari localStorage
 * @param {string} key - Key untuk diambil
 * @param {any} defaultValue - Default jika tidak ada
 * @returns {any} - Value dari storage
 */
export function getFromStorage(key, defaultValue = null) {
  try {
    const fullKey = STORAGE_PREFIX + key;
    const item = localStorage.getItem(fullKey);
    if (item === null) return defaultValue;
    return JSON.parse(item);
  } catch (error) {
    console.error('Error reading from storage:', error);
    return defaultValue;
  }
}

/**
 * Remove data dari localStorage
 * @param {string} key - Key untuk dihapus
 */
export function removeFromStorage(key) {
  try {
    const fullKey = STORAGE_PREFIX + key;
    localStorage.removeItem(fullKey);
    console.log(`Removed from storage: ${fullKey}`);
    return true;
  } catch (error) {
    console.error('Error removing from storage:', error);
    return false;
  }
}

/**
 * Clear semua data dengan prefix
 */
export function clearAllStorage() {
  try {
    const keys = Object.keys(localStorage);
    keys.forEach(key => {
      if (key.startsWith(STORAGE_PREFIX)) {
        localStorage.removeItem(key);
      }
    });
    console.log('Cleared all storage');
    return true;
  } catch (error) {
    console.error('Error clearing storage:', error);
    return false;
  }
}

/**
 * Save form data
 * @param {object} formData - Form data object
 */
export function saveFormData(formData) {
  return saveToStorage('formData', formData);
}

/**
 * Get form data yang tersimpan
 * @returns {object} - Saved form data
 */
export function getSavedFormData() {
  return getFromStorage('formData', {});
}

/**
 * Save last viewed row
 * @param {object} rowData - Row yang terakhir dilihat
 */
export function saveLastViewedRow(rowData) {
  return saveToStorage('lastViewedRow', rowData);
}

/**
 * Get last viewed row
 * @returns {object} - Last viewed row
 */
export function getLastViewedRow() {
  return getFromStorage('lastViewedRow', null);
}

/**
 * Save user preferences
 * @param {object} preferences - User preferences
 */
export function savePreferences(preferences) {
  return saveToStorage('preferences', preferences);
}

/**
 * Get user preferences
 * @returns {object} - User preferences
 */
export function getPreferences() {
  return getFromStorage('preferences', {
    perPage: 10,
    theme: 'light'
  });
}
