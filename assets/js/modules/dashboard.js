export function initDashboard() {
  const tooltip = document.getElementById('tooltip');
  if (!tooltip) return; // not on dashboard page
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

  const setTxt = (id, val) => {
    const el = document.getElementById(id);
    if (el) el.textContent = val;
  };

  /* ─── SUMMARY CARDS ── */
  function fillSummary(s) {
    setTxt('currentDate', today());
    setTxt('s-hari-ini', s.hariIni);
    setTxt('s-kemarin', s.kemarin);
    setTxt('s-bulan', s.bulan);
    setTxt('s-rata', s.rata);
    setTxt('s-mesin', s.mesinAktif);
    setTxt('s-group', s.groupAktif);
    setTxt('s-shift', s.shiftAktif);
  }

  /* ─── MINI STATS ── */
  function fillMiniStats(top) {
    const listEl = document.getElementById('miniStatsList');
    if (!listEl) return;

    if (!top || top.length === 0) {
      listEl.innerHTML = '<div class="mini-stat" style="text-align:center;color:var(--text-4);padding:20px;">Belum ada data</div>';
      return;
    }
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
    listEl.innerHTML = html;
    // animate bars
    setTimeout(() => {
      document.querySelectorAll('.mini-bar-fill').forEach(el => {
        el.style.width = el.dataset.w;
      });
    }, 400);
  }

  /* ─── BAR CHART ── */
  function buildChart(data) {
    const canvas = document.getElementById('prodChart');
    if (!canvas) return;

    const labels = data.map(d => fmtDateShort(d.tanggal));
    const totals = data.map(d => d.total);
    const maxVal = Math.max(...totals, 1);

    const getColor = (val, alpha = 1) => {
      const ratio = val / maxVal;
      if (ratio > 0.8) return `rgba(29,111,216,${alpha})`;
      if (ratio > 0.5) return `rgba(38,128,232,${alpha})`;
      return `rgba(59,154,245,${alpha})`;
    };

    const bgColors = totals.map(v => getColor(v, 0.85));
    const hoverColors = totals.map(v => getColor(v, 1));

    const ctx = canvas.getContext('2d');

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
            if (val === 0) return;
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
    canvas.addEventListener('mousemove', (e) => {
      const pts = chart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);
      if (!pts.length) { tooltip.style.display = 'none'; return; }
      const idx  = pts[0].index;
      const item = data[idx];
      if (item.total === 0) { tooltip.style.display = 'none'; return; }

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
    const titleEl = document.getElementById('detailTitle');
    const contentEl = document.getElementById('detailContent');
    if (!titleEl || !contentEl) return;

    if (item.total === 0) return;
    activeIdx = idx;
    if (chart) chart.update();

    setTxt('detailTitle', `Detail — ${fmtDate(item.tanggal)}`);
    setTxt('detailSub', `${item.total} data roll diproduksi`);
    
    const countBadge = document.getElementById('detailCount');
    if (countBadge) {
      countBadge.textContent = `${item.total} data`;
      countBadge.style.display = 'block';
    }

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

    contentEl.innerHTML = listHtml;
    contentEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  // Attach filter listeners
  document.querySelectorAll('.filter-btn[data-days]').forEach(btn => {
    btn.addEventListener('click', () => {
      const days = btn.dataset.days;
      
      // Update UI
      document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById('customDateRange').style.display = 'none';
      
      // Re-fetch with new period
      fetchDashboardData(days);
    });
  });

  const customBtn = document.getElementById('customFilterBtn');
  const customPop = document.getElementById('customDateRange');
  const applyBtn  = document.getElementById('applyCustomDash');

  if (customBtn) {
    customBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      customPop.style.display = customPop.style.display === 'none' ? 'block' : 'none';
    });
  }

  if (applyBtn) {
    applyBtn.addEventListener('click', () => {
      const start = document.getElementById('dashStart').value;
      const end   = document.getElementById('dashEnd').value;
      
      if (!start || !end) {
        alert("Silakan pilih rentang tanggal lengkap");
        return;
      }

      // Update UI
      document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
      customBtn.classList.add('active');
      customPop.style.display = 'none';

      fetchDashboardData(null, start, end);
    });
  }

  // Close popover on click outside
  document.addEventListener('click', (e) => {
    if (customPop && !customPop.contains(e.target) && e.target !== customBtn) {
      customPop.style.display = 'none';
    }
  });

  function fetchDashboardData(days = 14, start = null, end = null) {
    let url = `backend/roll.php?action=chart`;
    if (start && end) {
      url += `&start=${start}&end=${end}`;
    } else {
      url += `&days=${days}`;
    }

    fetch(url, {credentials: 'include'})
      .then(r => r.json())
      .then(api => {
        if (api.status === 'success') {
          fillSummary(api.summary);
          fillMiniStats(api.topProduk);
          buildChart(api.data);
        }
      })
      .catch(err => console.error("Failed to fetch dashboard data:", err));
  }

  // Set default dates in inputs
  const todayIso = new Date().toISOString().split('T')[0];
  if (document.getElementById('dashStart')) document.getElementById('dashStart').value = todayIso;
  if (document.getElementById('dashEnd'))   document.getElementById('dashEnd').value = todayIso;

  // Initial load
  fetchDashboardData(7);
}
