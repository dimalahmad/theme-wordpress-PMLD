# 🐳 Docker Setup - INVIRO WordPress Theme

Setup Docker yang sangat simpel untuk menjalankan WordPress dengan real-time changes.

## 📋 Requirements

- **Docker Desktop** (Windows/Mac) - **HARUS DIPASTIKAN RUNNING!**
- Git (optional)

### ⚠️ Important: Docker Desktop harus running!

Sebelum menjalankan `docker-compose up -d`, pastikan:
- ✅ Docker Desktop sudah di-install
- ✅ Docker Desktop sudah di-start
- ✅ Status menunjukkan "Docker Desktop is running"
- ✅ Icon Docker di system tray hijau

## 🚀 Quick Start

### 1. Setup Environment (Optional)

Copy file `.env.example` ke `.env` dan sesuaikan jika perlu:

```bash
cp .env.example .env
```

**Default values sudah cukup untuk development!**

### 2. Jalankan Docker

```bash
docker-compose up -d
```

### 3. Akses WordPress

- **WordPress Site**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081

### 4. Setup WordPress

1. Buka http://localhost:8080
2. Pilih bahasa
3. Isi informasi:
   - **Site Title**: INVIRO
   - **Username**: admin (atau sesuai keinginan)
   - **Password**: (buat password yang kuat)
   - **Email**: your-email@example.com

### 5. Aktifkan Theme

1. Login ke WordPress Admin: http://localhost:8080/wp-admin
2. Go to **Appearance → Themes**
3. Aktifkan **INVIRO WP Theme**

### 6. Flush Rewrite Rules

1. Go to **Settings → Permalinks**
2. Click **Save Changes** (tanpa mengubah apapun)

## ✨ Real-Time Changes

**Theme folder sudah di-mount ke container**, jadi semua perubahan file PHP, CSS, JS akan langsung terlihat tanpa perlu rebuild!

Cukup:
1. Edit file di folder theme
2. Refresh browser
3. Perubahan langsung terlihat! 🎉

## 🛠️ Commands

### Start containers
```bash
docker-compose up -d
```

### Stop containers
```bash
docker-compose down
```

### Stop & Remove volumes (HAPUS DATA!)
```bash
docker-compose down -v
```

### View logs
```bash
docker-compose logs -f
```

### View WordPress logs only
```bash
docker-compose logs -f wordpress
```

### Restart containers
```bash
docker-compose restart
```

### Access WordPress container shell
```bash
docker exec -it inviro_wordpress bash
```

### Access MySQL container shell
```bash
docker exec -it inviro_db bash
```

## 📁 Volume Mounting

Theme folder di-mount sebagai volume:
```
./ (current directory) → /var/www/html/wp-content/themes/wp-inviro-theme
```

Ini berarti:
- ✅ Semua perubahan file langsung terlihat
- ✅ Tidak perlu rebuild container
- ✅ Tidak perlu copy files manual

## 🔧 Troubleshooting

### Docker Desktop tidak running

**Error**: `The system cannot find the file specified` atau `dockerDesktopLinuxEngine`

**Solution**:
1. **Start Docker Desktop**:
   - Buka Docker Desktop dari Start Menu
   - Tunggu sampai status "Docker Desktop is running"
   - Icon Docker di system tray harus hijau

2. **Verify Docker running**:
   ```bash
   docker --version
   docker ps
   ```

3. **Jika masih error, restart Docker Desktop**:
   - Right-click icon Docker di system tray
   - Pilih "Restart Docker Desktop"
   - Tunggu sampai fully started

4. **Cek Windows WSL2** (jika menggunakan WSL2):
   ```bash
   wsl --status
   ```

### Port sudah digunakan

Jika port 8080 atau 8081 sudah digunakan, edit `docker-compose.yml`:

```yaml
ports:
  - "8080:80"  # Ganti 8080 ke port lain, misal 8082
```

### Database connection error

1. Pastikan container `db` running:
   ```bash
   docker-compose ps
   ```

2. Restart containers:
   ```bash
   docker-compose restart
   ```

### Permission issues (Linux/Mac)

Jika ada permission issues, jalankan:

```bash
sudo chown -R www-data:www-data .
```

Atau di dalam container:
```bash
docker exec -it inviro_wordpress chown -R www-data:www-data /var/www/html/wp-content/themes/wp-inviro-theme
```

### Theme tidak muncul

1. Pastikan theme folder di-mount dengan benar
2. Check di container:
   ```bash
   docker exec -it inviro_wordpress ls -la /var/www/html/wp-content/themes/
   ```

3. Pastikan `style.css` ada di root theme folder

### Clear cache

WordPress cache bisa di-clear via:
1. Admin → Settings → Permalinks → Save
2. Atau install plugin cache cleaner

## 📊 Services

### WordPress
- **URL**: http://localhost:8080
- **Container**: inviro_wordpress
- **Port**: 8080

### MySQL
- **Host**: localhost:3306
- **Container**: inviro_db
- **Database**: inviro_db (default)
- **User**: wordpress (default)
- **Password**: wordpress (default)

### phpMyAdmin
- **URL**: http://localhost:8081
- **Container**: inviro_phpmyadmin
- **Login**: wordpress / wordpress

## 🔐 Security Notes

⚠️ **Untuk Development Only!**

Setup ini menggunakan default passwords yang **TIDAK AMAN** untuk production:
- Database password: `wordpress`
- Root password: `rootpassword`

**JANGAN gunakan di production!**

## 📝 Next Steps

Setelah Docker running:

1. ✅ Aktifkan theme
2. ✅ Flush rewrite rules
3. ✅ Configure Customizer
4. ✅ Create required pages
5. ✅ Import dummy data (optional)

Lihat `DOCUMENTATION.md` untuk setup lengkap.

## 🎯 Tips

- Gunakan **VS Code** atau editor dengan **live reload** untuk development lebih cepat
- Install **WordPress Debug Bar** plugin untuk debugging
- Gunakan **Browser DevTools** untuk inspect changes
- Check **Docker logs** jika ada error

---

**Happy Coding! 🚀**

