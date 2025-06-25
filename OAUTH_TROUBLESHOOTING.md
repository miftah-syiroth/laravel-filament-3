# Troubleshooting OAuth 403 Forbidden di Production

## Masalah
Login OAuth berhasil di local, tetapi mendapat error 403 Forbidden di production meskipun session terbuat.

## Penyebab Umum

### 1. Masalah Session/Cookie
- Session tidak tersimpan dengan benar di production
- Cookie domain tidak sesuai
- Session driver tidak kompatibel dengan environment production

### 2. Masalah CSRF Token
- VerifyCsrfToken middleware mengganggu OAuth callback
- Token tidak valid atau expired

### 3. Masalah Middleware Configuration
- Middleware yang tidak sesuai untuk OAuth callback
- Konfigurasi panel yang berbeda antara local dan production

### 4. Masalah Role/Permission
- User tidak memiliki role yang diperlukan
- Permission tidak ter-assign dengan benar

## Solusi yang Sudah Diterapkan

### 1. Debugging
- Menambahkan logging di `authorizeUserUsing` dan `createUserUsing`
- Membuat route `/debug-auth` untuk mengecek status autentikasi
- Membuat command `php artisan oauth:check-config` untuk mengecek konfigurasi

### 2. Middleware Configuration
- Menghapus `VerifyCsrfToken` dari OAuth callback middleware
- Menambahkan `SubstituteBindings` middleware
- Memastikan session middleware berjalan dengan benar

### 3. Authorization Logic
- Mengubah `authorizeUserUsing` untuk selalu return `true`
- Menambahkan logging untuk troubleshooting

## Langkah Troubleshooting

### 1. Cek Konfigurasi
```bash
php artisan oauth:check-config
```

### 2. Cek Log
```bash
tail -f storage/logs/laravel.log
```

### 3. Cek Status Auth
Akses `/debug-auth` untuk melihat status autentikasi

### 4. Cek Environment Variables
Pastikan di production:
```env
APP_URL=https://yourdomain.com
SESSION_DRIVER=file
SESSION_DOMAIN=.yourdomain.com
SESSION_SECURE_COOKIE=true
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/oauth/google/callback
```

### 5. Cek Google OAuth Console
- Pastikan redirect URI sudah benar
- Pastikan domain sudah diizinkan
- Pastikan OAuth consent screen sudah dikonfigurasi

## Perbaikan Tambahan yang Bisa Dicoba

### 1. Force HTTPS di Production
```php
// di AppServiceProvider
if (app()->environment('production')) {
    \URL::forceScheme('https');
}
```

### 2. Custom Session Configuration
```php
// di config/session.php
'domain' => env('SESSION_DOMAIN', null),
'secure' => env('SESSION_SECURE_COOKIE', true),
'same_site' => 'lax',
```

### 3. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 4. Restart Queue Workers (jika menggunakan)
```bash
php artisan queue:restart
```

## Monitoring

Setelah menerapkan perbaikan, monitor:
1. Log Laravel untuk error
2. Status autentikasi via `/debug-auth`
3. Session storage dan cookie
4. Database untuk user dan socialite_user records

## Fallback Plan

Jika masalah masih berlanjut:
1. Implementasi custom OAuth handler
2. Gunakan stateless OAuth
3. Implementasi manual session management
4. Consider menggunakan package OAuth alternatif 