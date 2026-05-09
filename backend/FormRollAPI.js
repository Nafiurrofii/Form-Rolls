/* ─────────────────────────────────────────────────────
   FRONTEND API SERVICE - Call Backend API
   ──────────────────────────────────────────────────── */

/**
 * API Service untuk Form Roll Backend
 * Gunakan dalam frontend (assets/js/modules atau components)
 */

class FormRollAPI {
  
  /**
   * Constructor
   * @param {string} baseUrl - Base API URL (default: ../backend/roll.php)
   */
  constructor(baseUrl = '../backend/roll.php') {
    this.baseURL = baseUrl;
  }
  
  /**
   * Make API request
   * @param {string} action - API action
   * @param {object} options - Request options
   * @returns {Promise}
   */
  async request(action, options = {}) {
    try {
      const {
        method = 'GET',
        params = {},
        body = null,
        headers = {}
      } = options;
      
      // Build URL
      let url = `${this.baseURL}?action=${action}`;
      
      // Add query params untuk GET requests
      if (method === 'GET' && Object.keys(params).length > 0) {
        const queryString = new URLSearchParams(params).toString();
        url += `&${queryString}`;
      }
      
      // Build fetch options
      const fetchOptions = {
        method,
        headers: {
          'Content-Type': 'application/json',
          ...headers
        }
      };
      
      // Add body untuk non-GET requests
      if (method !== 'GET' && body) {
        fetchOptions.body = JSON.stringify(body);
      }
      
      // Make request
      const response = await fetch(url, fetchOptions);
      
      // Parse JSON
      const data = await response.json();
      
      return data;
      
    } catch (error) {
      console.error('API Request Error:', error);
      return {
        status: 'error',
        message: error.message
      };
    }
  }
  
  /* ─── READ METHODS ──────────────────────────────── */
  
  /**
   * Get all rolls dengan pagination
   * @param {number} page - Page number
   * @param {number} limit - Records per page
   * @param {string} search - Search query
   * @returns {Promise}
   */
  async getAllRolls(page = 1, limit = 10, search = null) {
    const params = { page, limit };
    if (search) params.search = search;
    return this.request('get', { params });
  }
  
  /**
   * Get single roll
   * @param {number} id - Roll ID
   * @returns {Promise}
   */
  async getRoll(id) {
    return this.request('get_one', { params: { id } });
  }
  
  /**
   * Get statistics
   * @returns {Promise}
   */
  async getStatistics() {
    return this.request('statistics');
  }
  
  /* ─── CREATE METHOD ────────────────────────────── */
  
  /**
   * Create new roll
   * @param {object} data - Roll data
   * @returns {Promise}
   */
  async createRoll(data) {
    return this.request('store', {
      method: 'POST',
      body: data
    });
  }
  
  /* ─── UPDATE METHOD ────────────────────────────── */
  
  /**
   * Update roll
   * @param {number} id - Roll ID
   * @param {object} data - Data to update
   * @returns {Promise}
   */
  async updateRoll(id, data) {
    return this.request('update', {
      method: 'POST',
      params: { id },
      body: data
    });
  }
  
  /* ─── DELETE METHODS ───────────────────────────── */
  
  /**
   * Delete single roll
   * @param {number} id - Roll ID
   * @returns {Promise}
   */
  async deleteRoll(id) {
    return this.request('delete', {
      method: 'DELETE',
      params: { id }
    });
  }
  
  /**
   * Delete multiple rolls
   * @param {array} ids - Array of roll IDs
   * @returns {Promise}
   */
  async deleteMultiple(ids) {
    return this.request('delete_multiple', {
      method: 'POST',
      body: { ids }
    });
  }
}

// ─── EXPORT (untuk ES Modules) ─────────────────
// export default FormRollAPI;

// ─── ATAU GUNAKAN GLOBAL (untuk non-module) ────
// window.FormRollAPI = FormRollAPI;
