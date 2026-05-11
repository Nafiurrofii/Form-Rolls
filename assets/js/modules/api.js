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

/**
 * Fetch semua rolls dari API
 * @returns {Promise<Array>}
 */
export async function fetchRolls() {
  try {
    const response = await fetch(`${API_BASE_URL}?action=get`);
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const result = await response.json();
    
    if (result.status !== 'success') {
      throw new Error(result.message || 'Unknown error');
    }
    
    return result.data;
  } catch (error) {
    console.error('❌ API Error:', error);
    throw error;
  }
}

/**
 * Fetch single roll by ID
 */
export async function fetchRollById(id) {
  const response = await fetch(`${API_BASE_URL}?action=get&id=${id}`);
  return response.json();
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
      body: JSON.stringify(payload)
    });

    
    if (!response.ok) {
      const errorText = await response.text();
      console.error('Server error response:', errorText);
      throw new Error(`HTTP ${response.status}: ${errorText || response.statusText}`);
    }
    
    const result = await response.json();
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
