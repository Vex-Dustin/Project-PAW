# Project-PAW

# SetUp Menjalankan Aplikasi SongketMart
1. Jalankan composer install
2. copy file ".env.example" dan rename menjadi ".env"
3. Jalankan php artisan key:generate (untuk membuat key app otomatis)
4. Jalankan php artisan migrate:fresh --seed (untuk menjalankan migrations)
5. Jalankan php artisan storage:link (untuk menampilkan gambar yang diupload di SongketMart)
6. Jalankan php artisan serve 

# Mengelola Database SongketMart di MySQL
1. Pada file ".env" ubah DB_CONNECTION menjadi "mysql".
2. Aktifkan : 
DB_HOST=127.0.0.1 
DB_PORT=3306 
DB_DATABASE= db_SongketMart 
DB_USERNAME=root 
DB_PASSWORD=
3. Jika MySQL yang digunakan perangkat memiliki password, masukkan password yang sesuai agar berhasil mengakses MySQL di perangkat yang digunakan.
4. Jalankan MySQL melalui XAMPP/Laragon
5. Buka MySQL Workbench
6. Buatlah sebuah connection baru atau buka connection yang sudah ada
7. Gunakan database dengan menjalankan perintah "use db_SongketMart"
8. Gunakan berbagai perintah pada MySQL untuk menampilkan data yang diinginkan. 
contoh:
- show tables -- untuk menampilkan semua tabel
- select* from nama_tabel -- untuk menampilkan data pada tabel tertentu
- dsb.

catatan: nama database dapat dibuat berbeda asalkan sesuai dengan yang digunakan di MySQL dan di file ".env"