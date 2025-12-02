# Front Page Template Parts

Template parts untuk halaman depan (front-page.php) yang sudah di-refactor dan diorganisir per section.

## Struktur

Semua template parts menggunakan:
- **CSS yang sama**: `front-page.css` (di-enqueue di `inc/enqueue/enqueue.php`)
- **Helper functions**: Semua gambar menggunakan `inviro_get_image_url()` dan `inviro_normalize_image_url()`
- **Struktur HTML konsisten**: Semua section menggunakan class `container` dan struktur yang sama

## File Template Parts

1. **hero-section.php** - Hero section dengan grid artikel
2. **statistics-section.php** - Section statistik
3. **about-section.php** - Section tentang perusahaan dan cabang
4. **products-section.php** - Section produk
5. **testimonials-section.php** - Section testimoni
6. **contact-section.php** - Section kontak dengan form

## Helper Functions

Semua helper functions ada di `inc/helpers/helpers.php`:
- `inviro_normalize_image_url()` - Normalisasi URL gambar
- `inviro_get_image_url()` - Ambil URL gambar dengan fallback
- `inviro_get_logo_url()` - Ambil URL logo
- `inviro_get_hero_projects()` - Ambil data proyek hero
- `inviro_render_hero_card()` - Render card hero

## CSS

Semua CSS untuk front-page ada di:
- `assets/css/front-page.css` - CSS utama untuk semua section
- `assets/css/hero-section-fix.css` - Fix khusus untuk hero section
- `assets/css/product-fix.css` - Fix khusus untuk product section

CSS di-enqueue otomatis ketika `is_front_page()` adalah true.

## Catatan

- Semua gambar menggunakan helper function untuk memastikan URL termuat dengan benar
- Semua template parts menggunakan struktur HTML yang konsisten
- Semua class CSS mengikuti naming convention yang sama

