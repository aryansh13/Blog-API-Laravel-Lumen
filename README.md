# Blog API with Laravel Lumen
Sebuah REST API untuk blog sederhana yang dibangun menggunakan framework Laravel Lumen. API ini menyediakan endpoint untuk mengelola artikel blog (posts) dan komentar (comments) dengan mengimplementasikan prinsip-prinsip RESTful.

## Fitur
API ini memiliki dua fitur utama:

- **Post Management**: Endpoint untuk membuat, membaca, mengupdate, dan menghapus artikel blog.
- **Comment Management**: Endpoint untuk membuat, membaca, mengupdate, dan menghapus komentar pada artikel blog.

## Endpoint API
### Posts Endpoints
| Method | URL            | Deskripsi                                      |
|--------|--------------|--------------------------------|
| POST   | `/posts`      | Membuat artikel blog baru    |
| GET    | `/posts`      | Mendapatkan daftar semua artikel blog dengan pagination |
| GET    | `/posts/{id}` | Mendapatkan detail satu artikel blog berdasarkan ID |
| PATCH  | `/posts/{id}` | Mengupdate artikel blog berdasarkan ID |
| DELETE | `/posts/{id}` | Menghapus artikel blog berdasarkan ID |

### Comments Endpoints
| Method | URL                       | Deskripsi |
|--------|---------------------------|-------------|
| POST   | `/posts/{id}/comments`    | Membuat komentar baru pada artikel blog |
| GET    | `/posts/{id}/comments`    | Mendapatkan daftar semua komentar untuk artikel blog tertentu |
| GET    | `/comments/{id}`          | Mendapatkan detail satu komentar berdasarkan ID |
| PATCH  | `/comments/{id}`          | Mengupdate komentar berdasarkan ID |
| DELETE | `/comments/{id}`          | Menghapus komentar berdasarkan ID |

## Instalasi dan Konfigurasi
Berikut adalah langkah-langkah untuk menginstal dan menjalankan proyek ini di lingkungan lokal:

### Prasyarat
- PHP >= 7.3
- Composer
- MySQL/MariaDB
- Git

### Langkah Instalasi
1. Clone repository ini:
   ```bash
   git clone https://github.com/aryansh13/Blog-API-Laravel-Lumen.git
   cd Blog-API-Laravel-Lumen
   ```

2. Install dependensi dengan Composer:
   ```bash
   composer install
   ```

3. Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```

4. Konfigurasi koneksi database di file `.env`:
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_blog_api
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Buat database baru:
   ```sql
   CREATE DATABASE blog_api;
   ```

6. Jalankan migrasi database:
   ```bash
   php artisan migrate
   ```

7. Set permission folder (untuk Linux/Mac):
   ```bash
   chmod -R 777 storage
   ```

8. Jalankan server lokal:
   ```bash
   php -S localhost:8000 -t public
   ```

9. Akses API melalui browser atau Postman:
   ```
   http://localhost:8000/posts
   ```

10. Contoh
    - Metode : POST
    - URL : ``` http://localhost:8000/posts ```
    - Headers : Key ``` Content-Type ``` , Value ``` application/json ```
    - Body : Pilih ``` raw ``` dan tipe ``` JSON ```, kemudian masukkan:
    ```
      {
         "title": "Artikel Pertama",
         "content": "Ini adalah konten artikel pertama saya."
      }
    ```

