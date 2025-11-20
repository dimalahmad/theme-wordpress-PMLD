# INVIRO WordPress Theme

Theme WordPress untuk website INVIRO - perusahaan peralatan depot air minum.

## Quick Start

```bash
# Start Docker
docker compose up -d

# Access
WordPress: http://localhost:8080
Admin: http://localhost:8080/wp-admin
```

## Fitur Utama

- ✅ Responsive design (mobile, tablet, desktop)
- ✅ Custom Post Types: Produk, Testimoni, Cabang, Proyek Pelanggan, Spare Parts, Artikel, Unduhan
- ✅ WordPress Customizer - edit semua konten tanpa coding
- ✅ Modular CSS - loading cepat, hanya CSS yang diperlukan
- ✅ Active navbar state - menu aktif ter-highlight otomatis
- ✅ Multiple page templates: Beranda, Profil, Pelanggan, Paket Usaha, Spare Parts, Artikel, Unduhan
- ✅ AJAX download tracking - auto-increment counter
- ✅ Regional filtering - filter proyek by daerah
- ✅ WhatsApp integration - order buttons
- ✅ Docker setup - install sekali jalan

## Halaman & Template

| Halaman | Template | CSS File |
|---------|----------|----------|
| Beranda | `front-page.php` | `front-page.css` |
| Profil | `page-profil.php` | `profil.css` |
| Pelanggan | `page-pelanggan.php` | `pelanggan.css` |
| Paket Usaha | `page-paket-usaha.php` | `paket-usaha.css` |
| Spare Parts | `page-spareparts.php` | Inline CSS |
| Artikel | `page-artikel.php` + `single-artikel.php` | Inline CSS |
| Unduhan | `page-unduhan.php` | Inline CSS |
| Single Post | `single.php` | `single.css` |
| Archive | `archive.php` | `archive.css` |

## Cara Menggunakan Setiap Halaman

### 🏠 Beranda (Front Page)

**Setup:**
1. Halaman ini otomatis tampil sebagai homepage WordPress
2. Sudah jadi, tidak perlu buat halaman baru
3. Menu "Beranda" otomatis ter-highlight saat di homepage

**Edit Konten:**
- **Appearance → Customize → Hero Section:** Edit judul, deskripsi, button, background image
- **Appearance → Customize → Statistik:** Edit 3 angka statistik (500+ Pelanggan, dll)
- **Appearance → Customize → About Section:** Edit tentang perusahaan
- **Appearance → Customize → Products Section:** Edit judul section produk
- **Appearance → Customize → Testimonials Section:** Edit judul section testimoni
- **Appearance → Customize → Contact Section:** Edit phone, email, alamat
- **Appearance → Customize → Footer:** Edit deskripsi footer & social media links

**Sections di Beranda:**
1. Hero - Banner utama dengan CTA button
2. Stats - 3 statistik (pelanggan, tahun, dukungan)
3. About - Tentang perusahaan
4. Products - Grid produk (otomatis dari CPT Produk)
5. Testimonials - Slider testimoni (otomatis dari CPT Testimoni)
6. Contact - Form kontak dengan info
7. Footer - Info perusahaan & cabang

---

### 👤 Halaman Profil

**Setup:**
1. **Pages → Add New**
2. **Judul:** "Profil"
3. **Permalink:** Otomatis jadi `/profil` (jangan diubah)
4. **Sidebar Kanan → Template:** Pilih **"Profil"**
5. **Publish**
6. **Appearance → Menus:** Tambahkan "Profil" ke menu utama
7. **Save Menu**

**Edit Konten:**
- **Appearance → Customize → Halaman Profil**

**Settings Available:**
- **Hero:** Judul + Subtitle
- **Sejarah:** Judul, konten (textarea), gambar
- **Visi:** Judul + konten
- **Misi:** Judul + konten
- **Nilai-nilai (4 items):** 
  - Nilai 1-4: Judul + Deskripsi
- **Tim (6 members):**
  - Tim 1-6: Nama, Posisi, Foto
- **Sertifikasi (4 items):**
  - Sertifikasi 1-4: Judul, Gambar
- **CTA:** Judul, Subtitle, Button Text, Link

**Sections di Profil:**
1. Hero - Header dengan gradient
2. Sejarah Perusahaan - Timeline dengan gambar
3. Visi & Misi - 2 card dengan icon
4. Nilai-nilai - 4 value cards dengan numbering
5. Tim Kami - Grid 6 anggota tim
6. Sertifikasi & Penghargaan - 4 certificate cards
7. CTA - Call to action untuk kontak

**Tips:**
- **Upload gambar sejarah:** Min 800x600px
- **Foto tim:** Ukuran sama semua (300x300px recommended)
- **Logo sertifikat:** Format portrait (200x250px)

---

### 👥 Halaman Pelanggan

**Setup:**
1. **Pages → Add New**
2. **Judul:** "Pelanggan"
3. **Permalink:** Otomatis jadi `/pelanggan` (jangan diubah)
4. **Sidebar Kanan → Template:** Pilih **"Pelanggan"**
5. **Publish**
6. **Appearance → Menus:** Tambahkan "Pelanggan" ke menu utama
7. **Save Menu**

**Edit Konten:**
- **Appearance → Customize → Halaman Pelanggan**

**Settings Available:**
- **Hero:** Judul + Subtitle
- **Company Logos (12 items):**
  - Logo 1-12: Nama Perusahaan + Gambar Logo
- **Logos Section:** Judul Utama + Subtitle
- **CTA:** Judul, Subtitle, Button Text, Link

**Tambah Proyek Pelanggan:**
1. **Proyek Pelanggan → Tambah Proyek**
2. **Judul:** Nama proyek lengkap (contoh: "PEMASANGAN DEPOT AIR ISI ULANG GIWANGAN UMBULHARJO YOGYAKARTA")
3. **Content Editor:** Deskripsi lengkap proyek:
   - Detail pemasangan
   - Spesifikasi produk yang dipakai
   - Lokasi alamat lengkap
   - Testimoni singkat dari klien (optional)
4. **Excerpt:** Ringkasan singkat (1-2 kalimat untuk card)
5. **Featured Image:** Upload foto proyek (WAJIB)
   - Foto hasil pemasangan
   - Foto lokasi/depot
   - Ukuran: 800x600px recommended
6. **Sidebar Kanan → Daerah:** Pilih region (Sumatra, Jawa, Kalimantan, Sulawesi, Maluku, Nusa Tenggara, Papua) - **WAJIB untuk filter berfungsi**
7. **Sidebar Kanan → Nama Klien:** Isi nama pemilik/PIC (contoh: "Oleh Agung INVIRO")
8. **Sidebar Kanan → Tanggal Proyek:** Pilih tanggal pemasangan/selesai
9. **Publish**
10. Otomatis tampil di Halaman Pelanggan

**Sections di Pelanggan:**
1. Hero - Header dengan gradient
2. Filter Tabs - Filter by region (Sumatra, Jawa, Kalimantan, Sulawesi, Maluku, Nusa Tenggara, Papua)
   - Klik tab untuk filter proyek berdasarkan daerah
   - JavaScript otomatis menyembunyikan/menampilkan card yang sesuai
3. Projects Grid - Grid 3 kolom auto dari CPT Proyek Pelanggan
   - Tampilan: Foto, Judul, Nama Klien, Tanggal, Excerpt, Link "Baca Selengkapnya"
4. Company Logos - 12 logo perusahaan partner
5. CTA - Call to action

**Tips:**
- **Judul proyek:** Deskriptif dan SEO-friendly (nama + lokasi)
- **Foto proyek:** Ambil angle terbaik, pencahayaan cukup
- **Nama klien:** Cantumkan "Oleh [Nama]" untuk kredibilitas
- **Daerah:** WAJIB pilih agar filter bekerja
- **Logo perusahaan:** Background putih/transparan, 200x100px

---

### 📦 Halaman Paket Usaha

**Setup:**
1. **Pages → Add New**
2. **Judul:** "Paket Usaha"
3. **Permalink:** Otomatis jadi `/paket-usaha` (jangan diubah)
4. **Sidebar Kanan → Template:** Pilih **"Paket Usaha"**
5. **Publish**
6. **Appearance → Menus:** Tambahkan "Paket Usaha" ke menu utama
7. **Save Menu**

**Edit Konten:**
- **Appearance → Customize → Halaman Paket Usaha**

**Settings Available:**
- **Hero:** Judul + Subtitle (textarea)
- **Features (4 items):**
  - Feature 1-4: Icon (emoji/text), Judul, Deskripsi
- **CTA:** Judul, Subtitle, Button Text, Link

**Tambah Paket/Produk (untuk Grid):**
1. **Produk → Add New**
2. **Judul:** Nama paket (contoh: "DAMIU Paket A")
3. **Content:** Deskripsi lengkap paket
4. **Excerpt:** Deskripsi singkat (tampil di card)
5. **Featured Image:** Upload gambar paket (WAJIB)
6. **Harga Produk:** Isi di meta box (contoh: Rp 15.900.000)
7. **Harga Asli (Optional):** Untuk tampilkan diskon
8. **Publish**
9. Otomatis tampil di grid paket usaha

**Sections di Paket Usaha:**
1. Hero - Header dengan gradient
2. Search Bar - Search paket (functional)
3. Packages Grid - Grid 4 kolom auto dari CPT Produk
4. Features - 4 keunggulan paket INVIRO
5. CTA - Call to action konsultasi

**Tips:**
- **Icon features:** Gunakan emoji (✓, 🚀, 💎, ⚡) atau teks
- **Gambar paket:** Ukuran sama semua (400x400px recommended)
- **Harga:** Format: Rp 15.900.000 (dengan titik pemisah ribuan)
- **Wishlist button:** Untuk save favorite (bisa dikembangkan nanti)

---

### 📝 Cara Umum Menambah Halaman Lain

1. **Pages → Add New**
2. **Isi judul** (misal: "Tentang Kami", "Kontak", dll)
3. **Sidebar kanan → Template:** Pilih template yang diinginkan
   - **Desain Beranda** - untuk halaman dengan layout seperti homepage
   - **Profil** - untuk halaman profil perusahaan
   - **Pelanggan** - untuk halaman customer/partner
   - **Paket Usaha** - untuk halaman produk/paket
   - **Default Template** - untuk halaman biasa
4. **Publish**
5. **Appearance → Menus:** Tambahkan ke menu
6. **Appearance → Customize:** Edit konten halaman (jika ada customizer)

## Custom Post Types - Cara Lengkap

Theme INVIRO memiliki 4 Custom Post Types:
1. **Produk** - Untuk produk/paket yang dijual
2. **Testimoni** - Untuk review pelanggan
3. **Cabang** - Untuk lokasi kantor/cabang INVIRO
4. **Proyek Pelanggan** - Untuk showcase project/pemasangan yang sudah dikerjakan

---

### 📦 1. Produk (`/produk`)

**Untuk apa?** Menampilkan produk/paket di Beranda, Archive Produk, dan Halaman Paket Usaha

**Cara Menambah Produk:**
1. **Produk → Add New**
2. **Judul:** Nama produk (contoh: "DAMIU Paket A - Rp. 15.900.000")
3. **Content Editor:** 
   - Tulis deskripsi lengkap produk
   - Bisa pakai formatting (bold, list, dll)
   - Tambahkan spesifikasi detail
4. **Excerpt:** Deskripsi singkat (tampil di card)
5. **Sidebar Kanan → Featured Image:**
   - Klik "Set featured image"
   - Upload/pilih gambar produk
   - Ukuran recommended: 400x400px
   - Format: JPG/PNG
   - **WAJIB ada gambar!**
6. **Scroll ke bawah → Meta Box "Harga Produk":**
   - Isi format: Rp. 15.900.000
   - Atau: Rp 15.900.000
7. **Meta Box "Harga Asli (Optional)":**
   - Isi jika ada diskon
   - Contoh: Rp. 18.000.000
   - Akan tampil coret di card
8. **Publish**

**Tampil dimana?**
- ✅ Beranda (section Products) - 6 produk terbaru
- ✅ Archive `/produk` - semua produk
- ✅ Halaman Paket Usaha - semua produk dalam grid 4 kolom

**Tips:**
- Foto produk dengan background putih/bersih
- Judul maksimal 50 karakter agar tidak terpotong
- Excerpt maksimal 100 karakter
- Gunakan kategori produk jika perlu (bisa ditambahkan nanti)

---

### ⭐ 2. Testimoni (`/testimoni`)

**Untuk apa?** Menampilkan review customer di Beranda

**Cara Menambah Testimoni:**
1. **Testimoni → Add New**
2. **Judul:** Nama customer (contoh: "Budi Santoso")
3. **Content Editor:** 
   - Tulis testimoni/review dari customer
   - Kutip langsung apa kata mereka
   - Jangan terlalu panjang (2-4 kalimat cukup)
4. **Sidebar Kanan → Featured Image:**
   - Upload foto customer
   - Ukuran recommended: 100x100px (square/bulat)
   - Format: JPG/PNG
   - **WAJIB ada foto!**
5. **Scroll ke bawah → Meta Box "Rating":**
   - Pilih rating: 1-5 Bintang
   - Biasanya 4-5 bintang
6. **Meta Box "Tanggal":**
   - Pilih tanggal testimoni
   - Default: hari ini
7. **Publish**

**Tampil dimana?**
- ✅ Beranda (section Testimonials) - 3 testimoni terbaru
- ✅ Bisa ditampilkan di halaman lain (custom query)

**Tips:**
- Foto customer asli lebih kredibel
- Testimoni autentik dan spesifik
- Rating 4-5 bintang untuk positive review
- Bisa tambahkan nama perusahaan di excerpt

---

### 📍 3. Cabang (`/cabang`)

**Untuk apa?** Menampilkan lokasi cabang di Footer dan Halaman Pelanggan

**Cara Menambah Cabang:**
1. **Cabang → Add New**
2. **Judul:** Nama cabang (contoh: "INVIRO Jakarta Pusat")
3. **Content Editor:** 
   - Tulis deskripsi cabang
   - Jam operasional
   - Fasilitas yang ada
4. **Sidebar Kanan → Featured Image:**
   - Upload foto lokasi/kantor cabang
   - Ukuran recommended: 800x600px
   - Format: JPG/PNG
   - **WAJIB ada foto!**
5. **Sidebar Kanan → Daerah (Region Taxonomy):**
   - **PENTING:** Pilih daerah cabang dari checkbox
   - Pilihan: Sumatra, Jawa, Kalimantan, Sulawesi, Maluku, Nusa Tenggara, Papua
   - **WAJIB pilih minimal 1 daerah!**
   - Digunakan untuk filter di Halaman Pelanggan
6. **Scroll ke bawah → Meta Box "Alamat Lengkap":**
   - Isi alamat lengkap dengan detail
   - Contoh: "Jl. Sudirman No. 123, Menteng, Jakarta Pusat 10310"
7. **Meta Box "Nomor Telepon":**
   - Format: +62 21 1234 5678
   - Atau: (021) 1234-5678
8. **Meta Box "URL Google Maps":**
   - Buka Google Maps
   - Cari lokasi cabang
   - Klik "Share" → "Embed a map"
   - Copy URL-nya (yang panjang)
   - Paste di sini
9. **Publish**

**Tampil dimana?**
- ✅ Footer - list semua cabang
- ✅ Bisa ditampilkan di widget
- ✅ Kontak informasi di halaman lain

**Tips:**
- Foto eksterior kantor/toko
- Alamat lengkap dengan kode pos
- Google Maps URL untuk navigasi
- Bisa tambahkan kontak person WhatsApp

---

### 🏢 4. Proyek Pelanggan (`/proyek-pelanggan`)

**Untuk apa?** Menampilkan showcase project/pemasangan yang sudah dikerjakan INVIRO di Halaman Pelanggan

**Cara Menambah Proyek:**
1. **Proyek Pelanggan → Tambah Proyek**
2. **Judul:** Nama proyek lengkap dan deskriptif
   - Contoh: "PEMASANGAN DEPOT AIR ISI ULANG GIWANGAN UMBULHARJO YOGYAKARTA"
   - Format: PEMASANGAN [JENIS] [LOKASI LENGKAP]
3. **Content Editor:** 
   - Deskripsi lengkap proyek
   - Detail pemasangan (produk yang dipakai, tanggal mulai-selesai)
   - Lokasi alamat lengkap
   - Spesifikasi sistem
   - Testimoni singkat dari klien (optional)
   - Bisa pakai formatting (bold, list, dll)
4. **Excerpt (ringkasan):**
   - Ringkasan singkat 1-2 kalimat
   - Tampil di card Halaman Pelanggan
   - Contoh: "Pemasangan Depot Air Minum Isi Ulang kompleks Bambang lipuro Bantul. Nama Konsumen/pembeli: Ibu Ruth. Nama Depot Air Minum: Jaya Water. Lokasi/Alamat Pemasangan..."
5. **Sidebar Kanan → Featured Image:**
   - Upload foto hasil pemasangan/proyek
   - Ukuran recommended: 800x600px
   - Format: JPG/PNG
   - Foto profesional, pencahayaan cukup
   - **WAJIB ada foto!**
6. **Sidebar Kanan → Daerah (Region Taxonomy):**
   - **WAJIB:** Pilih daerah lokasi proyek dari checkbox
   - Pilihan: Sumatra, Jawa, Kalimantan, Sulawesi, Maluku, Nusa Tenggara, Papua
   - Digunakan untuk filter di Halaman Pelanggan
7. **Sidebar Kanan → Nama Klien:**
   - Isi nama pemilik/PIC proyek
   - Contoh: "Oleh Agung INVIRO" atau "Ibu Ruth"
   - Tampil dengan icon 👤 di card
8. **Sidebar Kanan → Tanggal Proyek:**
   - Pilih tanggal pemasangan atau tanggal selesai proyek
   - Format otomatis: DD/MM/YYYY
   - Tampil dengan icon 📅 di card
9. **Publish**

**Tampil dimana?**
- ✅ Halaman Pelanggan - grid 3 kolom dengan filter region
- ✅ Filter tabs: Klik region untuk filter proyek
- ✅ Single page dengan detail lengkap

**Tips:**
- **Judul SEO-friendly:** Nama jelas + lokasi lengkap
- **Foto berkualitas:** Angle terbaik, pencahayaan bagus
- **Detail lengkap:** Semakin detail semakin kredibel
- **Nama klien:** Tambah kredibilitas dan personal touch
- **Region WAJIB:** Agar filter di halaman berfungsi
- **Update rutin:** Tambahkan proyek baru secara berkala untuk showcase portofolio

---

## Featured Image - PENTING!

**Lokasi Featured Image:**
- **Sidebar kanan** di halaman edit post
- Scroll ke bawah cari box "Featured Image"
- Klik "Set featured image"

**Jika tidak muncul:**
1. Klik **Screen Options** (pojok kanan atas)
2. Centang **"Featured Image"**
3. Box akan muncul di sidebar

**Tips Upload:**
- Max size: 2MB
- Format: JPG, PNG, WebP
- Compress dulu sebelum upload (tinypng.com)
- Rename file sebelum upload (contoh: `damiu-paket-a.jpg`)

**Ukuran Recommended:**
- Produk: 400x400px (square)
- Testimoni: 100x100px (square/circle)
- Cabang: 800x600px (landscape)
- Hero background: 1920x1080px

---

## WordPress Customizer - Panduan Lengkap

**Akses:** `Appearance → Customize`

### Sections Available:

#### 1. **Site Identity**
- Site Title & Tagline
- Logo perusahaan
- Site Icon (favicon)

#### 2. **Hero Section** (Beranda)
- Judul Hero
- Deskripsi Hero
- Teks Tombol
- Link Tombol
- Gambar Background Hero (1920x1080px)

#### 3. **About Section** (Beranda)
- Judul About
- Deskripsi About (support HTML)

#### 4. **Products Section** (Beranda)
- Judul Produk
- Subtitle Produk

#### 5. **Testimonials Section** (Beranda)
- Judul Testimoni
- Subtitle Testimoni

#### 6. **Contact Section** (Beranda)
- Judul Kontak
- Nomor Telepon
- Email
- Alamat

#### 7. **Pengaturan Peta**
- URL Google Maps Embed

#### 8. **Statistik** (Beranda)
- Stat 1: Angka + Label (contoh: 500+ / Pelanggan Puas)
- Stat 2: Angka + Label (contoh: 15 / Tahun Pengalaman)
- Stat 3: Angka + Label (contoh: 24/7 / Dukungan Pelanggan)

#### 9. **Footer**
- Deskripsi Footer
- Facebook URL
- Instagram URL
- Twitter URL
- Nomor WhatsApp (format: 621234567890)

#### 10. **Halaman Profil**
- Hero: Judul + Subtitle
- Sejarah: Judul, Konten, Gambar
- Visi: Judul + Konten
- Misi: Judul + Konten
- Nilai-nilai (4 items): Judul + Deskripsi per nilai
- Tim (6 members): Nama, Posisi, Foto per anggota
- Sertifikasi (4 items): Judul + Gambar per sertifikat
- CTA: Judul, Subtitle, Button Text, Link

#### 11. **Halaman Pelanggan**
- Hero: Judul + Subtitle
- Logos: Judul Utama + Subtitle
- Company Logos (12 items): Nama + Gambar per logo
- CTA: Judul, Subtitle, Button Text, Link

#### 12. **Halaman Paket Usaha**
- Hero: Judul + Subtitle
- Features (4 items): Icon, Judul, Deskripsi per feature
- CTA: Judul, Subtitle, Button Text, Link

### Cara Edit di Customizer:

1. Login WordPress Admin
2. **Appearance → Customize**
3. Pilih section yang ingin diedit (misal: "Hero Section")
4. Edit field yang tersedia
5. **Live Preview** otomatis update di sebelah kanan
6. Jika sudah OK, klik **Publish** (pojok kiri atas)
7. Atau klik **Save Draft** untuk simpan sementara

**Tips:**
- Gunakan "Live Preview" untuk lihat hasil realtime
- Bisa edit sambil buka website di tab lain
- Perubahan baru muncul setelah klik "Publish"
- Bisa schedule publish untuk nanti

---

## Docker Commands

```bash
# Start
docker compose up -d

# Stop
docker compose down

# Restart
docker compose restart

# View logs
docker compose logs -f

# Remove all (termasuk data)
docker compose down -v
```

## File Structure

```
invirowp-theme/
├── assets/
│   ├── css/               # Modular CSS files
│   ├── js/                # JavaScript
│   └── images/            # Static images
├── front-page.php         # Beranda
├── page-profil.php        # Profil
├── page-pelanggan.php     # Pelanggan
├── page-paket-usaha.php   # Paket Usaha
├── functions.php          # Theme functions
├── header.php             # Header dengan active navbar
├── footer.php             # Footer
└── style.css              # Theme info
```

## Browser Support

Chrome, Firefox, Safari, Edge (latest versions)

## Tips & Best Practices

### 📸 Gambar
- **Featured Image:** Min 1200x630px untuk quality terbaik
- **Format:** JPG untuk foto, PNG untuk logo/transparan
- **Compress:** Gunakan tinypng.com sebelum upload
- **Naming:** Beri nama deskriptif (contoh: `damiu-paket-a.jpg` bukan `IMG_1234.jpg`)
- **Alt Text:** Isi alt text untuk SEO (describe gambar)

### 🎨 Logo & Brand
- **Logo perusahaan:** Upload di Media Library, copy URL, paste di Customizer
- **Size:** 200x60px recommended untuk header
- **Format:** PNG dengan background transparan
- **Favicon:** 32x32px atau 512x512px (WordPress auto resize)

### 🗺️ Google Maps
- Buka Google Maps → Cari lokasi
- Klik "Share" → "Embed a map"
- Copy URL iframe (yang panjang)
- Paste di Customizer → Pengaturan Peta

### 📱 WhatsApp
- **Format:** 621234567890 (tanpa + atau spasi)
- Contoh salah: `+62 123 456 7890` atau `(021) 123-456`
- Contoh benar: `621234567890`

### 🎯 SEO Tips
- Isi judul halaman max 60 karakter
- Isi meta description (plugin: Yoast SEO)
- Gunakan heading structure (H1 → H2 → H3)
- Alt text di semua gambar
- URL permalink pendek & deskriptif

### 🚀 Performance
- Compress gambar sebelum upload
- Gunakan caching plugin (WP Super Cache)
- Enable lazy loading gambar
- Minify CSS/JS (plugin: Autoptimize)
- Gunakan CDN untuk images

---

## Troubleshooting - Masalah Umum

### ❌ Menu tidak ter-highlight
**Masalah:** Menu aktif tidak muncul garis biru

**Solusi:**
1. Pastikan slug halaman sesuai:
   - Profil: `/profil`
   - Pelanggan: `/pelanggan`
   - Paket Usaha: `/paket-usaha`
2. Clear browser cache (Ctrl+Shift+R)
3. Check di **Appearance → Menus** sudah assign ke "Menu Utama"

---

### ❌ Featured Image tidak muncul
**Masalah:** Box Featured Image tidak ada di sidebar

**Solusi:**
1. Klik **Screen Options** (pojok kanan atas halaman edit)
2. Centang **"Featured Image"**
3. Scroll sidebar kanan, box akan muncul
4. Klik "Set featured image"

---

### ❌ Gambar tidak tampil di website
**Masalah:** Upload berhasil tapi gambar tidak muncul

**Solusi:**
1. Check file size (max 2MB)
2. Check format (hanya JPG, PNG, WebP)
3. Re-upload dengan nama file tanpa spasi
4. Check permissions folder `wp-content/uploads/`
5. Clear cache (browser + WordPress cache)

---

### ❌ CSS tidak load / tampilan berantakan
**Masalah:** Halaman tampil tanpa styling

**Solusi:**
1. Hard refresh browser: `Ctrl+Shift+R` (Windows) atau `Cmd+Shift+R` (Mac)
2. Clear WordPress cache jika pakai caching plugin
3. Check file CSS ada di `/assets/css/`
4. Check functions.php tidak ada error (Dashboard akan show error)

---

### ❌ Customizer tidak save
**Masalah:** Klik Publish tapi perubahan tidak tersimpan

**Solusi:**
1. Check koneksi internet
2. Logout dan login ulang WordPress
3. Disable plugins sementara (cek conflict)
4. Check browser console untuk error (F12)
5. Coba browser lain

---

### ❌ Template tidak muncul di dropdown
**Masalah:** Saat buat halaman baru, template "Profil" / "Pelanggan" tidak ada

**Solusi:**
1. Check file template ada di root theme:
   - `page-profil.php`
   - `page-pelanggan.php`
   - `page-paket-usaha.php`
2. Check di baris pertama file ada comment:
   ```php
   /**
    * Template Name: Profil
    */
   ```
3. Re-activate theme (Appearance → Themes)

---

### ❌ Docker tidak jalan
**Masalah:** `docker compose up -d` error

**Solusi:**
1. Check Docker Desktop running
2. Check port 8080 tidak dipakai aplikasi lain
3. Hapus container lama:
   ```bash
   docker compose down -v
   docker compose up -d
   ```
4. Check log error:
   ```bash
   docker compose logs
   ```

---

## FAQ - Pertanyaan Sering Ditanya

**Q: Berapa halaman yang harus dibuat?**
A: Minimal 4 halaman:
- Beranda (otomatis)
- Profil
- Pelanggan  
- Paket Usaha

**Q: Apakah bisa edit HTML/CSS langsung?**
A: Bisa, tapi tidak recommended. Gunakan Customizer atau Child Theme.

**Q: Bagaimana cara backup theme?**
A: 
1. Manual: Download folder theme via FTP
2. Plugin: UpdraftPlus, BackWPup
3. Docker: Backup volume

**Q: Apakah bisa ganti warna theme?**
A: Ya, edit file `assets/css/base.css` bagian `:root` variables.

**Q: Produk/Testimoni/Cabang maksimal berapa?**
A: Unlimited, tapi untuk performance bagus max 50-100 per CPT.

**Q: Apakah support multilanguage?**
A: Saat ini bahasa Indonesia saja. Bisa pakai plugin WPML/Polylang untuk multilang.

**Q: Bagaimana cara update theme?**
A: 
1. Backup dulu
2. Download theme baru
3. Upload via Appearance → Themes → Add New → Upload
4. Activate

**Q: Apakah bisa pakai page builder (Elementor, dll)?**
A: Bisa, tapi akan override template theme. Tidak recommended.

**Q: Contact form tidak kirim email?**
A: Install plugin: Contact Form 7 atau WPForms, setup SMTP plugin (WP Mail SMTP).

**Q: Filter daerah di Halaman Pelanggan tidak berfungsi?**
A: Pastikan:
1. Setiap Cabang sudah dipilih Daerahnya (checkbox di sidebar kanan)
2. JavaScript sudah di-enqueue (cek Console browser untuk error)
3. Clear cache browser dan WordPress

**Q: Bagaimana cara menambah daerah baru (selain 7 daerah default)?**
A: Edit file `functions.php`, tambahkan di function `inviro_register_region_taxonomy()`:
```php
if (!term_exists('Nama Daerah Baru', 'region')) {
    wp_insert_term('Nama Daerah Baru', 'region', array('slug' => 'nama-daerah-baru'));
}
```
Lalu update tab filter di `page-pelanggan.php`.

---

### 🔧 Halaman Spare Parts

**Setup:**
1. **Pages → Add New**
2. **Judul:** "Spare Parts"
3. **Permalink:** Otomatis jadi `/spareparts` (jangan diubah)
4. **Sidebar Kanan → Template:** Pilih **"Spare Parts"**
5. **Publish**
6. **Appearance → Menus:** Tambahkan "Spare Parts" ke menu utama

**Tambah Spare Part:**
1. **Spare Parts → Tambah Spare Part**
2. **Judul:** Nama spare part (contoh: "Filter Air Berkualitas Tinggi")
3. **Content:** Deskripsi lengkap spare part, spesifikasi, kegunaan
4. **Excerpt:** Ringkasan singkat (tampil di card)
5. **Featured Image:** Upload foto spare part (WAJIB)
   - Background putih/bersih
   - Ukuran: 600x600px recommended
6. **Sidebar Kanan → Harga:** Isi harga (contoh: 150000)
7. **Sidebar Kanan → Stok:** Isi jumlah stok (contoh: 50)
8. **Sidebar Kanan → SKU:** Isi kode SKU (contoh: SP-001)
9. **Publish**

**Fitur Halaman:**
- Search bar - cari spare part by nama/deskripsi
- Sort dropdown - urutkan by latest/price/name
- Stock badge - hijau (Tersedia) / merah (Habis)
- WhatsApp order button - langsung chat order ke admin
- Responsive grid 4 kolom

**Tips:**
- **Foto produk:** Angle jelas, fokus pada produk
- **SKU:** Format konsisten (SP-001, SP-002, dst)
- **Stok:** Update rutin agar customer tidak kecewa
- **Harga:** Format angka saja tanpa "Rp" atau titik

---

### 📰 Halaman Artikel

**Setup:**
1. **Pages → Add New**
2. **Judul:** "Artikel" atau "Berita"
3. **Permalink:** Otomatis jadi `/artikel` (jangan diubah)
4. **Sidebar Kanan → Template:** Pilih **"Artikel dan Berita"**
5. **Publish**
6. **Appearance → Menus:** Tambahkan "Artikel" ke menu utama

**Tambah Artikel:**
1. **Artikel → Tambah Artikel**
2. **Judul:** Judul artikel menarik (SEO-friendly)
3. **Content:** Tulis artikel lengkap:
   - Opening paragraph
   - Body (gunakan heading H2/H3 untuk struktur)
   - Conclusion
   - Format: bold, italic, list, quote
4. **Excerpt:** Ringkasan artikel (tampil di card listing)
5. **Featured Image:** Upload gambar header artikel (WAJIB)
   - Landscape, 1200x600px recommended
6. **Author:** Otomatis terisi current user
7. **Publish**

**Fitur Halaman:**
- Search bar - cari artikel by judul/konten
- 3 kolom grid responsive
- Author name & date display
- "Baca Selengkapnya" button → ke single artikel

**Fitur Single Artikel:**
- Hero dengan featured image overlay
- Reading time estimate (auto-calculate)
- Full article content dengan formatting
- Author bio box (ambil dari user description)
- Share buttons (Facebook, Twitter, WhatsApp)
- Related articles sidebar (3 artikel random)
- Prev/Next navigation
- Comments section (jika diaktifkan)
- CTA WhatsApp consultation

**Tips:**
- **Judul:** Maksimal 60 karakter untuk SEO
- **Featured Image:** High quality, relevant ke artikel
- **Excerpt:** 150-160 karakter
- **Content:** Min 300 kata untuk SEO bagus
- **Author Bio:** Isi di Users → Your Profile → Biographical Info

---

### 📥 Halaman Unduhan

**Setup:**
1. **Pages → Add New**
2. **Judul:** "Unduhan" atau "Download"
3. **Permalink:** Otomatis jadi `/unduhan` (jangan diubah)
4. **Sidebar Kanan → Template:** Pilih **"Unduhan"**
5. **Publish**
6. **Appearance → Menus:** Tambahkan "Unduhan" ke menu utama

**Tambah File Unduhan:**
1. **Unduhan → Tambah Unduhan**
2. **Judul:** Nama file (contoh: "Katalog Produk 2024")
3. **Content:** Tidak ada editor - fokus pada file
4. **Excerpt:** Deskripsi file (tampil di card)
5. **Featured Image:** Upload thumbnail (optional tapi recommended)
   - Bisa screenshot cover PDF
   - Icon file
   - Ukuran: 400x400px
6. **Sidebar Kanan → File URL:**
   - Klik tombol **"📂 Pilih File"**
   - Upload file atau pilih dari Media Library
   - Support: PDF, DOC, DOCX, XLS, XLSX, ZIP, RAR, JPG, PNG
7. **Sidebar Kanan → Ukuran File:** Isi manual (contoh: "5 MB", "2.3 MB")
8. **Sidebar Kanan → Tipe File:** Isi manual (contoh: "PDF", "Excel", "ZIP")
9. **Sidebar Kanan → Jumlah Download:** Readonly, auto-increment saat di-download
10. **Publish**

**Fitur Halaman:**
- Search bar - cari file by nama/deskripsi
- Filter by type - PDF, DOC, Excel, ZIP, Image
- File icon otomatis berdasarkan extension
- File info badge (type, size, download count)
- Download button dengan AJAX tracking
- Responsive grid 3 kolom

**Cara Kerja Download Tracking:**
1. User klik tombol "Unduh File"
2. JavaScript kirim AJAX request ke server
3. Server increment `_unduhan_download_count` meta
4. Display auto-update tanpa page reload
5. File tetap ter-download ke device user

**Tips:**
- **Nama file:** Deskriptif (bukan "document1.pdf")
- **Ukuran file:** Jangan terlalu besar (max 10 MB recommended)
- **Thumbnail:** Buat menarik agar user tertarik download
- **Deskripsi:** Jelaskan isi file secara detail
- **File type:** Gunakan format umum (PDF lebih baik dari DOC)

---

## Support

Tim Development INVIRO

