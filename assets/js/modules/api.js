/* ─────────────────────────────────────────────────────
   MODULES: API - API Communication
   ──────────────────────────────────────────────────── */

// Get the base URL for API (handles both local and deployed environments)
const getAPIBaseUrl = () => {
  const pathArray = window.location.pathname.split('/');
  const projectIndex = pathArray.indexOf('Form-Roll');
  const basePath = projectIndex !== -1 ? pathArray.slice(0, projectIndex + 1).join('/') : '';
  return `${window.location.protocol}//${window.location.host}${basePath}/backend/roll.php`;
};

const API_BASE_URL = getAPIBaseUrl();

async function parseJsonResponse(response, context = 'API') {
  const responseText = await response.text();

  try {
    return JSON.parse(responseText);
  } catch (error) {
    const preview = responseText.replace(/\s+/g, ' ').trim().slice(0, 180);
    throw new Error(
      `${context} mengembalikan response non-JSON${preview ? `: ${preview}` : ''}`
    );
  }
}

/**
 * Fetch semua rolls dari API
 * @param {string} startDate - Format YYYY-MM-DD (opsional)
 * @param {string} endDate - Format YYYY-MM-DD (opsional)
 * @returns {Promise<Array>}
 */
export async function fetchRolls(startDate = null, endDate = null, page = 1, limit = 100) {
  try {
    let url = `${API_BASE_URL}?action=get&page=${page}&limit=${limit}`;
    if (startDate && endDate) {
      url += `&start=${startDate}&end=${endDate}`;
    }
    
    const response = await fetch(url, { credentials: 'include' });
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const result = await parseJsonResponse(response, 'Fetch rolls');
    
    if (result.status !== 'success') {
      throw new Error(result.message || 'Unknown error');
    }
    
    // Return lengkap dengan pagination info
    return {
      data: result.data,
      pagination: result.pagination
    };
  } catch (error) {
    console.error('❌ API Error:', error);
    throw error;
  }
}

/**
 * Fetch all rolls untuk export (tanpa pagination)
 * @param {string} startDate - Format YYYY-MM-DD (opsional)
 * @param {string} endDate - Format YYYY-MM-DD (opsional)
 * @returns {Promise<Array>}
 */
export async function fetchAllRollsForExport(startDate = null, endDate = null) {
  try {
    let url = `${API_BASE_URL}?action=get&export=true`;
    if (startDate && endDate) {
      url += `&start=${startDate}&end=${endDate}`;
    }
    
    const response = await fetch(url, { credentials: 'include' });
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const result = await parseJsonResponse(response, 'Fetch all rolls for export');
    
    if (result.status !== 'success') {
      throw new Error(result.message || 'Unknown error');
    }
    
    // Return lengkap dengan pagination info
    return {
      data: result.data,
      pagination: result.pagination
    };
  } catch (error) {
    console.error('❌ API Error (Export):', error);
    throw error;
  }
}

/**
 * Fetch single roll by ID
 */
export async function fetchRollById(id) {
  const response = await fetch(`${API_BASE_URL}?action=get&id=${id}`, { credentials: 'include' });
  return parseJsonResponse(response, 'Fetch roll by ID');
}

/**
 * Insert/Update roll
 */
export async function saveRoll(data, id = null) {
  // Mapping field form ke field database dengan konversi tipe data yang sesuai
  const payload = {
    tanggal: data.tanggal || new Date().toISOString().split('T')[0],
    jam: data.jam || new Date().toTimeString().split(' ')[0],
    roll: parseInt(data.roll_ke) || 0,
    group_name: data.group || '',
    mesin: data.mesin || '',
    nama: data.nama || '',
    denier: data.denier || '',
    panjang: parseInt(data.panjang) || 0,
    lebar: parseInt(data.lebar) || 0,
    anyam: data.anyam || '',
    berat: data.berat || '',
    trace_code: data.kode_trace || '',
    keterangan: data.keterangan || '',
    pic: data.pic || ''
  };

  const action = id ? `update&id=${id}` : 'store';
  const endpoint = `${API_BASE_URL}?action=${action}`;

  console.log('📤 Sending to:', endpoint);
  console.log('📤 Payload:', payload);

  try {
    const response = await fetch(endpoint, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify(payload)
    });

    
    if (!response.ok) {
      const errorText = await response.text();
      console.error('Server error response:', errorText);
      throw new Error(`HTTP ${response.status}: ${errorText || response.statusText}`);
    }
    
    const result = await parseJsonResponse(response, 'Save roll');
    console.log('✅ Server response:', result);
    
    if (result.status !== 'success') {
      throw new Error(result.message || 'Gagal menyimpan data (status dari server)');
    }
    
    return result;
  } catch (error) {
    console.error('❌ API Error details:', error);
    throw error;
  }
}

/**
 * CONTINUE roll (Limited update)
 */
export async function continueRoll(data, id) {
  const payload = {
    jam: data.jam || new Date().toTimeString().split(' ')[0],
    roll: parseInt(data.roll_ke) || 0,
    group_name: data.group || '',
    mesin: data.mesin || '',
    panjang: parseInt(data.panjang) || 0,
    lebar: parseInt(data.lebar) || 0,
    berat: data.berat || '',
    pic: data.pic || ''
  };

  const endpoint = `${API_BASE_URL}?action=continue&id=${id}`;

  try {
    const response = await fetch(endpoint, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify(payload)
    });

    if (!response.ok) {
      const errorText = await response.text();
      throw new Error(`HTTP ${response.status}: ${errorText}`);
    }

    return await parseJsonResponse(response, 'Continue roll');
  } catch (error) {
    console.error('❌ API Error (Continue):', error);
    throw error;
  }
}

/**
 * DELETE roll
 */
export async function deleteRoll(id) {
  const endpoint = `${API_BASE_URL}?action=delete&id=${id}`;
  
  try {
    const response = await fetch(endpoint, {
      method: 'POST',
      credentials: 'include'
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    return await parseJsonResponse(response, 'Delete roll');
  } catch (error) {
    console.error('❌ API Error:', error);
    throw error;
  }
}
