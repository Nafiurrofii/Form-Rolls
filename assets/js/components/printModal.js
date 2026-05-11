/* ─────────────────────────────────────────────────────
   COMPONENTS: PRINT MODAL - Thermal Label Print Modal
   ──────────────────────────────────────────────────── */

let _traceCode  = '';
let _printData  = {}; // Simpan data terakhir untuk nama file PDF
let _toastTimer = null;

/* ──────────────────────────────────────────────────────
   openPrintModal(data)
   — Terima data dari form roll, isi template, buka modal
   — data: { tanggal, jam, group, pic, nama, roll_ke,
             register, panjang, lebar, denier, anyam,
             berat, mesin, keterangan, kode_trace }
─────────────────────────────────────────────────────── */
export function openPrintModal(data = {}) {
  _traceCode = data.kode_trace || '';
  _printData = data; // simpan referensi untuk nama file PDF

  /* Fill thermal fields */
  _setT('printTanggal',    data.tanggal     || '—');
  _setT('printJam',        data.jam         || '—');
  _setT('printGroup',      data.group       || '—');
  _setT('printPic',        data.pic         || '—');
  _setT('printNama',       data.nama        || '—');
  _setT('printRoll',       data.roll_ke     || '—');
  _setT('printRegister',   data.register    || '—');
  _setT('printPanjang',    data.panjang     || '—');
  _setT('printLebar',      data.lebar       || '—');
  _setT('printDenier',     data.denier      || '—');
  _setT('printAnyam',      data.anyam       || '—');
  _setT('printBerat',      data.berat       || '—');
  _setT('printMesin',      data.mesin       || '—');
  _setT('printKeterangan', data.keterangan  || '—');
  _setT('printTrace',      data.kode_trace  || '—');
  _setT('printRegister',   data.register    || '—');
  _setT('printBarcode',    data.barcode     || '—');

  /* Timestamp cetak */
  const now = new Date();
  const ts  = now.toLocaleString('id-ID', {
    day:'2-digit', month:'2-digit', year:'numeric',
    hour:'2-digit', minute:'2-digit', second:'2-digit'
  });
  _setT('printTimestamp', 'Cetak: ' + ts);

  /* Info chips */
  _setT('chipRegister', data.register  || '—');
  _setT('chipMesin',    data.mesin     || '—');
  _setT('chipGroup',    data.group     || '—');
  _setT('chipRoll',     data.roll_ke   || '—');
  _setT('chipPic',      data.pic       || '—');

  /* Buka modal */
  const backdrop = document.getElementById('printModalBackdrop');
  if (backdrop) {
    backdrop.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
}

/* ──────────────────────────────────────────────────────
   closePrintModal()
─────────────────────────────────────────────────────── */
export function closePrintModal() {
  console.log('🔒 [PrintModal] closePrintModal() dipanggil');
  const backdrop = document.getElementById('printModalBackdrop');
  if (backdrop) {
    backdrop.classList.remove('active');
    document.body.style.overflow = '';
    console.log('✅ [PrintModal] Modal berhasil ditutup');
  } else {
    console.warn('⚠️ [PrintModal] #printModalBackdrop tidak ditemukan');
  }
}

/* ──────────────────────────────────────────────────────
   Setup event listeners (panggil sekali saat init)
   Gunakan event delegation agar tidak bergantung timing DOM
─────────────────────────────────────────────────────── */
export function setupPrintModal() {
  console.log('🔧 [PrintModal] setupPrintModal() dipanggil');

  /* ── Periksa apakah semua elemen penting ada ── */
  const ids = ['printModalBackdrop', 'pmBtnX', 'pmBtnPrint', 'pmBtnPdf', 'pmBtnCopy', 'pmBtnClose'];
  ids.forEach(id => {
    const el = document.getElementById(id);
    console.log(`  ${el ? '✅' : '❌'} #${id}`, el ? 'ditemukan' : 'TIDAK DITEMUKAN');
  });

  /* ── Gunakan event delegation di document untuk tombol ── */
  document.addEventListener('click', (e) => {
    const id = e.target.closest('[id]')?.id;

    /* Tombol X (header close) */
    if (e.target.closest('#pmBtnX')) {
      console.log('🖱️ [PrintModal] Klik #pmBtnX → menutup modal');
      closePrintModal();
      return;
    }

    /* Tombol Tutup (footer) */
    if (e.target.closest('#pmBtnClose')) {
      console.log('🖱️ [PrintModal] Klik #pmBtnClose → menutup modal');
      closePrintModal();
      return;
    }

    /* Klik di luar modal (backdrop) */
    if (e.target.id === 'printModalBackdrop') {
      console.log('🖱️ [PrintModal] Klik backdrop → menutup modal');
      closePrintModal();
      return;
    }

    /* Tombol Print */
    if (e.target.closest('#pmBtnPrint')) {
      console.log('🖱️ [PrintModal] Klik #pmBtnPrint → memulai print...');
      _doPrint();
      return;
    }

    /* Tombol PDF */
    if (e.target.closest('#pmBtnPdf')) {
      console.log('🖱️ [PrintModal] Klik #pmBtnPdf → memulai download PDF...');
      _doSavePDF();
      return;
    }

    /* Tombol Copy Trace */
    if (e.target.closest('#pmBtnCopy')) {
      console.log('🖱️ [PrintModal] Klik #pmBtnCopy → menyalin trace code...');
      _doCopyTrace();
      return;
    }
  });

  /* ESC key */
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      const backdrop = document.getElementById('printModalBackdrop');
      if (backdrop && backdrop.classList.contains('active')) {
        console.log('⌨️ [PrintModal] Tombol ESC → menutup modal');
        closePrintModal();
      }
    }
  });

  console.log('✅ [PrintModal] Event listeners terpasang via event delegation');
}

/* ──────────────────────────────────────────────────────
   INTERNAL FUNCTIONS
─────────────────────────────────────────────────────── */

/* ══════════════════════════════════════════════════════
   FITUR 1 — PRINT THERMAL
   Isolasi hanya #thermalPrint, sembunyikan semua modal UI
══════════════════════════════════════════════════════ */

/**
 * handlePrint()
 * Tambahkan class .printing ke body sehingga @media print
 * CSS akan menyembunyikan semua kecuali thermal label.
 */
export function handlePrint() {
  console.log('🖨️ [PrintModal] handlePrint() dipanggil');
  const thermal = document.getElementById('thermalPrint');
  if (!thermal) {
    console.error('❌ [PrintModal] #thermalPrint tidak ditemukan');
    return;
  }

  console.log('📐 [PrintModal] Ukuran thermal:', thermal.offsetWidth, '×', thermal.offsetHeight, 'px');

  const originalTransform = thermal.style.transform;
  thermal.style.transform = 'none';
  console.log('🔄 [PrintModal] Transform di-reset sementara untuk print');

  _showToast('🖨️', 'Membuka dialog print...');

  setTimeout(() => {
    console.log('🖨️ [PrintModal] Memanggil window.print()...');
    window.print();
    setTimeout(() => {
      thermal.style.transform = originalTransform;
      console.log('✅ [PrintModal] Transform dikembalikan setelah print');
    }, 500);
  }, 300);
}

function _doPrint() {
  handlePrint();
}

/* ══════════════════════════════════════════════════════
   FITUR 2 — DOWNLOAD PDF
   html2canvas capture #thermalPrint → jsPDF 9cm × 8cm
══════════════════════════════════════════════════════ */

/**
 * downloadThermalPdf(data)
 * @param {object} data - opsional, untuk nama file
 */
export async function downloadThermalPdf(data = {}) {
  console.log('📄 [PrintModal] downloadThermalPdf() dipanggil');
  const btn     = document.getElementById('pmBtnPdf');
  const thermal = document.getElementById('thermalPrint');

  if (!thermal) {
    console.error('❌ [PrintModal] #thermalPrint tidak ditemukan');
    _showToast('⚠️', '#thermalPrint tidak ditemukan');
    return;
  }

  /* ── Cek ketersediaan library ── */
  console.log('🔍 [PrintModal] Cek library:');
  console.log('  html2canvas:', typeof html2canvas !== 'undefined' ? '✅ tersedia' : '❌ TIDAK ADA');
  console.log('  jsPDF:', typeof window.jspdf !== 'undefined' ? '✅ tersedia' : '❌ TIDAK ADA');

  if (typeof html2canvas === 'undefined') {
    _showToast('⚠️', 'html2canvas belum dimuat. Cek koneksi internet.');
    return;
  }
  if (typeof window.jspdf === 'undefined') {
    _showToast('⚠️', 'jsPDF belum dimuat. Cek koneksi internet.');
    return;
  }

  /* ── Loading state ON ── */
  _setLoadingState(btn, true, 'Generating PDF...');

  try {
    /* ── Sementara reset transform agar capture full size ── */
    const originalTransform    = thermal.style.transform;
    const originalBoxShadow    = thermal.style.boxShadow;
    const originalBorderRadius = thermal.style.borderRadius;
    const originalBorder       = thermal.style.border;

    thermal.style.transform    = 'none';
    thermal.style.boxShadow    = 'none';
    console.log('📐 [PrintModal] Ukuran thermal saat capture:', thermal.offsetWidth, '×', thermal.offsetHeight);

    thermal.style.transform    = 'none';
    thermal.style.boxShadow    = 'none';
    thermal.style.borderRadius = '0';
    thermal.style.border       = 'none';

    /* ── Capture dengan html2canvas ── */
    console.log('📸 [PrintModal] Memulai html2canvas capture (scale=4)...');
    const canvas = await html2canvas(thermal, {
      scale:            4,         // 4× untuk high quality, tidak blur
      useCORS:          true,
      allowTaint:       false,
      backgroundColor:  '#ffffff',
      logging:          false,
      width:            thermal.offsetWidth,
      height:           thermal.offsetHeight,
      windowWidth:      thermal.offsetWidth,
      windowHeight:     thermal.offsetHeight,
    });

    console.log('✅ [PrintModal] html2canvas selesai — canvas:', canvas.width, '×', canvas.height, 'px');

    /* ── Kembalikan style asli ── */
    thermal.style.transform    = originalTransform;
    thermal.style.boxShadow    = originalBoxShadow;
    thermal.style.borderRadius = originalBorderRadius;
    thermal.style.border       = originalBorder;

    /* ── Buat PDF ukuran 9cm × 8cm ── */
    const { jsPDF } = window.jspdf;
    const PDF_W_CM = 9;  // lebar  dalam cm
    const PDF_H_CM = 8;  // tinggi dalam cm

    const pdf = new jsPDF({
      orientation: 'landscape',  // 9cm > 8cm
      unit:        'cm',
      format:      [PDF_W_CM, PDF_H_CM],
    });

    const imgData   = canvas.toDataURL('image/png');
    const imgWidth  = PDF_W_CM;
    const imgHeight = PDF_H_CM;

    pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight, '', 'FAST');

    /* ── Nama file: form-roll-[tanggal]-[roll].pdf ── */
    const mergedData  = { ..._printData, ...data };
    const tgl         = (mergedData.tanggal || new Date().toISOString().split('T')[0]).replace(/\//g, '-');
    const roll        = mergedData.roll_ke || mergedData.roll || 'X';
    const fileName    = `form-roll-${tgl}-${roll}.pdf`;

    console.log(`📁 [PrintModal] Menyimpan PDF: ${fileName}`);
    pdf.save(fileName);

    _showToast('✅', `PDF disimpan: ${fileName}`);
    console.log('✅ [PrintModal] PDF berhasil diunduh:', fileName);

  } catch (err) {
    console.error('❌ [PrintModal] Gagal generate PDF:', err);
    _showToast('❌', 'Gagal membuat PDF: ' + (err.message || 'unknown error'));
  } finally {
    /* ── Loading state OFF ── */
    _setLoadingState(btn, false, 'Download PDF');
  }
}

function _doSavePDF() {
  downloadThermalPdf();
}

function _doCopyTrace() {
  console.log('📋 [PrintModal] _doCopyTrace() dipanggil, traceCode:', _traceCode || '(kosong)');
  if (!_traceCode || _traceCode === '—') {
    console.warn('⚠️ [PrintModal] Trace code kosong, tidak bisa disalin');
    _showToast('⚠️', 'Tidak ada trace code untuk disalin.');
    return;
  }

  const btn  = document.getElementById('pmBtnCopy');
  const done = () => {
    console.log('✅ [PrintModal] Trace code berhasil disalin:', _traceCode);
    if (btn) {
      btn.classList.add('copied');
      btn.textContent = '✓ Tersalin!';
    }
    _showToast('✓', 'Trace code disalin: ' + _traceCode);
    setTimeout(() => {
      if (btn) {
        btn.classList.remove('copied');
        btn.textContent = 'Copy Trace';
      }
    }, 2400);
  };

  if (navigator.clipboard) {
    navigator.clipboard.writeText(_traceCode).then(done).catch(() => _fallbackCopy(_traceCode, done));
  } else {
    _fallbackCopy(_traceCode, done);
  }
}

function _fallbackCopy(text, cb) {
  const ta = Object.assign(document.createElement('textarea'), {
    value: text,
    style: 'position:fixed;opacity:0;top:0;left:0'
  });
  document.body.appendChild(ta);
  ta.select();
  try { document.execCommand('copy'); cb(); } catch(e) { _showToast('⚠️', 'Gagal menyalin.'); }
  document.body.removeChild(ta);
}


function _setT(id, val) {
  const el = document.getElementById(id);
  if (el) el.textContent = val;
}

/* ──────────────────────────────────────────────────────
   _setLoadingState(btn, isLoading, text)
   — Toggle disabled/teks tombol saat proses PDF
─────────────────────────────────────────────────────── */
function _setLoadingState(btn, isLoading, text) {
  if (!btn) return;
  btn.disabled = isLoading;
  btn.textContent = text;
  btn.style.opacity = isLoading ? '0.65' : '';
  btn.style.cursor  = isLoading ? 'not-allowed' : '';
}

function _showToast(icon, msg) {
  const el  = document.getElementById('pmToast');
  const ico = document.getElementById('pmToastIco');
  const txt = document.getElementById('pmToastTxt');
  if (!el) return;
  if (ico) ico.textContent = icon;
  if (txt) txt.textContent = msg;
  el.classList.add('show');
  clearTimeout(_toastTimer);
  _toastTimer = setTimeout(() => el.classList.remove('show'), 2700);
}
