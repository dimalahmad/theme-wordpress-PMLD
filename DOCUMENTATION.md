# 📚 INVIRO WordPress Theme - Dokumentasi Lengkap

## 📋 Daftar Isi

1. [Overview Project](#overview-project)
2. [Struktur Folder & File](#struktur-folder--file)
3. [Custom Post Types](#custom-post-types)
4. [Custom Taxonomies](#custom-taxonomies)
5. [Meta Boxes & Custom Fields](#meta-boxes--custom-fields)
6. [Template Files](#template-files)
7. [CSS & JavaScript Structure](#css--javascript-structure)
8. [AJAX Handlers](#ajax-handlers)
9. [Form Handlers](#form-handlers)
10. [WordPress Customizer](#wordpress-customizer)
11. [Hooks & Filters](#hooks--filters)
12. [Database Schema](#database-schema)
13. [Setup & Installation](#setup--installation)
14. [Development Guidelines](#development-guidelines)
15. [Troubleshooting](#troubleshooting)
16. [API Reference](#api-reference)

---

## 🎯 Overview Project

### Informasi Theme
- **Theme Name**: INVIRO WP Theme
- **Version**: 1.0.1
- **Author**: INVIRO Development Team
- **Description**: Tema WordPress kontemporer bergaya Figma untuk perusahaan pengolahan air. Modern, profesional, SEO-friendly, dan 100% responsif.
- **Text Domain**: inviro
- **License**: GNU General Public License v2 or later

### Teknologi yang Digunakan
- **WordPress**: 5.0+
- **PHP**: 7.4+
- **CSS**: Modern CSS dengan CSS Variables
- **JavaScript**: jQuery (included dengan WordPress)
- **Build Tools**: Tidak ada (vanilla CSS/JS)

### Fitur Utama
- ✅ 10+ Custom Post Types
- ✅ Custom Taxonomies
- ✅ Meta Boxes untuk Custom Fields
- ✅ AJAX Form Handling
- ✅ Review System untuk Spareparts & Paket Usaha
- ✅ WordPress Customizer Integration
- ✅ Responsive Design
- ✅ SEO Friendly
- ✅ Modern UI/UX

---

## 📁 Struktur Folder & File

```
wp-inviro-theme/
│
├── assets/                          # Assets folder
│   ├── css/                         # Stylesheets
│   │   ├── base.css                 # Base styles & reset
│   │   ├── main.css                 # Main styles
│   │   ├── header.css               # Header styles
│   │   ├── footer.css               # Footer styles
│   │   ├── front-page.css           # Homepage styles
│   │   ├── components/              # Component styles
│   │   │   ├── cards.css            # Card components
│   │   │   └── forms.css            # Form components
│   │   ├── profil.css               # Profil page styles
│   │   ├── paket-usaha.css          # Paket Usaha archive styles
│   │   ├── paket-article.css        # Paket Usaha article styles
│   │   ├── pelanggan.css            # Pelanggan page styles
│   │   ├── pelanggan-article.css    # Pelanggan article styles
│   │   ├── spareparts.css           # Spareparts archive styles
│   │   ├── sparepart-detail.css     # Spareparts detail styles
│   │   ├── artikel.css              # Artikel styles
│   │   ├── archive.css              # Archive pages styles
│   │   ├── single.css               # Single post styles
│   │   └── animations.css           # Animation styles
│   │
│   ├── js/                          # JavaScript files
│   │   ├── main.js                  # Main JavaScript
│   │   ├── customizer-preview.js    # Customizer live preview
│   │   └── pelanggan-filter.js      # Pelanggan filter functionality
│   │
│   └── images/                      # Theme images
│
├── dummy-data/                      # Dummy data untuk development
│   ├── helper.php                   # Helper functions untuk dummy data
│   ├── products.json                # Dummy products data
│   ├── paket-usaha.json             # Dummy paket usaha data
│   ├── spareparts.json              # Dummy spareparts data
│   ├── pelanggan.json               # Dummy pelanggan data
│   ├── artikel.json                 # Dummy artikel data
│   ├── unduhan.json                 # Dummy unduhan data
│   ├── branches.json                # Dummy branches data
│   ├── testimonials.json            # Dummy testimonials data
│   └── README.md                    # Dummy data documentation
│
├── inc/                             # Include files (organized functions)
│   ├── loader.php                   # Main loader file
│   │
│   ├── helpers/                     # Helper functions
│   │   └── helpers.php              # General helper functions
│   │
│   ├── theme-setup.php              # Theme setup & initialization
│   │
│   ├── enqueue/                     # Asset enqueueing
│   │   └── enqueue.php              # CSS & JS enqueue logic
│   │
│   ├── post-types/                  # Custom Post Types
│   │   ├── products.php             # Products CPT
│   │   ├── paket-usaha.php          # Paket Usaha CPT
│   │   ├── paket-usaha-reviews.php  # Paket Usaha Reviews CPT
│   │   ├── testimonials.php         # Testimonials CPT
│   │   ├── branches.php             # Branches CPT
│   │   ├── layanan.php              # Layanan CPT
│   │   ├── proyek-pelanggan.php     # Proyek Pelanggan CPT
│   │   ├── spareparts.php           # Spareparts CPT
│   │   ├── sparepart-reviews.php    # Sparepart Reviews CPT
│   │   ├── artikel.php              # Artikel CPT
│   │   └── unduhan.php              # Unduhan CPT
│   │
│   ├── meta-boxes/                  # Meta Boxes & Custom Fields
│   │   ├── products.php             # Products meta boxes
│   │   ├── paket-usaha.php          # Paket Usaha meta boxes
│   │   ├── paket-usaha-reviews.php  # Paket Usaha Reviews meta boxes
│   │   ├── testimonials.php         # Testimonials meta boxes
│   │   ├── branches.php             # Branches meta boxes
│   │   ├── layanan.php              # Layanan meta boxes
│   │   ├── proyek-pelanggan.php     # Proyek Pelanggan meta boxes
│   │   ├── spareparts.php           # Spareparts meta boxes
│   │   ├── sparepart-reviews.php    # Sparepart Reviews meta boxes
│   │   └── unduhan.php              # Unduhan meta boxes
│   │
│   ├── ajax/                        # AJAX handlers
│   │   └── ajax-handlers.php        # All AJAX handlers
│   │
│   ├── forms/                       # Form handlers
│   │   └── form-handlers.php        # Form submission handlers
│   │
│   ├── customizer/                  # WordPress Customizer
│   │   └── customizer.php           # Customizer settings
│   │
│   └── hooks/                       # Hooks & Filters
│       └── hooks.php                # All hooks and filters
│
├── functions.php                    # Main functions file (minimal, hanya loader)
├── style.css                        # Theme stylesheet (header info)
├── index.php                        # Fallback template
├── header.php                       # Header template
├── footer.php                       # Footer template
│
├── front-page.php                   # Homepage template
├── page.php                         # Default page template
│
├── page-profil.php                  # Profil page template
├── page-paket-usaha.php             # Paket Usaha archive page
├── page-pelanggan.php               # Pelanggan page template
├── page-spareparts.php              # Spareparts archive page
├── page-artikel.php                 # Artikel archive page
├── page-unduhan.php                 # Unduhan archive page
├── page-tambah-proyek.php           # Add project form page
│
├── single-paket-usaha.php           # Single Paket Usaha template
├── single-spareparts.php            # Single Spareparts template
├── single-artikel.php               # Single Artikel template
├── single-proyek_pelanggan.php      # Single Proyek Pelanggan template
│
├── archive-paket_usaha.php          # Paket Usaha archive template
├── archive-produk.php               # Products archive template
├── archive-proyek_pelanggan.php     # Proyek Pelanggan archive template
├── archive-artikel.php              # Artikel archive template
├── archive-unduhan.php              # Unduhan archive template
│
└── screenshot.png                   # Theme screenshot
```

---

## 🗂️ Custom Post Types

Theme ini menggunakan **10 Custom Post Types** yang terorganisir dengan baik:

### 1. Products (`produk`)
- **File**: `inc/post-types/products.php`
- **Slug**: `/produk/`
- **Public**: No (tidak ada single page)
- **Archive**: Yes
- **Supports**: `title`, `thumbnail`
- **Icon**: `dashicons-cart`
- **Description**: Produk yang ditampilkan di homepage

### 2. Paket Usaha (`paket_usaha`)
- **File**: `inc/post-types/paket-usaha.php`
- **Slug**: `/paket-usaha/`
- **Public**: Yes
- **Archive**: Yes (`/paket-usaha/`)
- **Supports**: `title`, `editor`, `thumbnail`
- **Icon**: `dashicons-portfolio`
- **Taxonomy**: `paket_usaha_category`
- **Description**: Paket usaha yang ditawarkan

### 3. Paket Usaha Reviews (`paket_usaha_review`)
- **File**: `inc/post-types/paket-usaha-reviews.php`
- **Public**: No (internal use only)
- **Supports**: `title`, `editor`
- **Description**: Review untuk paket usaha

### 4. Testimonials (`testimonial`)
- **File**: `inc/post-types/testimonials.php`
- **Slug**: `/testimonial/`
- **Public**: No
- **Archive**: No
- **Supports**: `title`, `editor`, `thumbnail`
- **Icon**: `dashicons-format-quote`
- **Description**: Testimoni pelanggan

### 5. Branches (`branch`)
- **File**: `inc/post-types/branches.php`
- **Slug**: `/branch/`
- **Public**: No
- **Archive**: No
- **Supports**: `title`, `editor`, `thumbnail`
- **Icon**: `dashicons-location`
- **Description**: Cabang perusahaan

### 6. Layanan (`layanan`)
- **File**: `inc/post-types/layanan.php`
- **Slug**: `/layanan/`
- **Public**: No
- **Archive**: No
- **Supports**: `title`, `editor`, `thumbnail`
- **Icon**: `dashicons-admin-tools`
- **Description**: Layanan yang ditawarkan

### 7. Proyek Pelanggan (`proyek_pelanggan`)
- **File**: `inc/post-types/proyek-pelanggan.php`
- **Slug**: `/proyek-pelanggan/`
- **Public**: Yes
- **Archive**: Yes
- **Supports**: `title`, `editor`, `thumbnail`, `excerpt`
- **Icon**: `dashicons-businessperson`
- **Taxonomy**: `region`
- **Description**: Proyek-proyek pelanggan

### 8. Spareparts (`spareparts`)
- **File**: `inc/post-types/spareparts.php`
- **Slug**: `/spareparts/`
- **Public**: Yes
- **Archive**: Yes
- **Supports**: `title`, `editor`, `thumbnail`, `excerpt`
- **Icon**: `dashicons-admin-tools`
- **Taxonomy**: `sparepart_category`
- **Description**: Spare parts yang dijual

### 9. Sparepart Reviews (`sparepart_review`)
- **File**: `inc/post-types/sparepart-reviews.php`
- **Public**: No (internal use only)
- **Supports**: `title`, `editor`
- **Description**: Review untuk spareparts

### 10. Artikel (`artikel`)
- **File**: `inc/post-types/artikel.php`
- **Slug**: `/artikel/`
- **Public**: Yes
- **Archive**: Yes
- **Supports**: `title`, `editor`, `thumbnail`, `excerpt`, `author`, `comments`
- **Icon**: `dashicons-welcome-write-blog`
- **Description**: Artikel/blog posts

### 11. Unduhan (`unduhan`)
- **File**: `inc/post-types/unduhan.php`
- **Slug**: `/unduhan/`
- **Public**: Yes
- **Archive**: Yes
- **Supports**: `title`, `editor`, `thumbnail`
- **Icon**: `dashicons-download`
- **Description**: File downloads

---

## 🏷️ Custom Taxonomies

### 1. Paket Usaha Category (`paket_usaha_category`)
- **Post Type**: `paket_usaha`
- **Hierarchical**: Yes
- **Slug**: `/kategori-paket-usaha/`
- **File**: `inc/post-types/paket-usaha.php`

### 2. Sparepart Category (`sparepart_category`)
- **Post Type**: `spareparts`
- **Hierarchical**: Yes
- **File**: `inc/post-types/spareparts.php`

### 3. Region (`region`)
- **Post Type**: `proyek_pelanggan`
- **Hierarchical**: Yes
- **File**: `inc/post-types/proyek-pelanggan.php`

---

## 📦 Meta Boxes & Custom Fields

Semua meta boxes terorganisir di folder `inc/meta-boxes/`. Setiap post type memiliki file meta box sendiri.

### Products Meta Boxes
**File**: `inc/meta-boxes/products.php`

| Field Name | Meta Key | Type | Description |
|------------|----------|------|-------------|
| Link Produk | `_product_link` | URL | Link ke halaman produk |
| Icon | `_product_icon` | Text | Nama icon (dashicons) |

### Paket Usaha Meta Boxes
**File**: `inc/meta-boxes/paket-usaha.php`

| Field Name | Meta Key | Type | Description |
|------------|----------|------|-------------|
| Harga | `_paket_price` | Number | Harga paket |
| Harga Asli | `_paket_original_price` | Number | Harga sebelum diskon |
| SKU | `_paket_sku` | Text | SKU paket |
| Promo | `_paket_promo` | Checkbox | Status promo (1/0) |
| Deskripsi | `_paket_description` | Textarea | Deskripsi singkat |
| Gallery | `_paket_gallery` | Text | Comma-separated attachment IDs |
| Spesifikasi | `_paket_specifications` | JSON | Array spesifikasi (label, value) |
| Bonus | `_paket_bonus` | JSON | Array bonus items |

**Spesifikasi Format**:
```json
[
  {"label": "Kapasitas", "value": "2000 GPD"},
  {"label": "Material", "value": "Stainless Steel"}
]
```

### Spareparts Meta Boxes
**File**: `inc/meta-boxes/spareparts.php`

| Field Name | Meta Key | Type | Description |
|------------|----------|------|-------------|
| Harga | `_sparepart_price` | Number | Harga sparepart |
| Harga Asli | `_sparepart_original_price` | Number | Harga sebelum diskon |
| Stock | `_sparepart_stock` | Number | Jumlah stock |
| SKU | `_sparepart_sku` | Text | SKU sparepart |
| Promo | `_sparepart_promo` | Checkbox | Status promo (1/0) |
| Gallery | `_sparepart_gallery` | Text | Comma-separated attachment IDs |
| Spesifikasi | `_sparepart_specifications` | JSON | Array spesifikasi |

### Proyek Pelanggan Meta Boxes
**File**: `inc/meta-boxes/proyek-pelanggan.php`

| Field Name | Meta Key | Type | Description |
|------------|----------|------|-------------|
| Nama Pelanggan | `_pelanggan_name` | Text | Nama pelanggan |
| Lokasi | `_pelanggan_location` | Text | Lokasi proyek |
| Tanggal | `_pelanggan_date` | Date | Tanggal proyek |
| Gallery | `_pelanggan_gallery` | Text | Comma-separated attachment IDs |

### Testimonials Meta Boxes
**File**: `inc/meta-boxes/testimonials.php`

| Field Name | Meta Key | Type | Description |
|------------|----------|------|-------------|
| Nama | `_testimonial_name` | Text | Nama pemberi testimoni |
| Posisi | `_testimonial_position` | Text | Posisi/jabatan |
| Rating | `_testimonial_rating` | Number | Rating (1-5) |

### Branches Meta Boxes
**File**: `inc/meta-boxes/branches.php`

| Field Name | Meta Key | Type | Description |
|------------|----------|------|-------------|
| Alamat | `_branch_address` | Textarea | Alamat lengkap |
| Telepon | `_branch_phone` | Text | Nomor telepon |
| Email | `_branch_email` | Email | Email cabang |
| Maps URL | `_branch_maps_url` | URL | Google Maps URL |

### Layanan Meta Boxes
**File**: `inc/meta-boxes/layanan.php`

| Field Name | Meta Key | Type | Description |
|------------|----------|------|-------------|
| Icon | `_layanan_icon` | Text | Nama icon (dashicons) |
| Link | `_layanan_link` | URL | Link ke halaman layanan |

### Artikel Meta Boxes
**File**: Tidak ada (menggunakan default WordPress fields)

### Unduhan Meta Boxes
**File**: `inc/meta-boxes/unduhan.php`

| Field Name | Meta Key | Type | Description |
|------------|----------|------|-------------|
| File | `_unduhan_file` | File | Attachment ID file |
| Ukuran File | `_unduhan_size` | Text | Ukuran file (auto-generated) |
| Tipe File | `_unduhan_type` | Text | Tipe file (auto-generated) |

### Review Meta Boxes

#### Sparepart Reviews
**File**: `inc/meta-boxes/sparepart-reviews.php`

| Field Name | Meta Key | Type | Description |
|------------|----------|------|-------------|
| Sparepart ID | `_review_sparepart_id` | Text | ID sparepart (bisa dummy_) |
| Reviewer Name | `_reviewer_name` | Text | Nama reviewer |
| Reviewer Email | `_reviewer_email` | Email | Email reviewer |
| Rating | `_review_rating` | Number | Rating (1-5) |
| Status | `_review_status` | Text | `approved` atau `pending` |
| Is Dummy | `_review_is_dummy` | Text | `1` atau `0` |
| Product Type | `_review_product_type` | Text | `spareparts` |

#### Paket Usaha Reviews
**File**: `inc/meta-boxes/paket-usaha-reviews.php`

| Field Name | Meta Key | Type | Description |
|------------|----------|------|-------------|
| Paket ID | `_review_sparepart_id` | Text | ID paket (bisa dummy_) |
| Reviewer Name | `_reviewer_name` | Text | Nama reviewer |
| Reviewer Email | `_reviewer_email` | Email | Email reviewer |
| Rating | `_review_rating` | Number | Rating (1-5) |
| Status | `_review_status` | Text | `approved` atau `pending` |
| Is Dummy | `_review_is_dummy` | Text | `1` atau `0` |

---

## 📄 Template Files

### Page Templates

#### `front-page.php`
- **Purpose**: Homepage template
- **Features**: 
  - Hero section dengan customizer settings
  - Products grid
  - Layanan section
  - Testimonials slider
  - CTA sections

#### `page-profil.php`
- **Purpose**: Profil perusahaan page
- **Template Name**: "Profil"
- **Features**: Profil lengkap perusahaan

#### `page-paket-usaha.php`
- **Purpose**: Paket Usaha archive page
- **Template Name**: "Paket Usaha"
- **Features**: 
  - Filter by category
  - Grid layout
  - Search functionality

#### `page-pelanggan.php`
- **Purpose**: Pelanggan/Projects page
- **Template Name**: "Pelanggan"
- **Features**: 
  - Filter by region
  - Grid layout
  - Project details

#### `page-spareparts.php`
- **Purpose**: Spareparts archive page
- **Template Name**: "Spare Parts"
- **Features**: 
  - Filter by category
  - Grid layout
  - Search functionality

#### `page-artikel.php`
- **Purpose**: Artikel archive page
- **Template Name**: "Artikel"
- **Features**: Blog-style archive

#### `page-unduhan.php`
- **Purpose**: Unduhan archive page
- **Template Name**: "Unduhan"
- **Features**: Downloadable files list

#### `page-tambah-proyek.php`
- **Purpose**: Add project form page
- **Template Name**: "Tambah Proyek"
- **Features**: Form untuk submit proyek baru

### Single Templates

#### `single-paket-usaha.php`
- **Purpose**: Single Paket Usaha detail page
- **Features**:
  - Image gallery dengan thumbnails
  - Product info card (harga, SKU, promo badge)
  - Spesifikasi table
  - Review section dengan form
  - WhatsApp order button

#### `single-spareparts.php`
- **Purpose**: Single Spareparts detail page
- **Features**:
  - Image gallery dengan thumbnails
  - Product info card (harga, SKU, stock, promo badge)
  - Spesifikasi table
  - Review section dengan form
  - WhatsApp order button

#### `single-artikel.php`
- **Purpose**: Single Artikel/Blog post
- **Features**: Standard blog post layout

#### `single-proyek_pelanggan.php`
- **Purpose**: Single Proyek Pelanggan detail
- **Features**: Project details dengan gallery

### Archive Templates

#### `archive-paket_usaha.php`
- **Purpose**: Paket Usaha archive (fallback)
- **Features**: Grid layout dengan pagination

#### `archive-produk.php`
- **Purpose**: Products archive
- **Features**: Products grid

#### `archive-proyek_pelanggan.php`
- **Purpose**: Proyek Pelanggan archive
- **Features**: Projects grid dengan filter

#### `archive-artikel.php`
- **Purpose**: Artikel archive
- **Features**: Blog-style archive

#### `archive-unduhan.php`
- **Purpose**: Unduhan archive
- **Features**: Downloadable files list

---

## 🎨 CSS & JavaScript Structure

### CSS Architecture

Theme menggunakan **modular CSS approach** dengan file terpisah untuk setiap section:

#### Base Styles
- **`base.css`**: Reset, typography, variables, base elements
- **`main.css`**: Main layout styles
- **`animations.css`**: Keyframe animations

#### Component Styles
- **`components/cards.css`**: Card component styles
- **`components/forms.css`**: Form component styles

#### Page-Specific Styles
- **`header.css`**: Header & navigation
- **`footer.css`**: Footer styles
- **`front-page.css`**: Homepage styles
- **`profil.css`**: Profil page
- **`paket-usaha.css`**: Paket Usaha archive
- **`paket-article.css`**: Paket Usaha single
- **`pelanggan.css`**: Pelanggan page
- **`pelanggan-article.css`**: Proyek Pelanggan single
- **`spareparts.css`**: Spareparts archive
- **`sparepart-detail.css`**: Spareparts & Paket Usaha detail (shared)
- **`artikel.css`**: Artikel pages
- **`archive.css`**: Archive pages
- **`single.css`**: Single post pages

### CSS Variables

Theme menggunakan CSS variables untuk warna yang bisa di-customize via Customizer:

```css
:root {
  --inviro-primary: #2F80ED;
  --inviro-primary-medium: #4FB3E8;
  --inviro-primary-light: #75C6F1;
  --inviro-secondary: #FF6B35;
  /* ... */
}
```

### JavaScript Files

#### `main.js`
- Main JavaScript file
- Navigation functionality
- Smooth scrolling
- Form validations
- General interactions

#### `customizer-preview.js`
- Live preview untuk WordPress Customizer
- Real-time update saat mengubah settings

#### `pelanggan-filter.js`
- Filter functionality untuk halaman Pelanggan
- AJAX filtering by region

### Enqueue Logic

**File**: `inc/enqueue/enqueue.php`

CSS dan JS di-enqueue berdasarkan:
- Page template
- Post type
- Conditional logic

**Priority Order**:
1. Base styles (always loaded)
2. Component styles
3. Page-specific styles (conditional)

---

## 🔄 AJAX Handlers

**File**: `inc/ajax/ajax-handlers.php`

### Available AJAX Actions

#### 1. `save_paket_gallery`
- **Action**: `wp_ajax_save_paket_gallery` & `wp_ajax_nopriv_save_paket_gallery`
- **Purpose**: Save gallery images untuk Paket Usaha
- **Parameters**:
  - `post_id`: Post ID
  - `gallery_ids`: Comma-separated attachment IDs
  - `nonce`: Security nonce
- **Returns**: JSON success/error

#### 2. `submit_sparepart_review`
- **Action**: `wp_ajax_submit_sparepart_review` & `wp_ajax_nopriv_submit_sparepart_review`
- **Purpose**: Submit review untuk Spareparts
- **Parameters**:
  - `sparepart_id`: Sparepart ID (bisa dummy_)
  - `reviewer_name`: Nama reviewer
  - `reviewer_email`: Email reviewer
  - `rating`: Rating (1-5)
  - `review_content`: Isi review
  - `is_dummy`: `1` atau `0`
  - `product_type`: `spareparts`
  - `review_nonce`: Security nonce
- **Returns**: JSON success/error

#### 3. `submit_paket_review`
- **Action**: `wp_ajax_submit_paket_review` & `wp_ajax_nopriv_submit_paket_review`
- **Purpose**: Submit review untuk Paket Usaha
- **Parameters**:
  - `paket_id`: Paket ID (bisa dummy_)
  - `reviewer_name`: Nama reviewer
  - `reviewer_email`: Email reviewer
  - `rating`: Rating (1-5)
  - `review_content`: Isi review
  - `is_dummy`: `1` atau `0`
  - `review_nonce`: Security nonce
- **Returns**: JSON success/error

### Security

Semua AJAX handlers menggunakan:
- **Nonce verification**: `wp_verify_nonce()`
- **Capability checks**: `current_user_can()`
- **Data sanitization**: `sanitize_text_field()`, `absint()`, dll

---

## 📝 Form Handlers

**File**: `inc/forms/form-handlers.php`

### Available Form Handlers

#### 1. Tambah Proyek Form
- **Page**: `page-tambah-proyek.php`
- **Action**: Form submission via POST
- **Fields**:
  - Nama Pelanggan
  - Lokasi
  - Deskripsi
  - Gallery (file upload)
- **Process**: Creates new `proyek_pelanggan` post
- **Security**: Nonce verification, sanitization

---

## ⚙️ WordPress Customizer

**File**: `inc/customizer/customizer.php`

### Customizer Sections

#### 1. Identitas Situs
- **Section**: `inviro_identity`
- **Settings**:
  - Logo
  - Primary Color
  - Secondary Color
  - Primary Light Color
  - Primary Medium Color

#### 2. Homepage Settings
- **Section**: `inviro_homepage`
- **Settings**:
  - Hero Title
  - Hero Subtitle
  - Hero Button Text
  - Hero Button Link

#### 3. Profil Settings
- **Section**: `inviro_profil`
- **Settings**: Profil page content

#### 4. Paket Usaha Settings
- **Section**: `inviro_paket_usaha`
- **Settings**: Paket Usaha page settings

#### 5. Pelanggan Settings
- **Section**: `inviro_pelanggan`
- **Settings**: Pelanggan page settings

#### 6. Spareparts Settings
- **Section**: `inviro_spareparts`
- **Settings**: Spareparts page settings

#### 7. Paket Usaha Settings (Detail)
- **Section**: `inviro_paket_usaha`
- **Settings**: Paket Usaha detail settings

#### 8. Artikel Settings
- **Section**: `inviro_artikel`
- **Settings**: Artikel page settings

#### 9. Contact Settings
- **Section**: `inviro_contact`
- **Settings**:
  - WhatsApp Number
  - Email
  - Phone
  - Address

### Customizer Functions

- `inviro_customize_register()`: Register all settings
- `inviro_output_custom_colors()`: Output CSS variables
- `inviro_customize_preview_js()`: Live preview JavaScript
- `inviro_add_schema()`: Schema.org markup

---

## 🪝 Hooks & Filters

**File**: `inc/hooks/hooks.php`

### Actions

#### `wp_head`
- `inviro_track_paket_views()`: Track page views untuk Paket Usaha
- `inviro_add_schema()`: Add schema.org markup

#### `admin_init`
- `inviro_create_dummy_layanan()`: Create dummy layanan data (development)

#### `wp_enqueue_scripts`
- Enqueue styles & scripts (via `inc/enqueue/enqueue.php`)

### Filters

#### `wp_get_attachment_image_attributes`
- `inviro_add_lazy_loading()`: Add lazy loading to images

#### `wp_calculate_image_sizes`
- `inviro_responsive_image_sizes()`: Add responsive image sizes

#### `template_include`
- `inviro_force_spareparts_template()`: Force template untuk specific pages
- Force `single-paket-usaha.php` untuk single paket_usaha posts

#### `pre_get_posts`
- `inviro_prioritize_spareparts_page()`: Prioritize spareparts page query
- `inviro_ensure_spareparts_page_query()`: Ensure correct query untuk spareparts page

### Query Modifications

Theme menggunakan beberapa query modifications untuk:
- Handle dummy data display
- Prioritize specific pages
- Custom archive queries

---

## 💾 Database Schema

### Post Meta Keys Reference

#### Products
- `_product_link`
- `_product_icon`

#### Paket Usaha
- `_paket_price`
- `_paket_original_price`
- `_paket_sku`
- `_paket_promo`
- `_paket_description`
- `_paket_gallery` (comma-separated IDs)
- `_paket_specifications` (JSON)
- `_paket_bonus` (JSON)
- `_paket_views` (view counter)

#### Spareparts
- `_sparepart_price`
- `_sparepart_original_price`
- `_sparepart_stock`
- `_sparepart_sku`
- `_sparepart_promo`
- `_sparepart_gallery` (comma-separated IDs)
- `_sparepart_specifications` (JSON)

#### Proyek Pelanggan
- `_pelanggan_name`
- `_pelanggan_location`
- `_pelanggan_date`
- `_pelanggan_gallery` (comma-separated IDs)

#### Testimonials
- `_testimonial_name`
- `_testimonial_position`
- `_testimonial_rating`

#### Branches
- `_branch_address`
- `_branch_phone`
- `_branch_email`
- `_branch_maps_url`

#### Layanan
- `_layanan_icon`
- `_layanan_link`

#### Unduhan
- `_unduhan_file` (attachment ID)
- `_unduhan_size`
- `_unduhan_type`

#### Reviews (Sparepart & Paket Usaha)
- `_review_sparepart_id` (ID produk, bisa dummy_)
- `_reviewer_name`
- `_reviewer_email`
- `_review_rating`
- `_review_status` (`approved` atau `pending`)
- `_review_is_dummy` (`1` atau `0`)
- `_review_product_type` (`spareparts` untuk sparepart reviews)

---

## 🚀 Setup & Installation

### Requirements
- WordPress 5.0+
- PHP 7.4+
- MySQL 5.6+
- Apache/Nginx dengan mod_rewrite

### Installation Steps

1. **Upload Theme**
   ```bash
   # Upload folder wp-inviro-theme ke:
   wp-content/themes/wp-inviro-theme/
   ```

2. **Activate Theme**
   - Go to WordPress Admin → Appearance → Themes
   - Activate "INVIRO WP Theme"

3. **Flush Rewrite Rules**
   - Go to Settings → Permalinks
   - Click "Save Changes" (tanpa mengubah apapun)

4. **Configure Customizer**
   - Go to Appearance → Customize
   - Configure:
     - Identitas Situs (Logo, Colors)
     - Homepage Settings
     - Contact Settings (WhatsApp, Email, dll)
     - Page-specific settings

5. **Create Required Pages**
   Create pages dengan template berikut:
   - **Profil**: Template "Profil"
   - **Paket Usaha**: Template "Paket Usaha"
   - **Pelanggan**: Template "Pelanggan"
   - **Spare Parts**: Template "Spare Parts"
   - **Artikel**: Template "Artikel"
   - **Unduhan**: Template "Unduhan"

6. **Set Homepage**
   - Go to Settings → Reading
   - Set "Homepage displays" to "A static page"
   - Select your homepage

7. **Import Dummy Data (Optional)**
   - Dummy data tersedia di folder `dummy-data/`
   - Lihat `dummy-data/README.md` untuk instruksi

### Post-Installation Checklist

- [ ] Theme activated
- [ ] Rewrite rules flushed
- [ ] Customizer configured
- [ ] Required pages created
- [ ] Homepage set
- [ ] Menu created & assigned
- [ ] Widgets configured (if any)
- [ ] Permalinks working
- [ ] All CPTs visible in admin
- [ ] Test form submissions
- [ ] Test AJAX functionality

---

## 👨‍💻 Development Guidelines

### Code Standards

#### PHP
- Follow WordPress Coding Standards
- Use `inviro_` prefix untuk semua functions
- Always use `ABSPATH` check
- Sanitize all inputs
- Escape all outputs

#### CSS
- Use BEM-like naming convention
- Use CSS variables untuk colors
- Mobile-first approach
- Comment complex sections

#### JavaScript
- Use jQuery (WordPress included)
- Namespace functions
- Comment complex logic
- Handle errors gracefully

### File Organization

#### Adding New Custom Post Type

1. **Create Post Type File**
   ```
   inc/post-types/your-post-type.php
   ```
   - Register post type dengan `inviro_register_your_post_type()`
   - Hook ke `init` action

2. **Create Meta Box File**
   ```
   inc/meta-boxes/your-post-type.php
   ```
   - Add meta boxes
   - Save meta data

3. **Load in Loader**
   ```php
   // inc/loader.php
   require_once get_template_directory() . '/inc/post-types/your-post-type.php';
   require_once get_template_directory() . '/inc/meta-boxes/your-post-type.php';
   ```

4. **Create Templates (if needed)**
   - `single-your-post-type.php`
   - `archive-your-post-type.php`
   - `page-your-post-type.php` (if needed)

#### Adding New Meta Field

1. **Add Meta Box** (in `inc/meta-boxes/your-post-type.php`)
   ```php
   function inviro_add_your_meta_box() {
       add_meta_box(
           'your-meta-box',
           'Your Meta Box Title',
           'inviro_your_meta_box_callback',
           'your_post_type'
       );
   }
   add_action('add_meta_boxes', 'inviro_add_your_meta_box');
   ```

2. **Save Meta Data**
   ```php
   function inviro_save_your_meta($post_id) {
       if (isset($_POST['your_field'])) {
           update_post_meta($post_id, '_your_field', sanitize_text_field($_POST['your_field']));
       }
   }
   add_action('save_post', 'inviro_save_your_meta');
   ```

3. **Display in Template**
   ```php
   $value = get_post_meta(get_the_ID(), '_your_field', true);
   echo esc_html($value);
   ```

### Debugging

#### Enable WordPress Debug
```php
// wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

#### Check Template Being Used
Add to template file:
```php
<?php if (current_user_can('administrator')) : ?>
    <!-- Template: single-paket-usaha.php -->
<?php endif; ?>
```

#### Check Meta Data
```php
<?php
$post_id = get_the_ID();
$all_meta = get_post_meta($post_id);
print_r($all_meta);
?>
```

### Common Tasks

#### Flush Rewrite Rules Programmatically
```php
flush_rewrite_rules();
```

#### Get Custom Post Type Posts
```php
$args = array(
    'post_type' => 'paket_usaha',
    'posts_per_page' => -1,
    'post_status' => 'publish'
);
$query = new WP_Query($args);
```

#### Get Meta Data
```php
$price = get_post_meta($post_id, '_paket_price', true);
```

#### Update Meta Data
```php
update_post_meta($post_id, '_paket_price', 1000000);
```

---

## 🔧 Troubleshooting

### Common Issues

#### 1. Template Not Loading
**Problem**: Custom template tidak digunakan

**Solutions**:
- Flush rewrite rules (Settings → Permalinks → Save)
- Check file name (harus exact match)
- Check template hierarchy
- Clear cache (browser & plugin)

#### 2. Meta Boxes Not Showing
**Problem**: Meta boxes tidak muncul di admin

**Solutions**:
- Check if post type registered correctly
- Check `add_meta_boxes` hook
- Check user capabilities
- Check screen context

#### 3. AJAX Not Working
**Problem**: AJAX requests failing

**Solutions**:
- Check nonce verification
- Check action name
- Check user capabilities
- Check JavaScript console for errors
- Verify `admin-ajax.php` URL

#### 4. Images Not Loading
**Problem**: Images tidak muncul

**Solutions**:
- Check file permissions
- Check uploads folder
- Check attachment IDs
- Verify image URLs

#### 5. Custom Post Type Not Showing
**Problem**: CPT tidak muncul di admin

**Solutions**:
- Check if registered (hook to `init`)
- Check `show_ui` setting
- Check user capabilities
- Flush rewrite rules

#### 6. Permalinks 404
**Problem**: Custom post type URLs return 404

**Solutions**:
- Flush rewrite rules
- Check `.htaccess` file
- Check permalink structure
- Verify rewrite rules

#### 7. CSS Not Loading
**Problem**: Styles tidak ter-apply

**Solutions**:
- Check enqueue logic
- Check file paths
- Clear browser cache
- Check conditional loading
- Verify file exists

#### 8. JavaScript Errors
**Problem**: JavaScript tidak bekerja

**Solutions**:
- Check browser console
- Verify jQuery loaded
- Check script dependencies
- Verify script enqueue
- Check for conflicts

### Debug Checklist

- [ ] WordPress debug mode enabled
- [ ] Error logs checked
- [ ] Browser console checked
- [ ] Network tab checked (for AJAX)
- [ ] File permissions correct
- [ ] Database connection OK
- [ ] PHP version compatible
- [ ] All plugins updated
- [ ] Theme files not corrupted

---

## 📖 API Reference

### Helper Functions

#### `inviro_get_feature_icon($feature_name)`
**File**: `inc/helpers/helpers.php`

Get icon untuk feature berdasarkan nama.

**Parameters**:
- `$feature_name` (string): Nama feature

**Returns**: Icon name (string)

**Example**:
```php
$icon = inviro_get_feature_icon('water-treatment');
// Returns: 'dashicons-admin-tools'
```

### Custom Functions

#### Post Views Counter

**Functions**:
- `inviro_set_paket_views($post_id)`: Set view count
- `inviro_get_paket_views($post_id)`: Get view count
- `inviro_track_paket_views()`: Track views (hook to `wp_head`)

**File**: `inc/hooks/hooks.php`

**Usage**:
```php
$views = inviro_get_paket_views(get_the_ID());
echo $views . ' views';
```

### Template Tags

#### Get Custom Field
```php
$price = get_post_meta(get_the_ID(), '_paket_price', true);
```

#### Get Taxonomy Terms
```php
$categories = get_the_terms(get_the_ID(), 'paket_usaha_category');
```

#### Get Featured Image
```php
$image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
```

#### Get Gallery Images
```php
$gallery_ids = get_post_meta(get_the_ID(), '_paket_gallery', true);
$gallery_ids = explode(',', $gallery_ids);
foreach ($gallery_ids as $img_id) {
    $img_url = wp_get_attachment_image_url($img_id, 'large');
    // Display image
}
```

#### Get Specifications (JSON)
```php
$specs_json = get_post_meta(get_the_ID(), '_paket_specifications', true);
$specifications = json_decode($specs_json, true);
foreach ($specifications as $spec) {
    echo $spec['label'] . ': ' . $spec['value'];
}
```

### WordPress Hooks Used

#### Actions
- `init`: Register post types, taxonomies
- `add_meta_boxes`: Add meta boxes
- `save_post`: Save meta data
- `wp_enqueue_scripts`: Enqueue assets
- `wp_head`: Add head elements
- `admin_init`: Admin initialization
- `pre_get_posts`: Modify queries

#### Filters
- `template_include`: Override templates
- `wp_get_attachment_image_attributes`: Modify image attributes
- `wp_calculate_image_sizes`: Modify image sizes
- `script_loader_tag`: Modify script tags

---

## 📞 Support & Contact

### Development Team
- **Theme**: INVIRO WP Theme
- **Version**: 1.0.1
- **Last Updated**: 2025

### Resources
- WordPress Codex: https://codex.wordpress.org/
- WordPress Developer Handbook: https://developer.wordpress.org/
- Theme Development: https://developer.wordpress.org/themes/

### Notes
- Theme ini menggunakan struktur modular untuk kemudahan maintenance
- Semua functions menggunakan prefix `inviro_` untuk avoid conflicts
- Dummy data tersedia untuk development/testing
- Theme fully responsive dan SEO-friendly

---

## 📝 Changelog

### Version 1.0.1
- Initial release
- 10+ Custom Post Types
- Custom Taxonomies
- Meta Boxes system
- AJAX handlers
- Review system
- WordPress Customizer integration
- Responsive design
- SEO optimization

---

## ✅ Final Checklist untuk Handover

Sebelum menyerahkan project ke developer lain, pastikan:

- [ ] Semua file ada dan tidak corrupted
- [ ] Database backup tersedia
- [ ] Documentation lengkap (file ini)
- [ ] Dummy data tersedia (jika perlu)
- [ ] Environment setup documented
- [ ] Customizer settings documented
- [ ] Known issues documented
- [ ] Future improvements noted
- [ ] Access credentials provided (jika perlu)
- [ ] Server requirements documented

---

**End of Documentation**

*Dokumentasi ini dibuat untuk memudahkan handover project. Jika ada pertanyaan atau perlu update, silakan edit file ini.*

