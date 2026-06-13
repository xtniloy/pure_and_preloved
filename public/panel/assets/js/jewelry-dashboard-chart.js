/**
 * Jewelry e-commerce dashboard charts
 * NOTE: data here is sample/placeholder data for the template.
 * Wire these arrays to backend values when the dashboard is made functional.
 */
window.addEventListener('DOMContentLoaded', () => {
  updateJewelryCharts();
});

const brand = '#0f766f';
const brandRgb = '15, 118, 111';
const brandPalette = ['#0f766f', '#14a89c', '#5eccc3', '#0b5c56', '#99e0d9'];

const updateJewelryCharts = () => {
  if (window.revenueTrendChart) {
    revenueTrendChart.options.scales.x.ticks.color = coreui.Utils.getStyle('--cui-body-color');
    revenueTrendChart.options.scales.y.ticks.color = coreui.Utils.getStyle('--cui-body-color');
    revenueTrendChart.options.scales.x.grid.color = coreui.Utils.getStyle('--cui-border-color-translucent');
    revenueTrendChart.options.scales.y.grid.color = coreui.Utils.getStyle('--cui-border-color-translucent');
    revenueTrendChart.update();
  }
};

/* ---- Small sparkline cards (KPI tiles) ---- */
const sparkOptions = {
  plugins: { legend: { display: false } },
  maintainAspectRatio: false,
  scales: { x: { display: false }, y: { display: false, beginAtZero: true } },
  elements: { line: { borderWidth: 2, tension: 0.4 }, point: { radius: 0, hitRadius: 10, hoverRadius: 4 } }
};

const makeSpark = (id, data, color) => {
  const el = document.getElementById(id);
  if (!el) return null;
  return new Chart(el, {
    type: 'line',
    data: {
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      datasets: [{
        backgroundColor: 'transparent',
        borderColor: 'rgba(255,255,255,.55)',
        pointBackgroundColor: color,
        data
      }]
    },
    options: sparkOptions
  });
};

makeSpark('spark-revenue', [38, 42, 40, 55, 61, 58, 74], '#fff');
makeSpark('spark-orders', [12, 18, 15, 22, 19, 27, 31], '#fff');
makeSpark('spark-aov', [320, 310, 360, 340, 380, 410, 395], '#fff');
makeSpark('spark-customers', [4, 7, 6, 9, 12, 10, 15], '#fff');

/* ---- Main revenue trend chart ---- */
const revenueEl = document.getElementById('revenue-trend-chart');
if (revenueEl) {
  window.revenueTrendChart = new Chart(revenueEl, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      datasets: [
        {
          label: 'Revenue ($)',
          backgroundColor: `rgba(${brandRgb}, .12)`,
          borderColor: brand,
          borderWidth: 3,
          pointBackgroundColor: brand,
          data: [42000, 38000, 51000, 47000, 62000, 58000, 71000, 69000, 78000, 84000, 92000, 110000],
          fill: true
        },
        {
          label: 'Orders',
          backgroundColor: 'transparent',
          borderColor: coreui.Utils.getStyle('--cui-info'),
          borderWidth: 2,
          borderDash: [6, 4],
          pointBackgroundColor: coreui.Utils.getStyle('--cui-info'),
          data: [120, 110, 145, 132, 178, 165, 198, 190, 215, 232, 255, 301],
          yAxisID: 'y1'
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      plugins: { legend: { display: true, position: 'top' } },
      scales: {
        x: { grid: { display: false } },
        y: {
          beginAtZero: true,
          ticks: { callback: (v) => '$' + (v / 1000) + 'k' }
        },
        y1: {
          beginAtZero: true,
          position: 'right',
          grid: { drawOnChartArea: false }
        }
      }
    }
  });
}

/* ---- Sales by category doughnut ---- */
const catEl = document.getElementById('category-doughnut-chart');
if (catEl) {
  new Chart(catEl, {
    type: 'doughnut',
    data: {
      labels: ['Rings', 'Necklaces', 'Earrings', 'Bracelets', 'Watches'],
      datasets: [{
        backgroundColor: brandPalette,
        data: [38, 24, 16, 14, 8],
        borderWidth: 0
      }]
    },
    options: {
      maintainAspectRatio: false,
      cutout: '68%',
      plugins: { legend: { position: 'bottom' } }
    }
  });
}

/* ---- Metal mix bar ---- */
const metalEl = document.getElementById('metal-mix-chart');
if (metalEl) {
  new Chart(metalEl, {
    type: 'bar',
    data: {
      labels: ['Gold', 'Silver', 'Platinum', 'Rose Gold', 'White Gold'],
      datasets: [{
        label: 'Units sold',
        backgroundColor: brandPalette,
        data: [420, 280, 95, 160, 130],
        borderRadius: 6
      }]
    },
    options: {
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: { x: { grid: { display: false } }, y: { beginAtZero: true } }
    }
  });
}
