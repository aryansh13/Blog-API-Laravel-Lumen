# Blog API with Laravel Lumen
Sebuah REST API untuk blog sederhana yang dibangun menggunakan framework Laravel Lumen. API ini menyediakan endpoint untuk mengelola artikel blog (posts) dan komentar (comments) dengan mengimplementasikan prinsip-prinsip RESTful.

## Fitur
API ini memiliki beberapa fitur utama:

- **Authentication**: Login dan register user dengan token-based authentication
- **Post Management**: Endpoint untuk membuat, membaca, mengupdate, dan menghapus artikel blog.
- **Comment Management**: Endpoint untuk membuat, membaca, mengupdate, dan menghapus komentar pada artikel blog.

## Authentication

This API uses token-based authentication. To access protected endpoints, you need to:

1. Register a new account or login with existing account to get an API token
2. Include the token in your requests using one of these methods:
   - In the Authorization header: `Authorization: Bearer YOUR_TOKEN`
   - As a query parameter: `?api_token=YOUR_TOKEN`

## Endpoint API

### Authentication Endpoints
| Method | URL                 | Deskripsi                                    |
|--------|---------------------|----------------------------------------------|
| POST   | `/auth/register`    | Mendaftarkan user baru                       |
| POST   | `/auth/login`       | Login dan mendapatkan API token              |
| POST   | `/auth/logout`      | Logout (invalidate token) - Auth required    |
| GET    | `/auth/me`          | Mendapatkan data user saat ini - Auth required |

### Posts Endpoints
| Method | URL            | Deskripsi                                      |
|--------|--------------|--------------------------------|
| POST   | `/posts`      | Membuat artikel blog baru (Auth required)    |
| GET    | `/posts`      | Mendapatkan daftar semua artikel blog dengan pagination |
| GET    | `/posts/{id}` | Mendapatkan detail satu artikel blog berdasarkan ID |
| PATCH  | `/posts/{id}` | Mengupdate artikel blog berdasarkan ID (Auth required) |
| DELETE | `/posts/{id}` | Menghapus artikel blog berdasarkan ID (Auth required) |

### Comments Endpoints
| Method | URL                       | Deskripsi |
|--------|---------------------------|-------------|
| POST   | `/posts/{id}/comments`    | Membuat komentar baru pada artikel blog (Auth required) |
| GET    | `/posts/{id}/comments`    | Mendapatkan daftar semua komentar untuk artikel blog tertentu |
| GET    | `/comments/{id}`          | Mendapatkan detail satu komentar berdasarkan ID |
| PATCH  | `/comments/{id}`          | Mengupdate komentar berdasarkan ID (Auth required) |
| DELETE | `/comments/{id}`          | Menghapus komentar berdasarkan ID (Auth required) |

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

## Endpoint Guide

### User Management

- `POST /users` - Create a new user and get API token
- `GET /users` - List all users
- `GET /users/{id}` - View user details
- `PATCH /users/{id}` - Update user
- `DELETE /users/{id}` - Delete user

### Posts (Protected - Requires Authentication)

- `POST /posts` - Create a new post
- `PATCH /posts/{id}` - Update post
- `DELETE /posts/{id}` - Delete post

### Posts (Public)

- `GET /posts` - List all posts
- `GET /posts/{id}` - View post details

### Comments (Protected - Requires Authentication)

- `POST /posts/{postId}/comments` - Add comment to a post
- `PATCH /comments/{id}` - Update comment
- `DELETE /comments/{id}` - Delete comment

### Comments (Public)

- `GET /posts/{postId}/comments` - List all comments for a post
- `GET /comments/{id}` - View comment details

## Testing in Postman

1. **Register a new user**
   - Method: POST
   - URL: `http://localhost/blog/auth/register`
   - Body (JSON):
   ```json
   {
     "name": "Test User",
     "email": "user@example.com",
     "password": "password123"
   }
   ```

2. **Login to get API token**
   - Method: POST
   - URL: `http://localhost/blog/auth/login`
   - Body (JSON):
   ```json
   {
     "email": "user@example.com",
     "password": "password123"
   }
   ```
   - Response will contain your `api_token`

3. **Access protected endpoints**
   - Method: POST
   - URL: `http://localhost/blog/posts`
   - Authentication: 
     - Add header: `Authorization: Bearer YOUR_API_TOKEN`
     - Or append to URL: `?api_token=YOUR_API_TOKEN`
   - Body (JSON):
   ```json
   {
     "title": "My First Post",
     "content": "This is the content of my first post"
   }
   ```

4. **View your profile**
   - Method: GET
   - URL: `http://localhost/blog/auth/me`
   - Authentication: Add header or query parameter with token

5. **Logout**
   - Method: POST
   - URL: `http://localhost/blog/auth/logout`
   - Authentication: Add header or query parameter with token

6. **Access public endpoints**
   - No authentication required:
   - Method: GET
   - URL: `http://localhost/blog/posts`

