<?php
// ============================================================
//  DATA BINA JASMANI — Edit bagian ini sesuai data nyata
// ============================================================

$baseline = [
    "Lari 12 Menit" => 70,
    "Pull-Up"       => 60,
    "Sit-Up"        => 65,
    "Push-Up"       => 65,
    "Shuttle Run"   => 70,
    "Renang 50m"    => 60,
];

$students = [
    [
        "id"     => 1,
        "nama"   => "Ridhotullah",
        "pangkat"=> "Prada",
        "color"  => "#00C9A7",
        "scores" => [
            "Lari 12 Menit" => 82,
            "Pull-Up"       => 75,
            "Sit-Up"        => 88,
            "Push-Up"       => 79,
            "Shuttle Run"   => 85,
            "Renang 50m"    => 72,
        ],
    ],
    [
        "id"     => 2,
        "nama"   => "Akhtarsyah",
        "pangkat"=> "Prada",
        "color"  => "#FF6B6B",
        "scores" => [
            "Lari 12 Menit" => 65,
            "Pull-Up"       => 55,
            "Sit-Up"        => 70,
            "Push-Up"       => 58,
            "Shuttle Run"   => 62,
            "Renang 50m"    => 50,
        ],
    ],
    [
        "id"     => 3,
        "nama"   => "Ammaruddin",
        "pangkat"=> "Kopral",
        "color"  => "#FFD93D",
        "scores" => [
            "Lari 12 Menit" => 90,
            "Pull-Up"       => 88,
            "Sit-Up"        => 92,
            "Push-Up"       => 85,
            "Shuttle Run"   => 91,
            "Renang 50m"    => 80,
        ],
    ],
    [
        "id"     => 4,
        "nama"   => "Azzamsyah",
        "pangkat"=> "Prada",
        "color"  => "#6BCB77",
        "scores" => [
            "Lari 12 Menit" => 72,
            "Pull-Up"       => 68,
            "Sit-Up"        => 60,
            "Push-Up"       => 74,
            "Shuttle Run"   => 78,
            "Renang 50m"    => 65,
        ],
    ],
    [
        "id"     => 5,
        "nama"   => "Ridhonofrah",
        "pangkat"=> "Sersan",
        "color"  => "#FF9F43",
        "scores" => [
            "Lari 12 Menit" => 55,
            "Pull-Up"       => 48,
            "Sit-Up"        => 58,
            "Push-Up"       => 52,
            "Shuttle Run"   => 60,
            "Renang 50m"    => 45,
        ],
    ],
];

// ============================================================
//  FUNGSI PEMBANTU
// ============================================================

function getAvg(array $scores): int {
    return (int) round(array_sum($scores) / count($scores));
}

function getBaseAvg(array $baseline): int {
    return (int) round(array_sum($baseline) / count($baseline));
}

function getStatus(int $score, int $base): array {
    if ($score >= $base + 15) return ["label" => "Sangat Baik", "color" => "#00C9A7", "bg" => "rgba(0,201,167,0.15)"];
    if ($score >= $base)      return ["label" => "Memenuhi",    "color" => "#6BCB77", "bg" => "rgba(107,203,119,0.15)"];
    if ($score >= $base - 10) return ["label" => "Hampir",      "color" => "#FFD93D", "bg" => "rgba(255,217,61,0.15)"];
    return                           ["label" => "Kurang",       "color" => "#FF6B6B", "bg" => "rgba(255,107,107,0.15)"];
}

// Ambil siswa yang dipilih (default: pertama)
$selectedId = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$selected   = $students[0];
foreach ($students as $s) {
    if ($s['id'] === $selectedId) { $selected = $s; break; }
}

$baseAvg = getBaseAvg($baseline);
$avg     = getAvg($selected['scores']);
$status  = getStatus($avg, $baseAvg);
$komponen = array_keys($baseline);
$lulusCount = 0;
foreach ($komponen as $k) {
    if ($selected['scores'][$k] >= $baseline[$k]) $lulusCount++;
}
$maxVal = max($selected['scores']);
$maxKey = array_search($maxVal, $selected['scores']);
?>
