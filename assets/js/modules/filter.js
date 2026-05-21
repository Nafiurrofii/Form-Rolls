/* ─────────────────────────────────────────────────────
   MODULES: FILTER - Search & Filter Functions
   ──────────────────────────────────────────────────── */

import { getDOMElement, searchData } from '../utils.js';
import { getSourceData, setFilteredData, setCurrentPage } from '../state.js';

/**
 * Get query pencarian dari input
 * @returns {string} - Query string
 */
export function getSearchQuery() {
  const input = getDOMElement('search_input');
  return input ? input.value : '';
}

/**
 * Apply filter berdasarkan search query
 * @returns {array} - Filtered data
 */
export function applyFilter() {
  const query = getSearchQuery();
  const sourceData = getSourceData();
  const filtered = query ? searchData(sourceData, query) : sourceData;
  setFilteredData(filtered);
  setCurrentPage(1); // Reset ke halaman pertama
  return filtered;
}

/**
 * Clear semua filter
 */
export function clearFilter() {
  const input = getDOMElement('search_input');
  if (input) {
    input.value = '';
  }
  setFilteredData([]);
  setCurrentPage(1);
}

/**
 * Setup event listener untuk search
 * @param {function} callback - Callback ketika search berubah
 */
export function setupSearchListener(callback) {
  const input = getDOMElement('search_input');
  if (input) {
    input.addEventListener('input', () => {
      const filtered = applyFilter();
      if (callback) callback(filtered);
    });
  }
}

/**
 * Setup event listener untuk per_page select
 * @param {function} callback - Callback ketika berubah
 */
export function setupPerPageListener(callback) {
  const select = getDOMElement('per_page');
  if (select) {
    select.addEventListener('change', () => {
      if (callback) callback();
    });
  }
}
