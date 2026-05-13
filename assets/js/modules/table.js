/* ─────────────────────────────────────────────────────
   MODULES: TABLE - Table Rendering & Interaction
   ──────────────────────────────────────────────────── */

import { getDOMElement, formatNumber, showNotification } from '../utils.js';
import { getCurrentPage, setCurrentPage, setSelectedRow, setEditMode } from '../state.js';
import { setFormData, toggleFormInputs } from './form.js';

/**
 * Render table dengan data dan pagination
 * @param {array} data - Data yang akan ditampilkan
 * @param {number} perPage - Jumlah per halaman
 * @param {number} totalVirtual - Total virtual record
 */
export function renderTable(data, perPage, totalVirtual) {
  const currentPage = getCurrentPage();
  const total = data.length === totalVirtual ? totalVirtual : data.length;
  const totalPages = Math.ceil(total / perPage);

  // Reset halaman jika melebihi batas
  if (currentPage > totalPages && totalPages > 0) {
    setCurrentPage(1);
    return renderTable(data, perPage, totalVirtual);
  }

  const start = (currentPage - 1) * perPage;
  const slice = data.slice(start, start + perPage);

  // Render body tabel
  const tbody = getDOMElement('table_body');
  if (tbody) {
    tbody.innerHTML = '';
    slice.forEach((row, index) => {
      const no = start + index + 1;
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${no}</td>
        <td>${row.tgl}</td>
        <td class="mono">${row.jam}</td>
        <td>${row.roll}</td>
        <td><span class="badge-shift">${row.shift}</span></td>
        <td>${row.mesin}</td>
        <td style="text-align:left;padding-left:14px;font-weight:500">${row.nama}</td>
        <td>${row.dnr}</td>
        <td>${row.pj}</td>
        <td>${row.lb}</td>
        <td>${row.anyam}</td>
        <td>${row.br}</td>
        <td class="mono" style="font-size:11px">${row.trace}</td>
        <td>${row.reg}</td>
        <td>${row.keterangan || ''}</td>
        <td>${row.user}</td>
      `;
      tr.style.cursor = 'pointer';
      tr.addEventListener('click', (e) => handleRowClick(row, e.currentTarget));
      tbody.appendChild(tr);
    });
  }

  // Update info text
  const end = Math.min(start + perPage, total);
  const infoElem = getDOMElement('table_info');
  if (infoElem) {
    infoElem.textContent = `Showing ${start + 1} to ${end} of ${formatNumber(total)} entries`;
  }

  // Render pagination
  renderPagination(totalPages, currentPage);
}

/**
 * Handle row click - load data ke form
 * @param {object} row - Data row
 * @param {HTMLElement} trElem - Element tr yang diklik
 */
function handleRowClick(row, trElem = null) {
  setSelectedRow(row);
  
  // Hapus class selected-row dari semua baris
  const allRows = document.querySelectorAll('#table_body tr');
  allRows.forEach(r => r.classList.remove('selected-row'));
  
  // Tambahkan class selected-row ke baris yang diklik
  if (trElem) {
    trElem.classList.add('selected-row');
  }

  setFormData(row);
  setEditMode(true); // Status internal Edit Mode tetap aktif agar tombol SIMPAN tahu ini update
  toggleFormInputs(false); // Tapi visual form dikunci (disable) sampai klik EDIT/LANJUT

  // Matikan status "active" pada tombol BARU & LANJUT
  const btnBaru = document.querySelector('.btn-primary');
  if (btnBaru) btnBaru.classList.remove('active');

  const btnLanjut = document.querySelector('.btn-lanjut');
  if (btnLanjut) btnLanjut.classList.remove('active');

  const btnEdit = document.querySelector('.btn-warning');
  if (btnEdit) btnEdit.classList.remove('active');

  // Reset teks input lanjut jika ada
  const inputLanjut = document.getElementById('lanjut');
  if (inputLanjut) inputLanjut.value = 'Lanjut';
}

/**
 * Render pagination button
 * @param {number} totalPages - Total halaman
 * @param {number} currentPage - Halaman saat ini
 */
export function renderPagination(totalPages, currentPage) {
  const nav = getDOMElement('pagination');
  if (!nav) return;

  nav.innerHTML = '';

  const createBtn = (label, page, disabled = false, active = false, isDots = false) => {
    if (isDots) {
      const span = document.createElement('span');
      span.className = 'page-dots';
      span.textContent = '...';
      nav.appendChild(span);
      return;
    }

    const btn = document.createElement('button');
    btn.className = 'page-btn' + (active ? ' active' : '');
    btn.textContent = label;
    btn.disabled = disabled;
    btn.onclick = () => {
      setCurrentPage(page);
      window.dispatchEvent(new CustomEvent('pageChanged'));
    };
    nav.appendChild(btn);
  };

  // First & Previous
  createBtn('«', 1, currentPage === 1);
  createBtn('‹', currentPage - 1, currentPage === 1);

  // Page numbers
  const pages = [];
  if (totalPages <= 7) {
    for (let i = 1; i <= totalPages; i++) pages.push(i);
  } else {
    pages.push(1);
    if (currentPage > 3) pages.push('...');
    for (let i = Math.max(2, currentPage - 1); i <= Math.min(totalPages - 1, currentPage + 1); i++) {
      pages.push(i);
    }
    if (currentPage < totalPages - 2) pages.push('...');
    pages.push(totalPages);
  }

  pages.forEach(p => {
    if (p === '...') {
      createBtn('', '', false, false, true);
    } else {
      createBtn(p, p, false, p === currentPage);
    }
  });

  // Next & Last
  createBtn('›', currentPage + 1, currentPage === totalPages);
  createBtn('»', totalPages, currentPage === totalPages);
}

/**
 * Get entries per page dari select
 * @returns {number}
 */
export function getEntriesPerPage() {
  const select = getDOMElement('per_page');
  return select ? parseInt(select.value) : 10;
}
