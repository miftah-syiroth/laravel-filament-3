# Command Cleanup File Storage

Command ini digunakan untuk membersihkan file yang tidak digunakan di storage disk public, khususnya file logo, favicon, dan avatar.

## Penggunaan

### Menjalankan Command
```bash
php artisan storage:cleanup-unused-files
```

### Dry Run (Preview)
Untuk melihat file apa saja yang akan dihapus tanpa benar-benar menghapusnya:
```bash
php artisan storage:cleanup-unused-files --dry-run
```

## Fitur

1. **Otomatis Mendeteksi File yang Digunakan**: Command akan mengambil data dari:
   - `WebsiteSetting` (logo dan favicon)
   - `AboutSetting` (avatar)

2. **Keamanan**: 
   - Meminta konfirmasi sebelum menghapus file
   - Opsi `--dry-run` untuk preview
   - Tidak menghapus file `.gitignore`

3. **Scheduling**: Command dijadwalkan untuk berjalan setiap minggu secara otomatis

## File yang Dibersihkan

- File logo yang tidak digunakan dari `WebsiteSetting`
- File favicon yang tidak digunakan dari `WebsiteSetting`  
- File avatar yang tidak digunakan dari `AboutSetting`

## Output

Command akan menampilkan:
- File yang sedang digunakan saat ini
- Jumlah file yang akan dihapus
- Daftar file yang akan dihapus
- Status penghapusan setiap file
- Total file yang berhasil dihapus 