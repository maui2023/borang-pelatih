
# 📄 PRD.md – Sistem Pendaftaran Pelatih Latihan Industri

## 🧾 Ringkasan Projek

Sistem ini dibangunkan untuk memudahkan proses **pendaftaran pelatih latihan industri**, membolehkan admin:

* Menjana pautan pendaftaran dengan token unik.
* Menghantar link ke WhatsApp pelatih.
* Pelatih mengisi maklumat.
* Admin meluluskan penyertaan.
* Surat tawaran dijana dalam bentuk PDF dan dihantar semula ke pelatih.

---

## 🎯 Objektif

* Mengurus proses pendaftaran pelatih dengan mudah dan selamat.
* Memastikan hanya pelatih yang sah menerima tawaran.
* Memudahkan komunikasi dan penghantaran dokumen melalui WhatsApp.

---

## 👤 Jenis Pengguna

### 1. **Admin**

* Login menggunakan username & password.
* Boleh cipta token dan hantar link ke WhatsApp pelatih.
* Melihat senarai permohonan yang telah dihantar.
* Meluluskan atau menolak permohonan.
* Menjana surat tawaran dalam bentuk PDF selepas permohonan diluluskan.

### 2. **Pelatih**

* Mengisi borang melalui link dengan token.
* Token hanya sah sekali dan akan tamat selepas digunakan.
* Tidak boleh isi borang jika token sudah digunakan atau tamat tempoh.

---

## 🧩 Fungsi Utama

### 🔐 Admin Login

* 1 akaun sahaja.
* Username dan password disimpan dengan hash.

### 🧾 Generate Link dengan Token

* Admin perlu masukkan nama pelatih dan nombor telefon.
* Sistem akan cipta token rawak (panjang 32 aksara).
* Token hanya boleh digunakan sekali.
* Pautan akan dihantar ke WhatsApp menggunakan API pihak ketiga (contoh: Twilio API / WhatsApp Business API).

### 📄 Borang Pendaftaran Pelatih

Pelatih akan isi maklumat berikut:

* Nama Penuh
* No. Kad Pengenalan
* No. Pelatih
* Nama Institusi
* Program/Kursus
* Mula Tempoh Latihan
* Akhir Tempoh Latihan
* Tempoh Latihan Industri (Auto calculate)
* Alamat Rumah
* No Akaun
* Nama Bank

> Tajuk borang boleh diubah oleh admin (dynamic).

### 📝 Proses Approval

* Admin menyemak maklumat pelatih.
* Jika diluluskan, sistem akan:

  * Jana surat tawaran dalam bentuk PDF (menggunakan template surat yang diberikan).
  * Hantar link surat melalui WhatsApp.
  * Default password PDF adalah nombor telefon pelatih.

---

## 🗄️ Struktur Database (MySQL)

### Table: `admins`

| Field          | Type         |
| -------------- | ------------ |
| id             | INT (PK)     |
| username       | VARCHAR(50)  |
| password\_hash | VARCHAR(255) |

---

### Table: `tokens`

| Field         | Type         |
| ------------- | ------------ |
| id            | INT (PK)     |
| token         | VARCHAR(64)  |
| phone\_number | VARCHAR(20)  |
| name          | VARCHAR(100) |
| is\_used      | BOOLEAN      |
| created\_at   | DATETIME     |

---

### Table: `registrations`

| Field                 | Type                                    |
| --------------------- | --------------------------------------- |
| id                    | INT (PK)                                |
| token\_id             | INT (FK)                                |
| name                  | VARCHAR(100)                            |
| ic\_number            | VARCHAR(20)                             |
| trainee\_number       | VARCHAR(20)                             |
| institution\_name     | VARCHAR(150)                            |
| course\_program       | VARCHAR(150)                            |
| internship\_start     | DATE                                    |
| internship\_end       | DATE                                    |
| address               | TEXT                                    |
| bank\_account\_number | VARCHAR(30)                             |
| bank\_name            | VARCHAR(50)                             |
| status                | ENUM('Pending', 'Approved', 'Rejected') |
| pdf\_url              | VARCHAR(255)                            |
| submitted\_at         | DATETIME                                |

---

## 🔧 Teknologi Digunakan

* **PHP 8.4**
* **MySQL**
* **Bootstrap 5.3**
* **PDF Generator:** Dompdf / TCPDF
* **WhatsApp API:** Twilio WhatsApp API (atau integrasi lain yang sesuai)

---

## 💻 Aliran Sistem (Flow)

### Admin:

1. Login
2. Masukkan nama pelatih dan nombor telefon
3. Sistem cipta token dan link
4. Link dihantar ke WhatsApp pelatih

### Pelatih:

1. Klik link dari WhatsApp
2. Isi borang lengkap
3. Hantar
4. Status: "Pending"

### Admin:

1. Semak permohonan
2. Klik "Approve"
3. Sistem jana PDF surat tawaran
4. Hantar link PDF ke WhatsApp
5. Status: "Approved"

---

## 📂 Fail & Folder Structure (Ringkas)

```
/internship-system/
├── /admin/
│   ├── login.php
│   ├── dashboard.php
│   ├── generate_token.php
│   └── approve.php
├── /pelatih/
│   ├── register.php
│   └── success.php
├── /includes/
│   ├── db.php
│   ├── functions.php
├── /pdf/
│   └── surat_generator.php
├── /assets/
│   ├── /css/
│   └── /js/
├── .env
├── index.php
```

---

## 📅 Timeline Cadangan

| Minggu | Tugas                                            |
| ------ | ------------------------------------------------ |
| 1      | Setup DB, struktur projek, login admin           |
| 2      | Fungsi generate token, hantar link WhatsApp      |
| 3      | Form pelatih + simpan ke DB                      |
| 4      | Approval + PDF Generator + hantar PDF ke pelatih |

---

* Form PHP untuk pelatih
* Fungsi generate token
* Template surat dalam PDF (dengan DomPDF)
* Skrip WhatsApp hantar link (Guna wa.me)
