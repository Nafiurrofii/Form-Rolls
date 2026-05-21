<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Roll</title>
  <link
    href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&family=DM+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap"
    rel="stylesheet">

  <!-- ─── STYLESHEETS ─────────────────────────────── -->
  <link rel="stylesheet" href="assets/css/variables.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/print-modal.css">
  <link rel="stylesheet" href="assets/css/dashboard.css">
  <link rel="icon" type="image/png" href="assets/images/logo-ljp.png">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
</head>

<body>

  <!-- ─── TOPBAR ─────────────────────────────────────── -->
  <div class="topbar">
    <div class="topbar-brand">
      <div class="topbar-icon-wrap">
        <img src="assets/images/logo-ljp.png" alt="Logo" style="height: 32px; width: auto;">
      </div>
      <div class="topbar-text">
        <h1>Form Roll</h1>
        <span class="topbar-sub">PT. Langgeng Jaya Plastindo — Textile Production</span>
      </div>
    </div>
    <div class="topbar-meta">
      <div class="user-badge" id="topbarUserBadge" style="display: none;">
        <div class="user-avatar" id="topbarUserAvatar">?</div>
        <span><span id="topbarUserName">User</span> (<span id="topbarUserRole">Role</span>)</span>
        <button class="btn-logout" id="btnLogout" title="Logout">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        </button>
      </div>
      <!-- <span class="topbar-badge">
        <span class="topbar-badge-dot"></span>
        Online
      </span> -->
    </div>
  </div>

  <!-- ─── MAIN ──────────────────────────────────────── -->
  <div class="main">
    <!-- ─── FORM PANEL ────────────────────────── -->
    <div class="card-form">
      <div class="card-form-header">
        <div class="card-form-title">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
          </svg>
          Input Data Roll
        </div>
        <span class="card-form-status" id="formStatusBadge">Standby</span>
      </div>
      <div class="card-form-body">
        <div class="form-grid">

          <!-- 1. INFORMASI UMUM -->
          <div class="form-section">
            <div class="section-title">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
              </svg>
              <span>Informasi Umum</span>
            </div>
            <div class="field-row">
              <label>Tanggal</label>
              <div class="input-wrap"><input type="date" id="tanggal" value=""></div>
            </div>
            <div class="field-row">
              <label>Jam</label>
              <div class="input-wrap"><input type="time" id="jam" value="" step="1"></div>
            </div>
            <div class="field-row">
              <label>Roll Ke</label>
              <div class="input-wrap"><input type="number" id="roll_ke" value="" min="1"></div>
            </div>
            <div class="field-row">
              <label>Group</label>
              <div class="input-wrap">
                <select id="group">
                  <option value="" selected>Pilih Group</option>
                  <option value="A">A</option>
                  <option value="B">B</option>
                  <option value="C">C</option>
                  <option value="D">D</option>
                </select>
              </div>
            </div>
            <div class="field-row">
              <label>Mesin</label>
              <div class="input-wrap">
                <input type="number" id="mesin">
              </div>
            </div>
            <input type="hidden" id="lanjut" value="Lanjut">
          </div>

          <!-- 2. DETAIL PRODUK -->
          <div class="form-section">
            <div class="section-title">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                <line x1="7" y1="7" x2="7.01" y2="7" />
              </svg>
              <span>Detail Produk</span>
            </div>
            <div class="field-row">
              <label>Nama</label>
              <div class="input-wrap"><input type="text" id="nama" value=""></div>
            </div>
            <div class="field-row">
              <label>Denier</label>
              <div class="input-wrap"><input type="text" id="denier" value=""></div>
            </div>
            <div class="field-row">
              <label>Panjang</label>
              <div class="input-wrap"><input type="number" id="panjang" value=""></div>
            </div>
            <div class="field-row">
              <label>Lebar</label>
              <div class="input-wrap"><input type="number" id="lebar" value=""></div>
            </div>
          </div>

          <!-- 3. PRODUKSI / HASIL -->
          <div class="form-section">
            <div class="section-title">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
              </svg>
              <span>Produksi / Hasil</span>
            </div>
            <div class="field-row">
              <label>Anyam / GSM</label>
              <div class="input-wrap"><input type="text" id="anyam" value=""></div>
            </div>
            <div class="field-row">
              <label>Berat Brutto</label>
              <div class="input-wrap"><input type="text" id="berat" value=""></div>
            </div>
            <div class="field-row">
              <label>Kode Trace</label>
              <div class="input-wrap"><input type="text" id="kode_trace" value="" class="mono-input"
                  style="font-family:var(--font-mono);font-size:12px;"></div>
            </div>
            <div class="field-row">
              <label>Keterangan</label>
              <div class="input-wrap"><input type="text" id="keterangan" value=""></div>
            </div>
            <div class="field-row">
              <label>PIC</label>
              <div class="input-wrap"><input type="text" id="pic" value="" placeholder="Nama/Inisial PIC"></div>
            </div>
          </div>

          <!-- 4. PERIODE & TOOLS -->
          <div class="form-section periode-section">
            <div class="section-title">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
              </svg>
              <span>Periode &amp; Tools</span>
            </div>
            <div class="field-row">
              <label>Periode Awal</label>
              <div class="input-wrap">
                <input type="date" id="periode_awal">
              </div>
            </div>
            <div class="field-row">
              <label>Periode Akhir</label>
              <div class="input-wrap">
                <input type="date" id="periode_akhir">
              </div>
            </div>
            <div class="periode-tools">
              <button class="btn-tool reset-filter">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                RESET
              </button>
              <button class="btn-tool lihat">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
                LIHAT
              </button>
              <button class="btn-tool excel">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                  stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                  <polyline points="14 2 14 8 20 8" />
                  <line x1="8" y1="13" x2="16" y2="13" />
                  <line x1="8" y1="17" x2="16" y2="17" />
                </svg>
                EXCEL
              </button>
            </div>
          </div>

        </div><!-- /form-grid -->

      </div><!-- /card-form-body -->

      <!-- ACTION BAR -->
      <div class="action-bar">
        <button class="btn-lanjut">
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2.5">
            <polyline points="9 18 15 12 9 6" />
          </svg>
          LANJUT
        </button>
        <button class="btn btn-warning">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
          </svg>
          EDIT
        </button>
        <button class="btn btn-primary">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
          BARU
        </button>
        <button class="btn btn-success">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
            <polyline points="17 21 17 13 7 13 7 21" />
            <polyline points="7 3 7 8 15 8" />
          </svg>
          SIMPAN
        </button>
        <button class="btn btn-danger">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <polyline points="3 6 5 6 21 6" />
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
          </svg>
          HAPUS
        </button>
      </div>

    </div><!-- /card-form -->

    <!-- ─── DATA ROLL TABLE ──────────────────────── -->
    <div class="card-table">
      <div class="table-header-bar">
        <div class="table-title">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <rect x="3" y="3" width="18" height="18" rx="2" />
            <line x1="3" y1="9" x2="21" y2="9" />
            <line x1="3" y1="15" x2="21" y2="15" />
            <line x1="9" y1="3" x2="9" y2="21" />
          </svg>
          Data Roll
          <span class="table-count-badge" id="tableCountBadge">—</span>
        </div>
        <div class="table-controls">
          <div class="show-entries">
            Show
            <select id="per_page" onchange="renderTable()">
              <!-- <option value="5">5</option> -->
              <option value="10" selected>10</option>
              <option value="25">25</option>
              <option value="50">50</option>
            </select>
            entries
          </div>
          <div class="search-wrap">
            <input type="text" id="search_input" placeholder="Cari data..." oninput="renderTable()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
              stroke-width="2">
              <circle cx="11" cy="11" r="8" />
              <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
          </div>
        </div>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>TGL</th>
              <th>JAM</th>
              <th>ROLL</th>
              <th>SHIFT</th>
              <th>MESIN</th>
              <th>NAMA</th>
              <th>DNR</th>
              <th>PJ</th>
              <th>LB</th>
              <th>ANYAM</th>
              <th>BR</th>
              <th>TRACE</th>
              <th>REG</th>
              <th>KET</th>
              <th>PIC</th>
            </tr>
          </thead>
          <tbody id="table_body"></tbody>
        </table>
      </div>

      <div class="table-footer">
        <span id="table_info">Showing 1 to 10 of — entries</span>
        <div class="pagination" id="pagination"></div>
      </div>
    </div>

    <!-- ─── DASHBOARD SECTION ──────────────────────── -->
    <div class="dashboard-section" style="margin-top: 30px;">
      
      <div class="page-header" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
          <div class="page-title" style="font-size: 1.5rem; font-weight: 700; color: var(--text);">Dashboard Produksi</div>
          <div class="page-sub" style="font-size: 0.875rem; color: var(--text-3);">Statistik dan monitoring data roll harian</div>
        </div>
        <div class="date-badge">
          <svg viewBox="0 0 24 24" fill="currentColor" style="width: 16px; height: 16px;"><path d="M19 4h-1V2h-2v2H8V2H6v2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/></svg>
          <span id="currentDate">–</span>
        </div>
      </div>

      <!-- SUMMARY CARDS -->
      <div class="summary-grid">
        <div class="stat-card blue">
          <div class="stat-top">
            <div class="stat-icon blue">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
            </div>
            <div class="stat-trend up" style="visibility:hidden">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 14l5-5 5 5z"/></svg>
              +
            </div>
          </div>
          <div class="stat-value" id="s-hari-ini">–</div>
          <div class="stat-label">Total Data Hari Ini</div>
          <div class="stat-foot">Dibandingkan kemarin: <strong id="s-kemarin">–</strong> data</div>
        </div>

        <div class="stat-card green">
          <div class="stat-top">
            <div class="stat-icon green">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/></svg>
            </div>
            <div class="stat-trend up" style="visibility:hidden">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 14l5-5 5 5z"/></svg>
              +
            </div>
          </div>
          <div class="stat-value" id="s-bulan">–</div>
          <div class="stat-label">Total Produksi Bulan Ini</div>
          <div class="stat-foot">Rata-rata harian: <strong id="s-rata">–</strong> data</div>
        </div>

        <div class="stat-card amber">
          <div class="stat-top">
            <div class="stat-icon amber">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
            </div>
            <div class="stat-trend same" style="visibility:hidden">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 12H4v-2h16v2z"/></svg>
              Stabil
            </div>
          </div>
          <div class="stat-value" id="s-mesin">–</div>
          <div class="stat-label">Mesin Aktif</div>
          <div class="stat-foot">Mesin mencetak hari ini</div>
        </div>

        <div class="stat-card purple">
          <div class="stat-top">
            <div class="stat-icon purple">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            </div>
            <div class="stat-trend up" style="visibility:hidden">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 14l5-5 5 5z"/></svg>
              Aktif
            </div>
          </div>
          <div class="stat-value" id="s-group">–</div>
          <div class="stat-label">Group Aktif</div>
          <div class="stat-foot">Shift berjalan: <strong id="s-shift">–</strong></div>
        </div>
      </div>

      <!-- CHART + MINI STATS -->
      <div class="chart-row">
        <!-- BAR CHART -->
        <div class="card">
          <div class="card-header">
            <div class="card-title-wrap">
              <div class="card-icon">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M5 9.2h3V19H5V9.2zM10.6 5h2.8v14h-2.8V5zM16.2 13h2.8v6h-2.8v-6z"/></svg>
              </div>
              <div>
                <div class="card-title">Produksi Per Tanggal</div>
                <div class="card-sub">Klik batang untuk melihat detail data</div>
              </div>
            </div>
            <div class="chart-filters">
              <button class="filter-btn active" data-days="7">Mingguan</button>
              <button class="filter-btn" data-days="30">Bulanan</button>
              <button class="filter-btn" id="customFilterBtn">Custom</button>
            </div>
            <!-- Custom Date Range Picker (Hidden) -->
            <div id="customDateRange" class="custom-date-popover" style="display:none">
              <div class="popover-body">
                <div class="date-input-group">
                  <input type="date" id="dashStart" class="dash-date-input">
                  <span>-</span>
                  <input type="date" id="dashEnd" class="dash-date-input">
                </div>
                <button id="applyCustomDash" class="apply-btn">Terapkan</button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-wrap">
              <canvas id="prodChart"></canvas>
            </div>
          </div>
        </div>

        <!-- MINI STATS -->
        <div class="card mini-stats-card">
          <div class="card-header">
            <div class="card-title-wrap">
              <div class="card-icon">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm-7 3a3 3 0 110 6 3 3 0 010-6zm5 11H7v-.5c0-2.21 2.24-4 5-4s5 1.79 5 4v.5z"/></svg>
              </div>
              <div>
                <div class="card-title">Top Produk</div>
                <div class="card-sub">30 hari terakhir</div>
              </div>
            </div>
          </div>
          <div id="miniStatsList"></div>
        </div>
      </div>

      <!-- DETAIL PANEL -->
      <!-- <div class="card detail-panel">
        <div class="card-header">
          <div class="card-title-wrap">
            <div class="card-icon">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/></svg>
            </div>
            <div>
              <div class="card-title" id="detailTitle">Detail Data Roll</div>
              <div class="card-sub" id="detailSub">Pilih tanggal pada chart di atas</div>
            </div>
          </div>
          <div class="chip" id="detailCount" style="display:none"></div>
        </div>
        <div id="detailContent">
          <div class="detail-empty">
            <div class="detail-empty-icon">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M5 9.2h3V19H5V9.2zM10.6 5h2.8v14h-2.8V5zM16.2 13h2.8v6h-2.8v-6z"/></svg>
            </div>
            <div class="detail-empty-title">Belum ada data dipilih</div>
            <div class="detail-empty-sub">Klik salah satu batang pada chart untuk melihat detail produksi</div>
          </div>
        </div>
      </div> -->
    </div><!-- /.dashboard-section -->

  </div><!-- /main -->

  <!-- TOOLTIP -->
  <div class="custom-tooltip" id="tooltip"></div>

  <!-- ═══════════════════════════════════════════════════
     PRINT MODAL
════════════════════════════════════════════════════ -->
  <div class="pm-backdrop" id="printModalBackdrop">
    <div class="pm-modal" role="dialog" aria-modal="true" aria-labelledby="pmModalHeading">

      <!-- HEADER -->
      <div class="pm-header">
        <div class="pm-hdr-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="1.8">
            <polyline points="6 9 6 2 18 2 18 9" />
            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
            <rect x="6" y="14" width="12" height="8" />
          </svg>
        </div>
        <div class="pm-hdr-text">
          <h2 id="pmModalHeading">Print Preview Roll</h2>
          <p>Thermal Label &nbsp;&middot;&nbsp; 9 cm &times; 8 cm &nbsp;&middot;&nbsp; Monochrome</p>
        </div>
        <div class="pm-hdr-actions">
          <span class="pm-hdr-badge" id="pmHdrBadge">Ready</span>
          <button class="pm-btn-x" id="pmBtnX" aria-label="Tutup">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
              stroke-width="2.5">
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
          </button>
        </div>
      </div>

      <!-- BODY -->
      <div class="pm-body">

        <!-- Info chips -->
        <div class="pm-chips-row">
          <div class="pm-chip">
            <div class="pm-chip-dot" style="background:#2563eb"></div><span class="pm-chip-k">REG</span><span class="pm-chip-v" id="chipRegister">&mdash;</span>
          </div>
          <div class="pm-chip">
            <div class="pm-chip-dot" style="background:#f97316"></div><span class="pm-chip-k">Mesin</span><span class="pm-chip-v" id="chipMesin">&mdash;</span>
          </div>
          <div class="pm-chip">
            <div class="pm-chip-dot" style="background:#16a34a"></div><span class="pm-chip-k">Group</span><span class="pm-chip-v" id="chipGroup">&mdash;</span>
          </div>
          <div class="pm-chip">
            <div class="pm-chip-dot" style="background:#8b5cf6"></div><span class="pm-chip-k">Roll Ke</span><span class="pm-chip-v" id="chipRoll">&mdash;</span>
          </div>
          <div class="pm-chip">
            <div class="pm-chip-dot" style="background:#dc2626"></div><span class="pm-chip-k">PIC</span><span class="pm-chip-v" id="chipPic">&mdash;</span>
          </div>
        </div>

        <!-- Section label -->
        <div class="pm-sec-label">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="18" height="18" rx="2" />
            <path d="M9 9h6M9 12h6M9 15h4" />
          </svg>
          Print Preview &mdash; Thermal 9cm &times; 8cm
        </div>

        <!-- Preview card -->
        <div class="pm-preview-card">
          <div class="pm-preview-bar">
            <div class="pm-pdots">
              <div class="pm-pdot"></div><div class="pm-pdot"></div><div class="pm-pdot"></div>
            </div>
            <span class="pm-preview-title">Thermal Label Preview</span>
            <span class="pm-preview-size">9cm &times; 8cm</span>
          </div>

          <div class="pm-thermal-host">

            <!-- ═══ LABEL 9cm × 8cm ═══ -->
            <div id="label-preview">

              <div class="lbl-header">FORM ROLL</div>

              <div class="lbl-row">
                <span class="lbl-col-l" id="l-tgl-jam"></span>
                <span class="lbl-col-r" id="l-grp-pic"></span>
              </div>

              <div style="margin-top:1.5px" id="l-nama"></div>

              <div class="lbl-line" id="l-roll-reg"></div>

              <div class="sep-double" id="sep1"></div>

              <div class="tbl-header">
                <span class="col-pj">Pj</span>
                <span class="col-lb">Lb</span>
                <span class="col-dn">Denier</span>
                <span class="col-gsm">Anyaman/GSM</span>
              </div>

              <div class="sep-dash" id="sep2"></div>

              <div class="tbl-data" id="l-tbldata"></div>

              <div class="sep-dash" id="sep3"></div>

              <div id="l-berat"></div>
              <div id="l-mesin"></div>
              <div id="l-ket"></div>

              <div class="sep-double" id="sep4"></div>

              <div id="l-trace"></div>
              <div id="l-barcodenum"></div>

              <!-- Barcode 1D -->
              <div class="lbl-barcode-area">
                <div class="lbl-barcode-wrap">
                  <canvas id="barcode1d"></canvas>
                  <div class="lbl-barcode-label" id="bc-label"></div>
                </div>
              </div>

            </div><!-- /#label-preview -->

          </div><!-- /.pm-thermal-host -->
        </div><!-- /.pm-preview-card -->

      </div><!-- /.pm-body -->

      <!-- FOOTER -->
      <div class="pm-footer">
        <div class="pm-footer-left">
          <button class="pm-btn pm-btn-print" id="pmBtnPrint">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
              stroke-width="2">
              <polyline points="6 9 6 2 18 2 18 9" />
              <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
              <rect x="6" y="14" width="12" height="8" />
            </svg>
            Print
          </button>
          <button class="pm-btn pm-btn-pdf" id="pmBtnPdf">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
              stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
              <polyline points="14 2 14 8 20 8" />
              <line x1="12" y1="12" x2="12" y2="18" />
              <polyline points="9 15 12 18 15 15" />
            </svg>
            Download PDF
          </button>
          <button class="pm-btn pm-btn-copy" id="pmBtnCopy">Copy Trace</button>
        </div>
        <div class="pm-footer-right">
          <button class="pm-btn pm-btn-close" id="pmBtnClose">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
              stroke-width="2" style="width:14px;height:14px;margin-right:5px">
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
            Tutup
          </button>
        </div>
      </div>

    </div><!-- /.pm-modal -->
  </div><!-- /.pm-backdrop -->

  <!-- Toast -->
  <div class="pm-toast" id="pmToast">
    <span id="pmToastIco">✓</span>
    <span id="pmToastTxt">OK</span>
  </div>

  <!-- ─── AUTHENTICATION MODALS ──────────────────────── -->
  
  <!-- Admin Verification Modal -->
  <div class="admin-verify-modal" id="adminVerifyModal">
    <div class="admin-verify-card">
      <button class="av-close-top" id="avCloseTopBtn" type="button" aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
      </button>

      <div class="av-header">
        <div class="av-header-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
        </div>
        <div class="av-header-text">
          <div class="av-title">OTORISASI ADMINISTRATOR</div>
          <div class="av-subtitle">Aksi ini membutuhkan izin dari Administrator.</div>
        </div>
      </div>

      <div class="av-alert av-alert-warning">
        <div class="av-alert-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
        </div>
        <div class="av-alert-title">AKSES TERBATAS</div>
        <div class="av-alert-divider"></div>
        <div class="av-alert-text">Hanya untuk tindakan ini</div>
      </div>
      
      <div class="auth-error" id="avError" style="margin: 0 32px 16px;">Kredensial tidak valid.</div>
      
      <form class="auth-form av-form" id="avForm" onsubmit="event.preventDefault();">
        <div class="av-body">
          <div class="field-group with-icon">
            <label class="av-label">Username Administrator</label>
            <div class="input-icon-wrap">
              <span class="input-icon left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
              </span>
              <input type="text" id="avUsername" placeholder="Masukkan username administrator" required autocomplete="username">
            </div>
          </div>
          
          <div class="field-group with-icon">
            <label class="av-label">Password Administrator</label>
            <div class="input-icon-wrap">
              <span class="input-icon left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
              </span>
              <input type="password" id="avPassword" placeholder="Masukkan password administrator" required autocomplete="current-password">
              <button type="button" class="input-icon right toggle-password" id="avTogglePassword" aria-label="Toggle password visibility">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
              </button>
            </div>
          </div>

          <div class="av-alert av-alert-info">
            <div class="av-alert-icon">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
            </div>
            <div class="av-alert-text">
              Aksi ini memerlukan persetujuan administrator.<br>
              Setelah berhasil, Anda akan kembali sebagai operator.
            </div>
          </div>
        </div>

        <div class="av-footer">
          <button type="button" class="btn btn-neutral av-btn-cancel" id="avCancelBtn">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            BATAL
          </button>
          <button type="submit" class="btn btn-primary av-btn-verify" id="avSubmitBtn">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
            VERIFIKASI
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ─── THIRD PARTY LIBRARIES ─────────────────────── -->
  <script src="assets/js/libs/html2canvas.min.js"></script>
  <script src="assets/js/libs/jspdf.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

  <!-- ─── APPLICATION ENTRY POINT ──────────────────── -->
  <script type="module" src="assets/js/app.js"></script>

</body>

</html>