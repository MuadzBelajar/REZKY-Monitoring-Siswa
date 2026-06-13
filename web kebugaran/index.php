<?php require_once 'data.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Bina Jasmani</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;600;700;900&family=Barlow+Condensed:wght@700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <!-- Chart.js untuk grafik -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>

<!-- ═══════════════════════════════ HEADER ═══════════════════════════════ -->
<header class="site-header">
  <div class="glow-circle"></div>
  <div class="header-inner">
    <div class="logo-box">⚡</div>
    <div>
      <h1 class="site-title">BINA JASMANI</h1>
      <p class="site-sub">Sistem Evaluasi Jasmani Militer · Standar TNI</p>
    </div>
  </div>
</header>

<!-- ═══════════════════════════════ MAIN ════════════════════════════════ -->
<main class="container">

  <!-- ── PILIH SISWA ── -->
  <section class="section-label">Pilih Individu</section>
  <div class="student-grid">
    <?php foreach ($students as $s):
      $av  = getAvg($s['scores']);
      $st  = getStatus($av, $baseAvg);
      $isActive = ($s['id'] === $selectedId);
    ?>
    <a href="?id=<?= $s['id'] ?>"
       class="student-card <?= $isActive ? 'active' : '' ?>"
       style="--c:<?= $s['color'] ?>; <?= $isActive ? "border-color:{$s['color']}" : '' ?>">
      <div class="card-top">
        <span class="dot" style="background:<?= $s['color'] ?>"></span>
        <span class="card-name"><?= htmlspecialchars($s['nama']) ?></span>
      </div>
      <div class="card-bottom">
        <span class="card-pangkat"><?= htmlspecialchars($s['pangkat']) ?></span>
        <span class="badge" style="color:<?= $st['color'] ?>;background:<?= $st['bg'] ?>"><?= $av ?></span>
      </div>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- ── STAT CARDS ── -->
  <div class="stat-grid">
    <?php
    $stats = [
      ["Rata-rata Nilai",    $avg,          "Baseline: $baseAvg",                                    $status['color']],
      ["Status Keseluruhan", $status['label'], ($avg >= $baseAvg ? "+".($avg-$baseAvg)." di atas baseline" : ($avg-$baseAvg)." di bawah baseline"), $status['color']],
      ["Komponen Lulus",     "$lulusCount/".count($komponen), "Melampaui baseline",                  "#6BCB77"],
      ["Nilai Tertinggi",    $maxVal,        explode(' ', $maxKey)[0],                               "#FFD93D"],
    ];
    foreach ($stats as $c): ?>
    <div class="stat-card" style="border-top-color:<?= $c[3] ?>">
      <div class="stat-label"><?= $c[0] ?></div>
      <div class="stat-value" style="color:<?= $c[3] ?>"><?= $c[1] ?></div>
      <div class="stat-sub"><?= $c[2] ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- ── CHART AREA ── -->
  <div class="chart-card">
    <div class="chart-header">
      <div>
        <div class="chart-title">
          Visualisasi Hasil —
          <span style="color:<?= $selected['color'] ?>"><?= htmlspecialchars($selected['nama']) ?></span>
        </div>
        <div class="chart-sub">vs. Baseline Standarisasi Nilai</div>
      </div>
      <div class="chart-btns">
        <button class="chart-btn active" onclick="switchChart('radar',this)">⬡ Radar Chart</button>
        <button class="chart-btn" onclick="switchChart('bar',this)">▬ Bar Chart</button>
        <button class="chart-btn" onclick="switchChart('compare',this)">⊞ Semua Siswa</button>
      </div>
    </div>
    <div class="chart-body">
      <canvas id="mainChart"></canvas>
    </div>
  </div>

  <!-- ── RINCIAN KOMPONEN ── -->
  <div class="detail-card">
    <div class="detail-header">
      <div class="chart-title">
        Rincian Komponen —
        <span style="color:<?= $selected['color'] ?>"><?= htmlspecialchars($selected['nama']) ?></span>
      </div>
      <div class="chart-sub"><?= htmlspecialchars($selected['pangkat']) ?></div>
    </div>
    <div class="detail-list">
      <?php foreach ($komponen as $k):
        $score = $selected['scores'][$k];
        $base  = $baseline[$k];
        $st    = getStatus($score, $base);
        $diff  = $score - $base;
        $diffStr = ($diff >= 0 ? '+' : '') . $diff;
        $diffColor = $diff >= 0 ? '#6BCB77' : '#FF6B6B';
      ?>
      <div class="detail-row">
        <div class="detail-top">
          <span class="detail-name"><?= htmlspecialchars($k) ?></span>
          <div class="detail-right">
            <span class="detail-baseline">Baseline: <?= $base ?></span>
            <span class="detail-score" style="color:<?= $st['color'] ?>"><?= $score ?></span>
            <span class="badge" style="color:<?= $st['color'] ?>;background:<?= $st['bg'] ?>;min-width:80px;text-align:center">
              <?= $st['label'] ?>
            </span>
            <span class="detail-diff" style="color:<?= $diffColor ?>"><?= $diffStr ?></span>
          </div>
        </div>
        <div class="progress-wrap">
          <div class="progress-bar"
               style="width:<?= $score ?>%;background:linear-gradient(90deg,<?= $st['color'] ?>88,<?= $st['color'] ?>)">
          </div>
          <div class="progress-marker" style="left:<?= $base ?>%"></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

</main>

<!-- ═══════════════════════════════ SCRIPT ══════════════════════════════ -->
<script>
// Data dari PHP ke JavaScript
const KOMPONEN  = <?= json_encode($komponen) ?>;
const BASELINE  = <?= json_encode(array_values($baseline)) ?>;
const SELECTED  = <?= json_encode(array_values($selected['scores'])) ?>;
const SEL_COLOR = "<?= $selected['color'] ?>";
const SEL_NAMA  = "<?= htmlspecialchars($selected['nama']) ?>";
const ALL_STUDENTS = <?= json_encode(array_map(fn($s) => [
  'nama'   => $s['nama'],
  'color'  => $s['color'],
  'scores' => array_values($s['scores']),
], $students)) ?>;

let currentChart = null;
let currentMode  = 'radar';

const DARK  = '#0f172a';
const GRID  = '#1e293b';
const TICK  = '#94a3b8';

function destroyChart() {
  if (currentChart) { currentChart.destroy(); currentChart = null; }
}

function buildRadar() {
  destroyChart();
  const ctx = document.getElementById('mainChart').getContext('2d');
  currentChart = new Chart(ctx, {
    type: 'radar',
    data: {
      labels: KOMPONEN,
      datasets: [
        {
          label: 'Baseline',
          data: BASELINE,
          borderColor: '#475569',
          backgroundColor: 'rgba(71,85,105,0.2)',
          borderDash: [5,3],
          borderWidth: 2,
          pointRadius: 3,
        },
        {
          label: SEL_NAMA,
          data: SELECTED,
          borderColor: SEL_COLOR,
          backgroundColor: SEL_COLOR + '33',
          borderWidth: 2.5,
          pointBackgroundColor: SEL_COLOR,
          pointRadius: 4,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      scales: {
        r: {
          min: 0, max: 100,
          grid: { color: GRID },
          angleLines: { color: GRID },
          pointLabels: { color: TICK, font: { size: 12, family: 'Barlow' } },
          ticks: { color: '#475569', backdropColor: 'transparent', stepSize: 20 }
        }
      },
      plugins: {
        legend: { labels: { color: TICK, font: { family: 'Barlow' } } },
        tooltip: { backgroundColor: DARK, borderColor: GRID, borderWidth: 1,
                   titleColor: '#94a3b8', bodyColor: '#f1f5f9' }
      }
    }
  });
}

function buildBar() {
  destroyChart();
  const ctx = document.getElementById('mainChart').getContext('2d');
  currentChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: KOMPONEN,
      datasets: [
        {
          label: 'Baseline',
          data: BASELINE,
          backgroundColor: '#334155',
          borderRadius: 4,
        },
        {
          label: SEL_NAMA,
          data: SELECTED,
          backgroundColor: SEL_COLOR,
          borderRadius: 4,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      scales: {
        x: { ticks: { color: TICK }, grid: { color: 'transparent' }, border: { color: GRID } },
        y: { min: 0, max: 100, ticks: { color: '#475569' }, grid: { color: GRID }, border: { color: 'transparent' } }
      },
      plugins: {
        legend: { labels: { color: TICK, font: { family: 'Barlow' } } },
        tooltip: { backgroundColor: DARK, borderColor: GRID, borderWidth: 1,
                   titleColor: '#94a3b8', bodyColor: '#f1f5f9' }
      }
    }
  });
}

function buildCompare() {
  destroyChart();
  const ctx = document.getElementById('mainChart').getContext('2d');
  const datasets = [
    { label: 'Baseline', data: BASELINE, backgroundColor: '#334155', borderRadius: 3 },
    ...ALL_STUDENTS.map(s => ({
      label: s.nama,
      data: s.scores,
      backgroundColor: s.color,
      borderRadius: 3,
    }))
  ];
  currentChart = new Chart(ctx, {
    type: 'bar',
    data: { labels: KOMPONEN, datasets },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      scales: {
        x: { ticks: { color: TICK }, grid: { color: 'transparent' }, border: { color: GRID } },
        y: { min: 0, max: 100, ticks: { color: '#475569' }, grid: { color: GRID }, border: { color: 'transparent' } }
      },
      plugins: {
        legend: { labels: { color: TICK, font: { size: 11, family: 'Barlow' } } },
        tooltip: { backgroundColor: DARK, borderColor: GRID, borderWidth: 1,
                   titleColor: '#94a3b8', bodyColor: '#f1f5f9' }
      }
    }
  });
}

function switchChart(mode, btn) {
  currentMode = mode;
  document.querySelectorAll('.chart-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  if (mode === 'radar')   buildRadar();
  if (mode === 'bar')     buildBar();
  if (mode === 'compare') buildCompare();
}

// Inisialisasi saat halaman pertama load
buildRadar();
</script>

</body>
</html>
