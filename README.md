# 🧺 Cling Laundry - Sistem Manajemen Laundry Online

Website manajemen laundry berbasis web yang dirancang untuk memudahkan pelanggan melakukan pemesanan dan membantu admin mengelola data pesanan secara efisien.

---

## 🚀 Fitur Utama
* **Pemesanan Online**: Formulir pemesanan jasa laundry yang terintegrasi dengan database.
* **Dashboard Admin**: Kelola data pelanggan (Tambah, Edit, Hapus) secara real-time.
* **Notifikasi PHP**: Sistem pesan sukses/error menggunakan PHP Session tanpa JavaScript.
* **Responsive Design**: Tampilan optimal di berbagai perangkat (HP, Tablet, Desktop).
* **FAQ & Lokasi**: Informasi lengkap mengenai layanan dan peta lokasi outlet.

---

## 🛠️ Teknologi yang Digunakan
* **Frontend**: HTML5, CSS3 (Custom Design)
* **Backend**: PHP (Server-side Programming)
* **Database**: MySQL
* **Hosting**: InfinityFree (Target Deployment)

---

## 📂 Struktur Folder Proyek
```text
htdocs/
├── index.php             # Halaman Beranda & Notifikasi
├── Admin.php             # Dashboard Admin utama
├── DataPesananAdmin.php   # Tabel kelola pesanan
├── Login.php             # Sistem autentikasi Admin
├── pemesanan.php         # Formulir input pelanggan
├── proses_pesanan.php     # Logika pengolah data ke MySQL
├── koneksi.php           # Konfigurasi database
├── Style.css             # Pusat desain website
└── LokasiSaya.html       # Halaman peta lokasi