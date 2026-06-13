# ⚡ Aplikasi Bina Jasmani — Dashboard Evaluasi Jasmani Militer

> Aplikasi web berbasis PHP & HTML/CSS murni untuk menampilkan hasil evaluasi Bina Jasmani (kebugaran jasmani militer) setiap individu secara visual. Aplikasi ini menyajikan **Radar Chart** dan **Bar Chart** yang membandingkan pencapaian siswa terhadap garis dasar (baseline) standarisasi nilai TNI.

**Tujuan Pembelajaran:**
- Memahami cara menyajikan data evaluasi secara visual menggunakan Chart.js
- Menerapkan PHP sebagai backend sederhana untuk pengolahan & penyajian data
- Mengimplementasikan desain antarmuka yang responsif & informatif
- Melatih pembuatan dashboard perbandingan data berbasis web

---

## 🛠️ Tech Stack

| Lapisan | Teknologi | Versi |
|---------|-----------|-------|
| Frontend | HTML5, CSS3 | Native |
| Charting | Chart.js | 4.4.0 |
| Backend | PHP | 8.1+ |
| Server Lokal | XAMPP / Laragon | Terbaru |
| Font | Google Fonts (Barlow) | CDN |
| Database | *(Opsional — data saat ini hardcoded di PHP)* | — |

---

## ⚙️ Langkah Instalasi & Menjalankan

### 1. Clone atau Download Project
```bash
git clone https://github.com/username/bina-jasmani.git
```
Atau download ZIP lalu ekstrak.

### 2. Pindahkan ke Folder Server Lokal
```
# XAMPP
C:\xampp\htdocs\bina-jasmani\

# Laragon
C:\laragon\www\bina-jasmani\
```

### 3. Jalankan Apache
Buka **XAMPP Control Panel** → klik **Start** pada Apache.

> ⚠️ Project ini tidak memerlukan database — data siswa langsung dikonfigurasi di `data.php`.

### 4. Buka di Browser
```
http://localhost/bina-jasmani/
```

### 5. Mengganti Data Siswa
Edit bagian `$students` dan `$baseline` di file `data.php`:
```php
$baseline = [
    "Lari 12 Menit" => 70,
    "Pull-Up"       => 60,
    // tambah/ubah komponen di sini
];

$students = [
    [
        "nama"    => "Ridhotullah",
        "pangkat" => "Prada",
        "color"   => "#00C9A7",
        "scores"  => ["Lari 12 Menit" => 82, "Pull-Up" => 75, ...]
    ],
    // tambah siswa lain di sini
];
```

---

## 📂 Struktur Direktori

```
bina-jasmani/
├── index.php        # Halaman utama — HTML + PHP + integrasi Chart.js
├── data.php         # Data siswa, baseline, dan fungsi pembantu PHP
├── style.css        # Stylesheet — desain dark mode militer
└── README.md
```

### Alur Data (Request → Response)
```
Browser (Request: ?id=2)
    ↓
index.php
    ↓
require data.php          ← Load data siswa & baseline
    ↓
PHP memproses:
  - Filter siswa berdasarkan ?id
  - Hitung rata-rata & status
  - Hitung selisih vs baseline
    ↓
Data di-encode ke JSON    ← php json_encode() → JavaScript
    ↓
Chart.js render grafik    ← Radar / Bar / Compare
    ↓
Browser menampilkan Dashboard
```

---

## ✅ Pemetaan Rubrik Penilaian

| Kriteria | Implementasi | Status |
|----------|-------------|--------|
| HTML Semantik | `<header>`, `<main>`, `<section>`, `<canvas>` digunakan sesuai fungsi | ✅ |
| Responsivitas | CSS Grid + Flexbox + Media Query untuk mobile & desktop | ✅ |
| Visualisasi Data | Radar Chart & Bar Chart via Chart.js dengan baseline overlay | ✅ |
| Interaktivitas | Pilih siswa, ganti tipe chart, mode perbandingan semua siswa | ✅ |
| Integrasi PHP–JS | Data PHP dikirim ke JS via `json_encode()` tanpa AJAX | ✅ |
| Desain UI | Dark mode konsisten, color-coded per status, progress bar | ✅ |

---

## 🔄 Mengapa CRUD Penting dalam Aplikasi Web?

CRUD (*Create, Read, Update, Delete*) adalah **operasi dasar** yang memungkinkan aplikasi web mengelola data secara dinamis dan interaktif.

Dalam konteks **Aplikasi Bina Jasmani** ini:

- **Create** → Input nilai hasil latihan baru untuk setiap siswa
- **Read** → Menampilkan hasil evaluasi dalam bentuk chart & tabel perbandingan
- **Update** → Memperbarui nilai siswa setelah sesi latihan berikutnya
- **Delete** → Menghapus data siswa yang sudah tidak aktif

Tanpa CRUD, aplikasi ini hanya bisa menampilkan data statis. Dengan CRUD, instruktur bisa **mengelola data real-time** langsung dari browser — menjadikannya alat evaluasi yang hidup dan terus berkembang, bukan sekadar laporan satu kali.

---

## 📊 Fitur Utama

- **⬡ Radar Chart** — Profil jasmani individu vs baseline dalam bentuk jaring laba-laba
- **▬ Bar Chart** — Perbandingan nilai per komponen vs baseline secara batang
- **⊞ Semua Siswa** — Bar chart komparatif seluruh individu sekaligus
- **Status Otomatis** — Sangat Baik / Memenuhi / Hampir / Kurang berdasarkan selisih vs baseline
- **Progress Bar** — Visual progress tiap komponen dengan penanda posisi baseline

---

## 🐛 Known Issues & Rencana Pengembangan

### Known Issues
- [ ] Data siswa masih hardcoded di `data.php`, belum tersambung database
- [ ] Belum ada fitur input/edit nilai langsung dari UI
- [ ] Belum ada fitur cetak laporan PDF

### Rencana Fase Selanjutnya
- [ ] Sambungkan ke MySQL — form input nilai dari antarmuka web
- [ ] Tambah fitur export PDF laporan per siswa
- [ ] Tambah grafik tren perkembangan nilai dari waktu ke waktu
- [ ] Tambah autentikasi login untuk instruktur

---

## 📸 Screenshot Aplikasi

> *(Tambahkan screenshot di sini setelah project berjalan)*

| Tampilan | Screenshot |
|----------|-----------|
| Dashboard Utama + Radar Chart | `screenshot-radar.png` |
| Bar Chart Individu vs Baseline | `screenshot-bar.png` |
| Perbandingan Semua Siswa | `screenshot-compare.png` |
| Tabel Rincian Komponen | `screenshot-detail.png` |
| Tampilan Mobile | `screenshot-mobile.png` |

---

## 👤 Identitas

- Nama: Muh rezky ridhotullah
- Kelas : XI A
- Jurusan smk : Pemrograman Web / Aplikasi Olahraga
- Guru : Rajie Al Qadri Anwar
