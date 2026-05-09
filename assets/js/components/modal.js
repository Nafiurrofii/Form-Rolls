/* ─────────────────────────────────────────────────────
   COMPONENTS: MODAL - Reusable Modal Component
   ──────────────────────────────────────────────────── */

/**
 * Create simple modal element
 * @param {string} id - Modal ID
 * @param {string} title - Modal title
 * @param {string} content - Modal content (HTML)
 * @param {array} buttons - Array of button configs
 * @returns {HTMLElement} - Modal element
 */
export function createModal(id, title, content, buttons = []) {
  const modal = document.createElement('div');
  modal.id = id;
  modal.className = 'modal';
  modal.innerHTML = `
    <div class="modal-overlay" onclick="closeModal('${id}')"></div>
    <div class="modal-content">
      <div class="modal-header">
        <h2>${title}</h2>
        <button class="modal-close" onclick="closeModal('${id}')">&times;</button>
      </div>
      <div class="modal-body">
        ${content}
      </div>
      <div class="modal-footer">
        ${buttons.map(btn => `
          <button class="btn btn-${btn.type || 'primary'}" onclick="${btn.onclick}">
            ${btn.label}
          </button>
        `).join('')}
      </div>
    </div>
  `;
  return modal;
}

/**
 * Show modal
 * @param {string} id - Modal ID
 */
export function showModal(id) {
  const modal = document.getElementById(id);
  if (modal) {
    modal.style.display = 'block';
    modal.classList.add('show');
  }
}

/**
 * Close modal
 * @param {string} id - Modal ID
 */
export function closeModal(id) {
  const modal = document.getElementById(id);
  if (modal) {
    modal.style.display = 'none';
    modal.classList.remove('show');
  }
}

/**
 * Confirmation Modal
 * @param {string} message - Confirmation message
 * @param {function} onConfirm - Callback ketika confirm
 * @param {function} onCancel - Callback ketika cancel
 */
export function showConfirmModal(message, onConfirm, onCancel) {
  const id = 'confirm-modal-' + Date.now();
  const modal = createModal(id, 'Konfirmasi', message, [
    { label: 'Ya', type: 'primary', onclick: `confirmModalAction('${id}', ${onConfirm})` },
    { label: 'Tidak', type: 'neutral', onclick: `closeModal('${id}')` }
  ]);
  document.body.appendChild(modal);
  showModal(id);
}

/**
 * Alert Modal
 * @param {string} title - Alert title
 * @param {string} message - Alert message
 * @param {string} type - 'success', 'error', 'warning', 'info'
 */
export function showAlertModal(title, message, type = 'info') {
  const id = 'alert-modal-' + Date.now();
  const modal = createModal(id, title, message, [
    { label: 'OK', type: 'primary', onclick: `closeModal('${id}')` }
  ]);
  modal.classList.add(`alert-${type}`);
  document.body.appendChild(modal);
  showModal(id);
}

/**
 * Form Modal
 * @param {string} title - Modal title
 * @param {array} fields - Array of field configs
 * @param {function} onSubmit - Submit callback
 */
export function showFormModal(title, fields, onSubmit) {
  const id = 'form-modal-' + Date.now();
  const formHtml = fields.map(field => `
    <div class="form-group">
      <label>${field.label}</label>
      <input type="${field.type || 'text'}" id="${field.id}" placeholder="${field.placeholder || ''}">
    </div>
  `).join('');

  const modal = createModal(id, title, formHtml, [
    { 
      label: 'Submit', 
      type: 'success', 
      onclick: `submitFormModal('${id}', ${JSON.stringify(fields)}, ${onSubmit})`
    },
    { label: 'Cancel', type: 'neutral', onclick: `closeModal('${id}')` }
  ]);

  document.body.appendChild(modal);
  showModal(id);
}

/**
 * Helper untuk submit form modal
 * @param {string} modalId - Modal ID
 * @param {array} fields - Field configs
 * @param {function} callback - Submit callback
 */
export function submitFormModal(modalId, fields, callback) {
  const values = {};
  fields.forEach(field => {
    const elem = document.getElementById(field.id);
    if (elem) values[field.id] = elem.value;
  });
  
  if (callback) {
    callback(values);
  }
  
  closeModal(modalId);
}

/**
 * Helper untuk confirm modal action
 * @param {string} modalId - Modal ID
 * @param {function} callback - Confirm callback
 */
export function confirmModalAction(modalId, callback) {
  if (callback) {
    callback();
  }
  closeModal(modalId);
}
