<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Produksi — FORM ROLL</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap');

:root {
  --navy:        #0f1e38;
  --navy-2:      #162540;
  --navy-3:      #1c2e50;
  --blue:        #1d6fd8;
  --blue-2:      #2680e8;
  --blue-3:      #3b9af5;
  --blue-soft:   rgba(29,111,216,0.10);
  --blue-glow:   rgba(59,154,245,0.20);
  --teal:        #0ea5c9;
  --green:       #10b981;
  --green-soft:  rgba(16,185,129,0.12);
  --amber:       #f59e0b;
  --amber-soft:  rgba(245,158,11,0.12);
  --rose:        #f43f5e;
  --rose-soft:   rgba(244,63,94,0.10);
  --purple:      #8b5cf6;
  --purple-soft: rgba(139,92,246,0.10);
  --white:       #ffffff;
  --surface:     #f0f4fb;
  --card:        #ffffff;
  --border:      #dde4f0;
  --border-2:    #c8d4e8;
  --text:        #0f1e38;
  --text-2:      #3d5280;
  --text-3:      #7089b0;
  --text-4:      #9fb3cc;
  --shadow-sm:   0 1px 4px rgba(15,30,56,0.07), 0 0 0 1px rgba(15,30,56,0.05);
  --shadow-md:   0 4px 16px rgba(15,30,56,0.10), 0 1px 4px rgba(15,30,56,0.07);
  --shadow-lg:   0 12px 40px rgba(15,30,56,0.13), 0 2px 8px rgba(15,30,56,0.08);
  --radius:      12px;
  --radius-sm:   8px;
  --radius-lg:   16px;
  --font:        'DM Sans', system-ui, sans-serif;
  --mono:        'DM Mono', monospace;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }

body {
  font-family: var(--font);
  background: var(--surface);
  color: var(--text);
  min-height: 100vh;
  line-height: 1.5;
  -webkit-font-smoothing: antialiased;
}

/* ─── TOPBAR ─────────────────────────────────── */
.topbar {
  background: linear-gradient(100deg, var(--navy) 0%, var(--navy-3) 100%);
  padding: 0 28px;
  height: 54px;
  display: flex;
  align-items: center;
  gap: 14px;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 2px 12px rgba(15,30,56,0.3);
}

.topbar-logo {
  width: 32px; height: 32px;
  background: linear-gradient(135deg, var(--blue) 0%, var(--blue-3) 100%);
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.topbar-logo svg { width: 17px; height: 17px; fill: #fff; }
.topbar-brand {
  display: flex;
  flex-direction: column;
  line-height: 1.15;
}
.topbar-title {
  font-size: 14px;
  font-weight: 700;
  color: #fff;
  letter-spacing: 0.4px;
}
.topbar-sub {
  font-size: 10px;
  color: rgba(255,255,255,0.4);
  font-weight: 400;
}

.topbar-sep { flex: 1; }

.topbar-nav {
  display: flex;
  gap: 2px;
}
.nav-link {
  font-size: 11.5px;
  font-weight: 500;
  color: rgba(255,255,255,0.5);
  padding: 6px 12px;
  border-radius: 6px;
  cursor: pointer;
  transition: all .15s;
  text-decoration: none;
  letter-spacing: 0.2px;
}
.nav-link:hover { color: rgba(255,255,255,0.85); background: rgba(255,255,255,0.07); }
.nav-link.active { color: #fff; background: rgba(59,154,245,0.2); }

.badge-online {
  display: flex; align-items: center; gap: 5px;
  background: rgba(16,185,129,0.15);
  border: 1px solid rgba(16,185,129,0.3);
  border-radius: 20px;
  padding: 3px 10px;
  margin-left: 8px;
}
.badge-dot { width: 6px; height: 6px; background: var(--green); border-radius: 50%; box-shadow: 0 0 6px var(--green); animation: pulse 2s infinite; }
.badge-txt { font-size: 10px; font-weight: 600; color: var(--green); letter-spacing: 0.5px; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.45} }

/* ─── LAYOUT ─────────────────────────────────── */
.page {
  max-width: 1280px;
  margin: 0 auto;
  padding: 24px 28px 40px;
}

.page-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  margin-bottom: 22px;
  flex-wrap: wrap;
  gap: 12px;
}
.page-title {
  font-size: 20px;
  font-weight: 700;
  color: var(--text);
  letter-spacing: -0.3px;
}
.page-sub { font-size: 12.5px; color: var(--text-3); margin-top: 2px; }

.date-badge {
  display: flex; align-items: center; gap: 7px;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 7px 12px;
  font-size: 12px;
  color: var(--text-2);
  font-weight: 500;
  box-shadow: var(--shadow-sm);
}
.date-badge svg { width: 13px; height: 13px; color: var(--blue); }

/* ─── SUMMARY CARDS ──────────────────────────── */
.summary-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 14px;
  margin-bottom: 22px;
}

.stat-card {
  background: var(--card);
  border-radius: var(--radius);
  padding: 18px 20px;
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--border);
  position: relative;
  overflow: hidden;
  transition: box-shadow .2s, transform .2s;
  animation: cardIn .4s ease both;
}
.stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
.stat-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 3px;
  border-radius: 12px 12px 0 0;
}
.stat-card.blue::before   { background: linear-gradient(90deg, var(--blue), var(--blue-3)); }
.stat-card.green::before  { background: linear-gradient(90deg, #059669, var(--green)); }
.stat-card.amber::before  { background: linear-gradient(90deg, #d97706, var(--amber)); }
.stat-card.purple::before { background: linear-gradient(90deg, #7c3aed, var(--purple)); }

@keyframes cardIn {
  from { opacity:0; transform: translateY(12px); }
  to   { opacity:1; transform: translateY(0); }
}
.stat-card:nth-child(1){ animation-delay:.05s }
.stat-card:nth-child(2){ animation-delay:.10s }
.stat-card:nth-child(3){ animation-delay:.15s }
.stat-card:nth-child(4){ animation-delay:.20s }

.stat-top {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 12px;
}
.stat-icon {
  width: 38px; height: 38px;
  border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
}
.stat-icon.blue   { background: var(--blue-soft); }
.stat-icon.green  { background: var(--green-soft); }
.stat-icon.amber  { background: var(--amber-soft); }
.stat-icon.purple { background: var(--purple-soft); }
.stat-icon svg { width: 18px; height: 18px; }
.stat-icon.blue svg   { color: var(--blue); }
.stat-icon.green svg  { color: var(--green); }
.stat-icon.amber svg  { color: var(--amber); }
.stat-icon.purple svg { color: var(--purple); }

.stat-trend {
  font-size: 10.5px;
  font-weight: 600;
  padding: 2px 7px;
  border-radius: 20px;
  display: flex; align-items: center; gap: 3px;
}
.stat-trend.up   { background: var(--green-soft); color: #059669; }
.stat-trend.same { background: var(--amber-soft);  color: #b45309; }
.stat-trend svg { width: 10px; height: 10px; }

.stat-value {
  font-size: 28px;
  font-weight: 700;
  color: var(--text);
  letter-spacing: -1px;
  line-height: 1;
  margin-bottom: 4px;
  font-variant-numeric: tabular-nums;
}
.stat-label {
  font-size: 11.5px;
  color: var(--text-3);
  font-weight: 500;
  letter-spacing: 0.2px;
}
.stat-foot {
  margin-top: 10px;
  padding-top: 10px;
  border-top: 1px solid var(--border);
  font-size: 10.5px;
  color: var(--text-4);
}
.stat-foot strong { color: var(--text-3); }

/* ─── CHART SECTION ──────────────────────────── */
.chart-row {
  display: grid;
  grid-template-columns: 1fr 340px;
  gap: 16px;
  margin-bottom: 16px;
  align-items: start;
}

.card {
  background: var(--card);
  border-radius: var(--radius);
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--border);
  overflow: hidden;
  animation: cardIn .45s ease both;
  animation-delay: .25s;
}

.card-header {
  padding: 16px 20px 14px;
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
}
.card-title-wrap { display: flex; align-items: center; gap: 9px; }
.card-icon {
  width: 30px; height: 30px;
  border-radius: 7px;
  background: var(--blue-soft);
  display: flex; align-items: center; justify-content: center;
}
.card-icon svg { width: 15px; height: 15px; color: var(--blue); }
.card-title {
  font-size: 13.5px;
  font-weight: 700;
  color: var(--text);
  letter-spacing: -0.1px;
}
.card-sub { font-size: 11px; color: var(--text-3); margin-top: 1px; }

.chip {
  font-size: 10.5px;
  font-weight: 600;
  padding: 3px 9px;
  border-radius: 20px;
  background: var(--blue-soft);
  color: var(--blue);
  border: 1px solid rgba(29,111,216,0.15);
  white-space: nowrap;
}

.card-body { padding: 18px 20px; }

/* chart wrapper */
.chart-wrap {
  position: relative;
  height: 280px;
}

/* ─── MINI STATS SIDEBAR ─────────────────────── */
.mini-stats-card {
  animation-delay: .3s;
}
.mini-stat {
  padding: 14px 18px;
  border-bottom: 1px solid var(--border);
  transition: background .15s;
  cursor: default;
}
.mini-stat:last-child { border-bottom: none; }
.mini-stat:hover { background: #f7f9fd; }
.mini-stat-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 8px;
}
.mini-stat-label {
  font-size: 11.5px;
  font-weight: 600;
  color: var(--text-2);
  display: flex; align-items: center; gap: 6px;
}
.mini-dot {
  width: 7px; height: 7px;
  border-radius: 50%;
  flex-shrink: 0;
}
.mini-stat-val {
  font-size: 17px;
  font-weight: 700;
  color: var(--text);
  font-variant-numeric: tabular-nums;
}
.mini-bar-bg {
  height: 5px;
  background: var(--surface);
  border-radius: 10px;
  overflow: hidden;
}
.mini-bar-fill {
  height: 100%;
  border-radius: 10px;
  transition: width .8s cubic-bezier(.22,.68,0,1.1);
  width: 0%;
}

/* ─── DETAIL PANEL ───────────────────────────── */
.detail-panel {
  animation-delay: .35s;
}
.detail-empty {
  padding: 32px 20px;
  text-align: center;
  color: var(--text-4);
}
.detail-empty-icon {
  width: 44px; height: 44px;
  margin: 0 auto 10px;
  background: var(--surface);
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
}
.detail-empty-icon svg { width: 22px; height: 22px; color: var(--text-4); }
.detail-empty-title { font-size: 13px; font-weight: 600; color: var(--text-3); margin-bottom: 4px; }
.detail-empty-sub   { font-size: 11.5px; color: var(--text-4); }

.detail-date-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 20px 12px;
  border-bottom: 1px solid var(--border);
  background: linear-gradient(100deg, #f7faff 0%, #ffffff 100%);
}
.detail-date-label {
  font-size: 11px;
  font-weight: 600;
  color: var(--text-3);
  letter-spacing: 0.5px;
  text-transform: uppercase;
  margin-bottom: 2px;
}
.detail-date-val {
  font-size: 16px;
  font-weight: 700;
  color: var(--text);
  font-family: var(--mono);
  letter-spacing: -0.3px;
}
.detail-total-badge {
  background: var(--blue-soft);
  border: 1px solid rgba(29,111,216,0.18);
  border-radius: 8px;
  padding: 6px 12px;
  text-align: center;
}
.detail-total-num {
  font-size: 20px;
  font-weight: 700;
  color: var(--blue);
  display: block;
  line-height: 1;
  font-variant-numeric: tabular-nums;
}
.detail-total-lbl { font-size: 10px; color: var(--text-3); font-weight: 500; }

.detail-list { overflow-y: auto; max-height: 320px; }
.detail-item {
  padding: 12px 20px;
  border-bottom: 1px solid #f0f4f8;
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 4px 10px;
  align-items: start;
  transition: background .12s;
  animation: itemIn .25s ease both;
}
.detail-item:hover { background: #f7fafd; }
.detail-item:last-child { border-bottom: none; }

@keyframes itemIn {
  from { opacity:0; transform: translateX(-8px); }
  to   { opacity:1; transform: translateX(0); }
}

.item-nama {
  font-size: 12.5px;
  font-weight: 700;
  color: var(--text);
  grid-column: 1;
}
.item-meta {
  font-size: 10.5px;
  color: var(--text-3);
  grid-column: 1;
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}
.item-meta span { display: flex; align-items: center; gap: 3px; }
.item-meta svg { width: 10px; height: 10px; }
.item-roll {
  grid-column: 2;
  grid-row: 1 / 3;
  align-self: center;
  background: var(--navy);
  color: #fff;
  font-size: 10px;
  font-weight: 700;
  font-family: var(--mono);
  padding: 3px 7px;
  border-radius: 5px;
  white-space: nowrap;
}
.item-pic {
  background: var(--blue-soft);
  color: var(--blue-2);
  font-size: 9.5px;
  font-weight: 700;
  padding: 2px 6px;
  border-radius: 4px;
  white-space: nowrap;
  font-family: var(--mono);
}
.item-mesin {
  background: var(--surface);
  color: var(--text-2);
  font-size: 9.5px;
  font-weight: 600;
  padding: 2px 6px;
  border-radius: 4px;
  white-space: nowrap;
}

/* ─── TOOLTIP ────────────────────────────────── */
.custom-tooltip {
  position: fixed;
  background: var(--navy);
  color: #fff;
  border-radius: 9px;
  padding: 11px 14px;
  font-size: 11.5px;
  font-family: var(--font);
  box-shadow: 0 8px 30px rgba(15,30,56,0.4);
  pointer-events: none;
  z-index: 9999;
  min-width: 180px;
  display: none;
  border: 1px solid rgba(255,255,255,0.08);
}
.tt-date { font-weight: 700; font-size: 12px; margin-bottom: 6px; color: var(--blue-3); }
.tt-total { display: flex; align-items: center; gap: 6px; margin-bottom: 6px; font-weight: 600; }
.tt-total-num { font-size: 15px; font-weight: 700; }
.tt-sep { height: 1px; background: rgba(255,255,255,0.1); margin: 6px 0; }
.tt-items-label { font-size: 10px; color: rgba(255,255,255,0.45); font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 5px; }
.tt-item { font-size: 11px; color: rgba(255,255,255,0.75); padding: 2px 0; display: flex; align-items: center; gap: 6px; }
.tt-item::before { content: ''; width: 5px; height: 5px; background: var(--blue-3); border-radius: 50%; flex-shrink: 0; }
.tt-more { font-size: 10px; color: rgba(255,255,255,0.35); font-style: italic; margin-top: 3px; }

/* ─── GRID OVERLAY ON CHART ──────────────────── */
.chart-wrap::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(29,111,216,0.025) 1px, transparent 1px),
    linear-gradient(90deg, rgba(29,111,216,0.025) 1px, transparent 1px);
  background-size: 40px 40px;
  pointer-events: none;
  border-radius: 8px;
}

/* ─── RESPONSIVE ─────────────────────────────── */
@media (max-width: 1100px) {
  .summary-grid { grid-template-columns: repeat(2, 1fr); }
  .chart-row    { grid-template-columns: 1fr; }
  .mini-stats-card { display: grid; grid-template-columns: repeat(3, 1fr); }
  .mini-stat { border-bottom: none; border-right: 1px solid var(--border); }
  .mini-stat:last-child { border-right: none; }
}
@media (max-width: 700px) {
  .page { padding: 16px; }
  .summary-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
  .topbar { padding: 0 16px; }
  .topbar-nav { display: none; }
}
@media (max-width: 500px) {
  .summary-grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<!-- TOPBAR -->
<div class="topbar">
  <div class="topbar-logo">
    <svg viewBox="0 0 24 24"><path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
  </div>
  <div class="topbar-brand">
    <div class="topbar-title">FORM ROLL</div>
    <div class="topbar-sub">PT Langgeng Jaya Plastindo</div>
  </div>
  <div class="topbar-sep"></div>
  <nav class="topbar-nav">
    <a class="nav-link" href="#">Input Data</a>
    <a class="nav-link active" href="#">Dashboard</a>
    <a class="nav-link" href="#">Laporan</a>
    <a class="nav-link" href="#">Pengaturan</a>
  </nav>
  <div class="badge-online">
    <div class="badge-dot"></div>
    <span class="badge-txt">ONLINE</span>
  </div>
</div>

<!-- PAGE -->
<div class="page">
  <div class="page-header">
    <div>
      <div class="page-title">Dashboard Produksi</div>
      <div class="page-sub">Statistik dan monitoring data roll harian</div>
    </div>
    <div class="date-badge">
      <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 4h-1V2h-2v2H8V2H6v2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/></svg>
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
        <div class="stat-trend up">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 14l5-5 5 5z"/></svg>
          +3
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
        <div class="stat-trend up">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 14l5-5 5 5z"/></svg>
          +12%
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
        <div class="stat-trend same">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 12H4v-2h16v2z"/></svg>
          Stabil
        </div>
      </div>
      <div class="stat-value" id="s-mesin">–</div>
      <div class="stat-label">Mesin Aktif</div>
      <div class="stat-foot">Dari total <strong>34</strong> mesin terdaftar</div>
    </div>

    <div class="stat-card purple">
      <div class="stat-top">
        <div class="stat-icon purple">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
        </div>
        <div class="stat-trend up">
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
        <div class="chip" id="periodChip">14 Hari Terakhir</div>
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
            <div class="card-sub">Bulan ini</div>
          </div>
        </div>
      </div>
      <div id="miniStatsList"></div>
    </div>
  </div>

  <!-- DETAIL PANEL -->
  <div class="card detail-panel">
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
  </div>

</div><!-- .page -->

<!-- TOOLTIP -->
<div class="custom-tooltip" id="tooltip"></div>

<script>
/* ═══════════════════════════════════════════════
   MOCK DATA  (replace with fetch('/api/dashboard/production-chart'))
═══════════════════════════════════════════════ */
const MOCK_API = {
  status: "success",
  summary: {
    hariIni: 7, kemarin: 4, bulan: 89, rata: 6,
    mesinAktif: 12, groupAktif: 3, shiftAktif: "A/B"
  },
  topProduk: [
    { nama: "PP SOLAR",  total: 24, color: "#1d6fd8" },
    { nama: "PP HITAM",  total: 19, color: "#0ea5c9" },
    { nama: "PP BUS",    total: 15, color: "#10b981" },
    { nama: "PP GEOTEX", total: 12, color: "#f59e0b" },
    { nama: "PP AWIO",   total: 11, color: "#8b5cf6" },
  ],
  data: [
    { tanggal:"2026-05-02", total:5,  items:[
      {nama:"PP SOLAR",roll:121,mesin:12,group:"A",jam:"07:10",pic:"BASOE/DS"},
      {nama:"PP BUS",  roll:122,mesin:12,group:"A",jam:"08:45",pic:"BASOE/DS"},
      {nama:"PP HITAM",roll:123,mesin:11,group:"B",jam:"10:22",pic:"ANGGA/A"},
      {nama:"PP SOLAR",roll:124,mesin:12,group:"B",jam:"13:00",pic:"BASOE/DS"},
      {nama:"PP AWIO", roll:125,mesin:34,group:"C",jam:"15:30",pic:"OK/DS"},
    ]},
    { tanggal:"2026-05-03", total:3,  items:[
      {nama:"PP HITAM",roll:126,mesin:11,group:"A",jam:"08:00",pic:"ANGGA/A"},
      {nama:"PP SDK",  roll:127,mesin:1, group:"A",jam:"09:30",pic:"JOK"},
      {nama:"PP SOLAR",roll:128,mesin:12,group:"C",jam:"14:00",pic:"BASOE/DS"},
    ]},
    { tanggal:"2026-05-04", total:8,  items:[
      {nama:"PP GEOTEX",roll:129,mesin:17,group:"C",jam:"07:00",pic:"BASOR/DIKY"},
      {nama:"PP SOLAR", roll:130,mesin:12,group:"A",jam:"08:10",pic:"BASOE/DS"},
      {nama:"PP BUS",   roll:131,mesin:12,group:"A",jam:"09:20",pic:"BASOE/DS"},
      {nama:"PP HITAM", roll:132,mesin:11,group:"B",jam:"11:00",pic:"ANGGA/A"},
      {nama:"PP HOJUT", roll:133,mesin:12,group:"A",jam:"12:30",pic:"CBA/SIIJ"},
      {nama:"PP AWIO",  roll:134,mesin:34,group:"C",jam:"14:00",pic:"OK/DS"},
      {nama:"PP SOLAR", roll:135,mesin:12,group:"B",jam:"15:30",pic:"BASOE/DS"},
      {nama:"PP KS",    roll:136,mesin:13,group:"A",jam:"16:40",pic:"NJ/DS"},
    ]},
    { tanggal:"2026-05-05", total:4,  items:[
      {nama:"PP SOLAR",roll:137,mesin:12,group:"A",jam:"07:30",pic:"BASOE/DS"},
      {nama:"PP HITAM",roll:138,mesin:11,group:"B",jam:"10:00",pic:"ANGGA/A"},
      {nama:"PP BUS",  roll:139,mesin:12,group:"C",jam:"13:20",pic:"BASOE/DS"},
      {nama:"PP SDK",  roll:140,mesin:1, group:"A",jam:"15:00",pic:"JOK"},
    ]},
    { tanggal:"2026-05-06", total:6,  items:[
      {nama:"PP GEOTEX",roll:141,mesin:17,group:"C",jam:"07:00",pic:"BASOR/DIKY"},
      {nama:"PP SOLAR", roll:142,mesin:12,group:"A",jam:"08:30",pic:"BASOE/DS"},
      {nama:"PP HITAM", roll:143,mesin:11,group:"B",jam:"10:00",pic:"ANGGA/A"},
      {nama:"PP BUS",   roll:144,mesin:12,group:"A",jam:"12:00",pic:"BASOE/DS"},
      {nama:"PP AWIO",  roll:145,mesin:34,group:"C",jam:"14:30",pic:"OK/DS"},
      {nama:"PP HOJUT", roll:146,mesin:12,group:"A",jam:"16:00",pic:"CBA/SIIJ"},
    ]},
    { tanggal:"2026-05-07", total:2,  items:[
      {nama:"PP SOLAR",roll:147,mesin:12,group:"B",jam:"09:00",pic:"BASOE/DS"},
      {nama:"PP HITAM",roll:148,mesin:11,group:"A",jam:"13:00",pic:"ANGGA/A"},
    ]},
    { tanggal:"2026-05-08", total:9,  items:[
      {nama:"PP SOLAR", roll:149,mesin:12,group:"A",jam:"07:00",pic:"BASOE/DS"},
      {nama:"PP BUS",   roll:150,mesin:12,group:"A",jam:"08:00",pic:"BASOE/DS"},
      {nama:"PP HITAM", roll:151,mesin:11,group:"B",jam:"09:30",pic:"ANGGA/A"},
      {nama:"PP GEOTEX",roll:152,mesin:17,group:"C",jam:"11:00",pic:"BASOR/DIKY"},
      {nama:"PP HOJUT", roll:153,mesin:12,group:"A",jam:"12:30",pic:"CBA/SIIJ"},
      {nama:"PP AWIO",  roll:154,mesin:34,group:"C",jam:"14:00",pic:"OK/DS"},
      {nama:"PP KS",    roll:155,mesin:13,group:"A",jam:"15:00",pic:"NJ/DS"},
      {nama:"PP SDK",   roll:156,mesin:1, group:"B",jam:"16:00",pic:"JOK"},
      {nama:"PP SOLAR", roll:157,mesin:12,group:"C",jam:"17:00",pic:"BASOE/DS"},
    ]},
    { tanggal:"2026-05-09", total:5,  items:[
      {nama:"PP SOLAR",roll:158,mesin:12,group:"A",jam:"07:30",pic:"BASOE/DS"},
      {nama:"PP HITAM",roll:159,mesin:11,group:"B",jam:"09:00",pic:"ANGGA/A"},
      {nama:"PP BUS",  roll:160,mesin:12,group:"A",jam:"11:30",pic:"BASOE/DS"},
      {nama:"PP AWIO", roll:161,mesin:34,group:"C",jam:"14:00",pic:"OK/DS"},
      {nama:"PP GEOTEX",roll:162,mesin:17,group:"C",jam:"16:00",pic:"BASOR/DIKY"},
    ]},
    { tanggal:"2026-05-10", total:7,  items:[
      {nama:"PP SOLAR", roll:163,mesin:12,group:"A",jam:"07:00",pic:"BASOE/DS"},
      {nama:"PP BUS",   roll:164,mesin:12,group:"A",jam:"08:30",pic:"BASOE/DS"},
      {nama:"PP HITAM", roll:165,mesin:11,group:"B",jam:"10:00",pic:"ANGGA/A"},
      {nama:"PP HOJUT", roll:166,mesin:12,group:"A",jam:"12:00",pic:"CBA/SIIJ"},
      {nama:"PP GEOTEX",roll:167,mesin:17,group:"C",jam:"13:30",pic:"BASOR/DIKY"},
      {nama:"PP KS",    roll:168,mesin:13,group:"A",jam:"15:00",pic:"NJ/DS"},
      {nama:"PP SOLAR", roll:169,mesin:12,group:"B",jam:"16:30",pic:"BASOE/DS"},
    ]},
    { tanggal:"2026-05-11", total:12, items:[
      {nama:"PP SOLAR", roll:170,mesin:12,group:"A",jam:"07:00",pic:"BASOE/DS"},
      {nama:"PP BUS",   roll:171,mesin:12,group:"A",jam:"08:00",pic:"BASOE/DS"},
      {nama:"PP HITAM", roll:172,mesin:11,group:"B",jam:"09:00",pic:"ANGGA/A"},
      {nama:"PP GEOTEX",roll:173,mesin:17,group:"C",jam:"10:00",pic:"BASOR/DIKY"},
      {nama:"PP HOJUT", roll:174,mesin:12,group:"A",jam:"11:00",pic:"CBA/SIIJ"},
      {nama:"PP AWIO",  roll:175,mesin:34,group:"C",jam:"12:00",pic:"OK/DS"},
      {nama:"PP KS",    roll:176,mesin:13,group:"A",jam:"13:00",pic:"NJ/DS"},
      {nama:"PP SOLAR", roll:177,mesin:12,group:"B",jam:"14:00",pic:"BASOE/DS"},
      {nama:"PP SDK",   roll:178,mesin:1, group:"A",jam:"15:00",pic:"JOK"},
      {nama:"PP HITAM", roll:179,mesin:11,group:"C",jam:"16:00",pic:"ANGGA/A"},
      {nama:"PP BUS",   roll:180,mesin:12,group:"B",jam:"17:00",pic:"BASOE/DS"},
      {nama:"PP DSJSN", roll:181,mesin:12,group:"B",jam:"18:00",pic:"SJQI"},
    ]},
    { tanggal:"2026-05-12", total:4,  items:[
      {nama:"PP HITAM",roll:182,mesin:11,group:"B",jam:"13:47",pic:"ANGGA/A"},
      {nama:"PP SHOW", roll:183,mesin:12,group:"A",jam:"10:39",pic:"AGA/ASD"},
      {nama:"PP DSJSN",roll:184,mesin:12,group:"B",jam:"10:58",pic:"SJQI"},
      {nama:"PP SDK",  roll:185,mesin:1, group:"A",jam:"10:58",pic:"JOK"},
    ]},
    { tanggal:"2026-05-13", total:6,  items:[
      {nama:"PP SOLAR", roll:186,mesin:12,group:"A",jam:"07:30",pic:"BASOE/DS"},
      {nama:"PP HITAM", roll:187,mesin:11,group:"B",jam:"09:00",pic:"ANGGA/A"},
      {nama:"PP GEOTEX",roll:188,mesin:17,group:"C",jam:"11:00",pic:"BASOR/DIKY"},
      {nama:"PP BUS",   roll:189,mesin:12,group:"A",jam:"13:00",pic:"BASOE/DS"},
      {nama:"PP AWIO",  roll:190,mesin:34,group:"C",jam:"15:00",pic:"OK/DS"},
      {nama:"PP HOJUT", roll:191,mesin:12,group:"A",jam:"16:30",pic:"CBA/SIIJ"},
    ]},
    { tanggal:"2026-05-14", total:10, items:[
      {nama:"PP SOLAR", roll:192,mesin:12,group:"A",jam:"07:00",pic:"BASOE/DS"},
      {nama:"PP BUS",   roll:193,mesin:12,group:"A",jam:"08:00",pic:"BASOE/DS"},
      {nama:"PP HITAM", roll:194,mesin:11,group:"B",jam:"09:30",pic:"ANGGA/A"},
      {nama:"PP KS",    roll:195,mesin:13,group:"A",jam:"11:00",pic:"NJ/DS"},
      {nama:"PP GEOTEX",roll:196,mesin:17,group:"C",jam:"12:30",pic:"BASOR/DIKY"},
      {nama:"PP HOJUT", roll:197,mesin:12,group:"A",jam:"14:00",pic:"CBA/SIIJ"},
      {nama:"PP AWIO",  roll:198,mesin:34,group:"C",jam:"15:30",pic:"OK/DS"},
      {nama:"PP SOLAR", roll:199,mesin:12,group:"B",jam:"16:30",pic:"BASOE/DS"},
      {nama:"PP SDK",   roll:200,mesin:1, group:"A",jam:"17:00",pic:"JOK"},
      {nama:"PP HITAM", roll:201,mesin:11,group:"C",jam:"17:30",pic:"ANGGA/A"},
    ]},
    { tanggal:"2026-05-15", total:7,  items:[
      {nama:"PP SOLAR", roll:202,mesin:12,group:"A",jam:"07:10",pic:"BASOE/DS"},
      {nama:"PP GEOTEX",roll:203,mesin:17,group:"C",jam:"08:00",pic:"BASOR/DIKY"},
      {nama:"PP HITAM", roll:204,mesin:11,group:"B",jam:"09:30",pic:"ANGGA/A"},
      {nama:"PP BUS",   roll:205,mesin:12,group:"A",jam:"11:00",pic:"BASOE/DS"},
      {nama:"PP HOJUT", roll:206,mesin:12,group:"A",jam:"13:00",pic:"CBA/SIIJ"},
      {nama:"PP AWIO",  roll:207,mesin:34,group:"C",jam:"14:30",pic:"OK/DS"},
      {nama:"PP KS",    roll:208,mesin:13,group:"B",jam:"16:00",pic:"NJ/DS"},
    ]},
  ]
};

/* ═══════════════════════════════════════════════
   INIT
═══════════════════════════════════════════════ */
const tooltip = document.getElementById('tooltip');
let chart = null;
let activeIdx = null;

function fmtDate(iso) {
  const [y, m, d] = iso.split('-');
  return `${d}/${m}/${y}`;
}
function fmtDateShort(iso) {
  const [, m, d] = iso.split('-');
  return `${d}/${m}`;
}
function today() {
  return new Date().toLocaleDateString('id-ID',{weekday:'long',year:'numeric',month:'long',day:'numeric'});
}

/* ─── SUMMARY CARDS ── */
function fillSummary(s) {
  document.getElementById('currentDate').textContent = today();
  document.getElementById('s-hari-ini').textContent = s.hariIni;
  document.getElementById('s-kemarin').textContent  = s.kemarin;
  document.getElementById('s-bulan').textContent    = s.bulan;
  document.getElementById('s-rata').textContent     = s.rata;
  document.getElementById('s-mesin').textContent    = s.mesinAktif;
  document.getElementById('s-group').textContent    = s.groupAktif;
  document.getElementById('s-shift').textContent    = s.shiftAktif;
}

/* ─── MINI STATS ── */
function fillMiniStats(top) {
  const max = top[0].total;
  const colors = top.map(t => t.color);
  const html = top.map((p,i) => `
    <div class="mini-stat">
      <div class="mini-stat-top">
        <div class="mini-stat-label">
          <span class="mini-dot" style="background:${colors[i]}"></span>
          ${p.nama}
        </div>
        <div class="mini-stat-val">${p.total}</div>
      </div>
      <div class="mini-bar-bg">
        <div class="mini-bar-fill" style="background:${colors[i]};width:0%" data-w="${Math.round(p.total/max*100)}%"></div>
      </div>
    </div>
  `).join('');
  document.getElementById('miniStatsList').innerHTML = html;
  // animate bars
  setTimeout(() => {
    document.querySelectorAll('.mini-bar-fill').forEach(el => {
      el.style.width = el.dataset.w;
    });
  }, 400);
}

/* ─── BAR CHART ── */
function buildChart(data) {
  const labels = data.map(d => fmtDateShort(d.tanggal));
  const totals = data.map(d => d.total);
  const maxVal = Math.max(...totals);

  const getColor = (val, alpha = 1) => {
    const ratio = val / maxVal;
    if (ratio > 0.8) return `rgba(29,111,216,${alpha})`;
    if (ratio > 0.5) return `rgba(38,128,232,${alpha})`;
    return `rgba(59,154,245,${alpha})`;
  };

  const bgColors = totals.map(v => getColor(v, 0.85));
  const hoverColors = totals.map(v => getColor(v, 1));

  const ctx = document.getElementById('prodChart').getContext('2d');

  if (chart) chart.destroy();

  chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        data: totals,
        backgroundColor: bgColors,
        hoverBackgroundColor: hoverColors,
        borderRadius: 6,
        borderSkipped: false,
        borderWidth: 0,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      animation: { duration: 700, easing: 'easeOutQuart' },
      plugins: {
        legend: { display: false },
        tooltip: { enabled: false },
        datalabels: false,
      },
      scales: {
        x: {
          grid: { display: false },
          border: { display: false },
          ticks: {
            color: '#7089b0',
            font: { family: "'DM Sans',sans-serif", size: 10.5, weight: '500' },
          }
        },
        y: {
          beginAtZero: true,
          border: { display: false, dash: [4,4] },
          grid: { color: 'rgba(29,111,216,0.07)', lineWidth: 1 },
          ticks: {
            color: '#7089b0',
            font: { family: "'DM Mono',monospace", size: 10 },
            stepSize: Math.ceil(maxVal / 5),
            padding: 8,
          }
        }
      },
      onHover: (event, elements) => {
        event.native.target.style.cursor = elements.length ? 'pointer' : 'default';
      },
      onClick: (event, elements) => {
        if (!elements.length) return;
        const idx = elements[0].index;
        showDetail(data[idx], idx);
      }
    },
    plugins: [{
      id: 'dataLabels',
      afterDatasetsDraw(chart) {
        const { ctx, data, scales } = chart;
        ctx.save();
        data.datasets[0].data.forEach((val, i) => {
          const meta = chart.getDatasetMeta(0);
          const bar  = meta.data[i];
          ctx.fillStyle = activeIdx === i ? '#0f1e38' : '#3d5280';
          ctx.font = `600 10.5px 'DM Sans', sans-serif`;
          ctx.textAlign = 'center';
          ctx.textBaseline = 'bottom';
          ctx.fillText(val, bar.x, bar.y - 4);
        });
        ctx.restore();
      }
    }]
  });

  // custom tooltip + hover
  const canvas = document.getElementById('prodChart');
  canvas.addEventListener('mousemove', (e) => {
    const pts = chart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);
    if (!pts.length) { tooltip.style.display = 'none'; return; }
    const idx  = pts[0].index;
    const item = data[idx];
    const names = item.items.slice(0,5).map(i => i.nama);
    const more  = item.items.length > 5 ? item.items.length - 5 : 0;

    tooltip.innerHTML = `
      <div class="tt-date">${fmtDate(item.tanggal)}</div>
      <div class="tt-total">
        <span>Total Data:</span>
        <span class="tt-total-num">${item.total}</span>
      </div>
      <div class="tt-sep"></div>
      <div class="tt-items-label">Produk</div>
      ${names.map(n => `<div class="tt-item">${n}</div>`).join('')}
      ${more ? `<div class="tt-more">+${more} lainnya</div>` : ''}
    `;
    tooltip.style.display = 'block';

    const rect = canvas.getBoundingClientRect();
    let tx = e.clientX + 14;
    let ty = e.clientY - 10;
    if (tx + 200 > window.innerWidth) tx = e.clientX - 200;
    tooltip.style.left = tx + 'px';
    tooltip.style.top  = ty + 'px';
  });
  canvas.addEventListener('mouseleave', () => { tooltip.style.display = 'none'; });
}

/* ─── DETAIL PANEL ── */
function showDetail(item, idx) {
  activeIdx = idx;
  if (chart) chart.update();

  document.getElementById('detailTitle').textContent = `Detail — ${fmtDate(item.tanggal)}`;
  document.getElementById('detailSub').textContent   = `${item.total} data roll diproduksi`;
  const countBadge = document.getElementById('detailCount');
  countBadge.textContent = `${item.total} data`;
  countBadge.style.display = 'block';

  const groupIcon = `<svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>`;
  const mesinIcon = `<svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>`;
  const timeIcon  = `<svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/></svg>`;

  const listHtml = `
    <div class="detail-date-header">
      <div>
        <div class="detail-date-label">Tanggal Produksi</div>
        <div class="detail-date-val">${fmtDate(item.tanggal)}</div>
      </div>
      <div class="detail-total-badge">
        <span class="detail-total-num">${item.total}</span>
        <span class="detail-total-lbl">Total Data</span>
      </div>
    </div>
    <div class="detail-list">
      ${item.items.map((r,i) => `
        <div class="detail-item" style="animation-delay:${i*0.04}s">
          <div class="item-nama">${r.nama}</div>
          <div class="item-meta">
            <span>${groupIcon} Grp ${r.group}</span>
            <span>${mesinIcon} Mesin ${r.mesin}</span>
            <span>${timeIcon} ${r.jam}</span>
            <span class="item-pic">${r.pic}</span>
          </div>
          <div class="item-roll">R#${r.roll}</div>
        </div>
      `).join('')}
    </div>
  `;

  document.getElementById('detailContent').innerHTML = listHtml;
  document.getElementById('detailContent').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

/* ═══════════════════════════════════════════════
   BOOT
═══════════════════════════════════════════════ */
(function init() {
  /* In production, replace with:
     fetch('/api/dashboard/production-chart')
       .then(r => r.json())
       .then(api => { ...build from api... });
  */
  const api = MOCK_API;
  fillSummary(api.summary);
  fillMiniStats(api.topProduk);
  buildChart(api.data);
  document.getElementById('periodChip').textContent = `${api.data.length} Hari`;
})();
</script>
</body>
</html>