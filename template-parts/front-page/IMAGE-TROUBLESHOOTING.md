# Troubleshooting Gambar Produk yang Tidak Muncul

## Langkah-langkah untuk Memastikan Gambar Muncul:

### 1. Pastikan Produk Memiliki Featured Image
- Buka WordPress Admin → Products (Produk)
- Edit produk yang ingin ditampilkan
- Di sidebar kanan, cari "Featured Image"
- Upload dan set featured image untuk setiap produk
- Klik "Set featured image" dan pilih gambar
- Simpan produk

### 2. Periksa Image Size di WordPress
- Buka Settings → Media
- Pastikan ukuran gambar sudah di-set:
  - Thumbnail size: 150x150
  - Medium size: 300x300
  - Large size: 1024x1024
- Setelah mengubah ukuran, regenerate thumbnails menggunakan plugin seperti "Regenerate Thumbnails"

### 3. Periksa URL Gambar
Jika gambar masih tidak muncul, cek:
- Apakah URL gambar benar di browser (inspect element)
- Apakah gambar ada di folder wp-content/uploads
- Apakah permission file gambar benar

### 4. Clear Cache
- Clear browser cache (Ctrl+F5)
- Clear WordPress cache jika menggunakan plugin cache
- Clear server cache jika ada

## Kode yang Digunakan:

Template menggunakan:
- `has_post_thumbnail($product_id)` - cek apakah ada featured image
- `inviro_get_product_image_url($product_id, 'medium')` - ambil URL gambar
- `wp_get_attachment_image()` - fallback jika URL tidak tersedia

## Debug Mode:

Jika masih tidak muncul, tambahkan kode debug di template-parts/front-page/products-section.php:

```php
// Debug - hapus setelah selesai
if (current_user_can('manage_options')) {
    echo '<!-- DEBUG Product ID: ' . $product_id . ' -->';
    echo '<!-- DEBUG Has Thumbnail: ' . (has_post_thumbnail($product_id) ? 'YES' : 'NO') . ' -->';
    echo '<!-- DEBUG Thumbnail ID: ' . get_post_thumbnail_id($product_id) . ' -->';
    echo '<!-- DEBUG Image URL: ' . $image_url . ' -->';
}
```

