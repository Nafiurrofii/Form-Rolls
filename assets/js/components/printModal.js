/* ─────────────────────────────────────────────────────
   COMPONENTS: PRINT MODAL - Thermal Label Print Modal
   ──────────────────────────────────────────────────── */

let _traceCode = '';
let _printData = {}; // Simpan data terakhir untuk nama file PDF
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
  _printData = data;

  const barcodeVal = data.barcode || data.register || '';
  const SEP_D = '='.repeat(42);
  const SEP_S = '-'.repeat(42);

  /* ── Isi field label ── */
  const tglJam = document.getElementById('l-tgl-jam');
  if (tglJam) {
    tglJam.textContent = `TGL : ${data.tanggal || '—'}\nJAM : ${data.jam || '—'}`;
    tglJam.style.whiteSpace = 'pre';
  }

  const grpPic = document.getElementById('l-grp-pic');
  if (grpPic) {
    grpPic.textContent = `GROUP : ${data.group || '—'}\nPIC   : ${data.pic || '—'}`;
    grpPic.style.whiteSpace = 'pre';
  }

  _setT('l-nama',      `NAMA   : ${data.nama || '—'}`);

  const rollReg = document.getElementById('l-roll-reg');
  if (rollReg) {
    rollReg.innerHTML = `<span>Roll Ke : ${data.roll_ke || '—'}</span><span><b>REGISTER : ${data.register || '—'}</b></span>`;
  }

  _setT('sep1', SEP_D);
  _setT('sep2', SEP_S);
  _setT('sep3', SEP_S);
  _setT('sep4', SEP_D);

  const tblData = document.getElementById('l-tbldata');
  if (tblData) {
    tblData.innerHTML =
      `<span class="col-pj">${data.panjang || '—'}</span>` +
      `<span class="col-lb">${data.lebar   || '—'}</span>` +
      `<span class="col-dn">${data.denier  || '—'}</span>` +
      `<span class="col-gsm">${data.anyam  || '—'}</span>`;
  }

  _setT('l-berat',      `Berat  : ${data.berat      || '—'}`);
  _setT('l-mesin',      `Mesin  : ${data.mesin      || '—'}`);
  _setT('l-ket',        `Ket.   : ${data.keterangan || '—'}`);
  _setT('l-trace',      `KODE TRACE : ${data.kode_trace || '—'}`);
  _setT('l-barcodenum', `BARCODE    : ${barcodeVal}`);
  _setT('bc-label',     barcodeVal);

  /* ── Info chips ── */
  _setT('chipRegister', data.register || '—');
  _setT('chipMesin',    data.mesin    || '—');
  _setT('chipGroup',    data.group    || '—');
  _setT('chipRoll',     data.roll_ke  || '—');
  _setT('chipPic',      data.pic      || '—');

  /* ── Buka modal dulu ── */
  const backdrop = document.getElementById('printModalBackdrop');
  if (backdrop) {
    backdrop.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  /* ── Gambar barcode setelah modal visible ── */
  setTimeout(() => {
    const canvas = document.getElementById('barcode1d');
    if (canvas && barcodeVal) _drawBarcode128(canvas, barcodeVal);
  }, 80);
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
 * handlePrint() — Print label 9cm × 8cm
 */
export function handlePrint() {
  console.log('🖨️ [PrintModal] handlePrint() dipanggil');
  const label = document.getElementById('label-preview');
  if (!label) {
    console.error('❌ [PrintModal] #label-preview tidak ditemukan');
    _showToast('⚠️', 'Label preview tidak ditemukan');
    return;
  }

  /* Reset transform sementara agar browser print di ukuran asli */
  const origTransform = label.style.transform;
  label.style.transform = 'none';

  _showToast('🖨️', 'Membuka dialog print...');

  setTimeout(() => {
    window.print();
    setTimeout(() => {
      label.style.transform = origTransform;
    }, 600);
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
 * downloadThermalPdf() — Capture #label-preview → PDF 9cm × 8cm
 */
export async function downloadThermalPdf(data = {}) {
  console.log('📄 [PrintModal] downloadThermalPdf() dipanggil');
  const btn   = document.getElementById('pmBtnPdf');
  const label = document.getElementById('label-preview');

  if (!label) {
    _showToast('⚠️', '#label-preview tidak ditemukan');
    return;
  }

  if (typeof html2canvas === 'undefined') {
    _showToast('⚠️', 'html2canvas belum dimuat');
    return;
  }
  if (typeof window.jspdf === 'undefined') {
    _showToast('⚠️', 'jsPDF belum dimuat');
    return;
  }

  _setLoadingState(btn, true, 'Generating PDF...');

  try {
    /* ── Reset transform agar capture ukuran asli 9cm × 8cm ── */
    const origTransform  = label.style.transform;
    const origBoxShadow  = label.style.boxShadow;
    const origBorder     = label.style.border;

    label.style.transform = 'none';
    label.style.boxShadow = 'none';
    label.style.border    = 'none';

    console.log('📐 [PrintModal] Capture ukuran:', label.offsetWidth, '×', label.offsetHeight, 'px');

    /* ── Capture ── */
    const capturedCanvas = await html2canvas(label, {
      scale: 4,
      useCORS: true,
      allowTaint: false,
      backgroundColor: '#ffffff',
      logging: false,
      width: label.offsetWidth,
      height: label.offsetHeight,
    });

    /* ── Kembalikan style ── */
    label.style.transform = origTransform;
    label.style.boxShadow = origBoxShadow;
    label.style.border    = origBorder;

    console.log('✅ html2canvas selesai —', capturedCanvas.width, '×', capturedCanvas.height, 'px');

    /* ── Buat PDF ── */
    const { jsPDF } = window.jspdf;
    const PDF_W = 9;   // cm
    const PDF_H = 8;   // cm

    const pdf = new jsPDF({
      orientation: 'landscape',
      unit: 'cm',
      format: [PDF_W, PDF_H],
    });

    const imgData = capturedCanvas.toDataURL('image/png');
    pdf.addImage(imgData, 'PNG', 0, 0, PDF_W, PDF_H, '', 'FAST');

    /* ── Nama file ── */
    const merged   = { ..._printData, ...data };
    const tgl      = (merged.tanggal || new Date().toISOString().split('T')[0]).replace(/\//g, '-');
    const roll     = merged.roll_ke || merged.roll || 'X';
    const fileName = `form-roll-${tgl}-roll${roll}.pdf`;

    pdf.save(fileName);
    _showToast('✅', `PDF disimpan: ${fileName}`);
    console.log('✅ [PrintModal] PDF berhasil diunduh:', fileName);

  } catch (err) {
    console.error('❌ [PrintModal] Gagal generate PDF:', err);
    _showToast('❌', 'Gagal membuat PDF: ' + (err.message || 'unknown error'));
  } finally {
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

  const btn = document.getElementById('pmBtnCopy');
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
  try { document.execCommand('copy'); cb(); } catch (e) { _showToast('⚠️', 'Gagal menyalin.'); }
  document.body.removeChild(ta);
}


function _setT(id, val) {
  const el = document.getElementById(id);
  if (el) el.textContent = val;
}

/* ──────────────────────────────────────────────────────
   _drawBarcode128(canvas, text)
   — Menggambar barcode 1D menggunakan library JsBarcode
─────────────────────────────────────────────────────── */
function _drawBarcode128(canvas, text) {
  if (typeof JsBarcode === 'undefined') {
    console.warn('⚠️ JsBarcode belum dimuat!');
    return;
  }
  
  // Menggunakan JsBarcode untuk hasil scan yang presisi dan tidak blur
  JsBarcode(canvas, text, {
    format: "CODE128",
    width: 1.4,        // Ketebalan baris (disesuaikan agar proporsional)
    height: 25,        // Tinggi barcode
    displayValue: false, // Jangan tampilkan teks di dalam barcode (sudah ada label terpisah)
    margin: 0,
    background: "#ffffff",
    lineColor: "#000000"
  });
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
  btn.style.cursor = isLoading ? 'not-allowed' : '';
}

function _showToast(icon, msg) {
  const el = document.getElementById('pmToast');
  const ico = document.getElementById('pmToastIco');
  const txt = document.getElementById('pmToastTxt');
  if (!el) return;
  if (ico) ico.textContent = icon;
  if (txt) txt.textContent = msg;
  el.classList.add('show');
  clearTimeout(_toastTimer);
  _toastTimer = setTimeout(() => el.classList.remove('show'), 2700);
}
