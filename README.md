# 📘 Sistem Pendaftaran Pelatih Latihan Industri

Sistem ini dibina menggunakan **PHP 8.4**, **MySQL**, dan **Bootstrap 5.3** untuk memudahkan urusan pendaftaran pelatih Latihan Industri, termasuk pengesahan maklumat, kelulusan oleh admin, dan penjanaan surat tawaran dalam format PDF.

---

## 🧩 Fungsi Utama

### ✅ Untuk Admin

* Login sebagai admin (satu akaun sahaja).
* Jana link pendaftaran dengan token unik.
* Hantar link terus ke nombor WhatsApp pelatih.
* Lihat semua permohonan yang diterima.
* Luluskan permohonan.
* Sistem jana surat tawaran PDF secara automatik.
* Hantar link surat tawaran ke WhatsApp pelatih.

### 📝 Untuk Pelatih

* Terima link khas melalui WhatsApp.
* Isi borang lengkap berdasarkan token yang diberikan.
* Link hanya boleh digunakan sekali sahaja.
* Tunggu pengesahan oleh admin.

---

## 🔐 Ciri-Ciri Keselamatan

* Link pendaftaran hanya boleh digunakan sekali.
* Surat tawaran PDF dilindungi dengan kata laluan (default: nombor telefon pelatih).
* Akses admin dilindungi dengan hash password.

---

## 🧱 Teknologi Digunakan

* PHP 8.4
* MySQL
* Bootstrap 5.3
* DomPDF (untuk PDF Generator)
* WhatsApp API (contoh: Twilio, UltraMsg)

---

## 🗃️ Struktur Direktori

```
/internship-system/
├── admin/                # Halaman untuk admin (login, dashboard, kelulusan)
├── pelatih/              # Borang pendaftaran untuk pelatih
├── pdf/                  # Fail template surat tawaran dan generator
├── includes/             # Sambungan database dan fungsi bersama
├── assets/               # CSS & JS (Bootstrap 5.3)
├── .env                  # Fail konfigurasi sensitif
└── index.php             # Halaman utama (redirect login/admin)
```

---

## 🚀 Aliran Sistem

1. Admin login ke dashboard.
2. Masukkan nama & nombor telefon pelatih.
3. Sistem cipta token dan link khas.
4. Link dihantar ke WhatsApp pelatih.
5. Pelatih isi borang pendaftaran.
6. Admin semak dan luluskan.
7. Sistem jana PDF surat tawaran.
8. Link PDF dihantar ke WhatsApp pelatih.

---

## ⚙️ Cara Pasang (Installation)

1. **Clone repositori:**

   ```bash
   git clone https://github.com/nama-anda/internship-system.git
   cd internship-system
   ```

2. **Setup `.env`**

   ```env
   DB_HOST=localhost
   DB_NAME=internship_db
   DB_USER=root
   DB_PASS=
   WHATSAPP_API_KEY=your_api_key_here
   ```

3. **Import database:**

   * Import `internship_db.sql` ke dalam MySQL.

4. **Setup virtual host / localhost**

   * Letakkan dalam htdocs (jika pakai XAMPP/Laragon)

5. **Sedia untuk digunakan**

---

## 📄 Lisensi

Sistem ini dibangunkan untuk tujuan dalaman organisasi dan boleh diubah suai mengikut keperluan.

---