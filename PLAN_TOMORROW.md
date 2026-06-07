# Plan Tomorrow - Business Risk Audit

Tanggal audit: 2026-06-07

## Temuan Risiko Tinggi

- Default admin masih hardcoded di `database/seeders/AdminSeeder.php`; jika `RUN_SEEDER=true`, deploy bisa membuat atau mereset admin ke kredensial default.
- Promo default aktif masih hardcoded di `database/seeders/PromoSeeder.php`; seeding dapat mengaktifkan ulang atau menimpa promo bisnis.
- Harga fallback `5000000` tersebar di backend, Blade, dan JavaScript, sehingga harga bisa salah saat `VillaPrice` belum dikonfigurasi atau berubah.
- `BlockedDate` sudah punya model/controller, tetapi belum dipakai oleh `getBookedDates()` dan `isDateAvailable()`, jadi tanggal maintenance/holiday masih bisa dipesan.
- File test payment masih berada di `public/`: `test-doku.php`, `test-checkout.php`, dan `test-time.php`; ini dapat diakses jika ikut ter-deploy.
- Route publik `/api/test-promo` masih aktif dan mengekspos sample promo.

## Temuan Risiko Sedang

- Edit profile admin hanya menyimpan nama/email ke session, bukan ke tabel `admins`; field password juga belum diproses.
- Payment controller fallback ke DOKU sandbox jika env tidak lengkap; di production sebaiknya gagal eksplisit.
- `.env.example` masih memakai default local/debug/sandbox dan `MAIL_MAILER=log`, rawan dipakai mentah saat deploy.
- Manual booking tanpa email memakai `manual@swarnamandapa.com`, sehingga data kontak dan email konfirmasi bisa tidak akurat.
- Status booking dan payment method masih berupa string literal tersebar di controller, route, dan view.

## Rencana Perbaikan

- Amankan `AdminSeeder`: ambil admin awal dari env/setup command, jangan reset password admin existing.
- Hentikan promo seed otomatis di produksi; kelola promo melalui admin panel sebagai source of truth.
- Jadikan `VillaPrice` satu-satunya sumber harga; jika tidak ada harga aktif, tampilkan error konfigurasi alih-alih fallback angka.
- Integrasikan `BlockedDate` ke API unavailable dates, calendar admin, dan validasi booking/manual booking.
- Hapus file test dari `public/` atau pindahkan ke command/debug route yang hanya aktif di local.
- Hapus atau proteksi `/api/test-promo` dengan `app.debug` dan/atau middleware admin.
- Benahi edit profile agar update record admin di database dan mendukung password opsional.
- Fail-fast konfigurasi DOKU di production jika base URL, client id, atau secret kosong/tidak valid.
- Update `.env.example` ke default aman dan dokumentasikan sandbox vs production.

## Test Plan

- Jalankan `composer test` setelah perubahan.
- Tambah test untuk admin seeder agar tidak reset password existing.
- Tambah test agar promo seed tidak override data produksi atau tidak dipanggil di production.
- Tambah test booking ditolak saat tanggal masuk `BlockedDate`.
- Tambah test harga booking mengikuti `VillaPrice` dan gagal jelas saat harga aktif tidak ada.
- Tambah test payment config gagal eksplisit di production saat credential DOKU kosong.
- Smoke test manual: booking publik, apply promo, manual booking, edit profile admin, calendar blocked date, dan DOKU sandbox checkout.

## Catatan

- Jangan commit `.env`, credential DOKU, generated PDF, log, atau file debug lokal.
- Saat implementasi, hindari refactor besar di luar proses bisnis booking, promo, admin, payment, dan availability.
- JANGAN commit ke github biar sama saya saja. 