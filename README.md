# Blog API dengan Laravel Lumen

Sebuah REST API untuk blog yang dibangun menggunakan framework Laravel Lumen. API ini menyediakan endpoint untuk mengelola artikel blog (posts) dan komentar (comments) dengan sistem role-based access control (RBAC).

## Fitur Utama

### 1. Sistem Autentikasi
- Register user baru
- Login dengan email dan password
- Token-based authentication menggunakan Laravel Sanctum
- Logout (invalidate token)
- Get user profile

### 2. Role-Based Access Control (RBAC)
Sistem memiliki 3 role dengan hak akses berbeda:

#### Reader (Default)
- Dapat membaca semua post dan komentar
- Dapat membuat komentar
- Tidak dapat membuat atau mengedit post

#### Writer
- Memiliki semua hak akses reader
- Dapat membuat post baru
- Dapat mengedit dan menghapus post mereka sendiri
- Post default status 'draft'

#### Editor
- Memiliki semua hak akses writer
- Dapat mengedit dan menghapus semua post
- Dapat publish/unpublish post
- Dapat mengelola user (CRUD)

### 3. Post Management
- Create, read, update, delete post
- Status post (draft/published)
- Pagination dan filtering
- Relasi dengan user dan komentar

### 4. Comment Management
- Create, read, update, delete komentar
- Relasi dengan post dan user
- Pagination

## Instalasi

### Prasyarat
- PHP >= 7.3
- Composer
- MySQL/MariaDB
- Git

### Langkah Instalasi
1. Clone repository:
```bash
git clone [repository-url]
cd [project-directory]
```

2. Install dependencies:
```bash
composer install
```

3. Copy file .env:
```bash
cp .env.example .env
```

4. Konfigurasi database di .env:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog_api
DB_USERNAME=root
DB_PASSWORD=
```

5. Jalankan migrasi:
```bash
php artisan migrate
```

6. Jalankan server:
```bash
php -S localhost:8000 -t public
```

## Penggunaan API

### 1. Autentikasi

#### Register User Baru
```http
POST /auth/register
Content-Type: application/json

{
    "name": "Test User",
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "writer"  // opsional: reader, writer, atau editor
}
```

#### Login
```http
POST /auth/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password123"
}
```

#### Logout
```http
POST /auth/logout
Authorization: Bearer {token}
```

#### Get Profile
```http
GET /auth/me
Authorization: Bearer {token}
```

### 2. Post Management

#### Create Post (Writer/Editor)
```http
POST /posts
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Judul Post",
    "content": "Konten post...",
    "status": "draft"  // opsional: draft atau published
    "user_id": 1
}
```

#### Get All Posts
```http
GET /posts?page=1&limit=10&status=published
```

#### Get Single Post
```http
GET /posts/{id}
```

#### Update Post (Writer/Editor)
```http
PATCH /posts/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Judul Updated",
    "content": "Konten updated..."
}
```

#### Delete Post (Writer/Editor)
```http
DELETE /posts/{id}
Authorization: Bearer {token}
```

#### Publish/Unpublish Post (Editor)
```http
PATCH /posts/{id}/publish
PATCH /posts/{id}/unpublish
Authorization: Bearer {token}
```

### 3. Comment Management

#### Create Comment
```http
POST /posts/{postId}/comments
Authorization: Bearer {token}
Content-Type: application/json

{
    "comment": "Isi komentar...",
    "user_id": 1
}
```

#### Get Post Comments
```http
GET /posts/{postId}/comments?page=1&limit=10
```

#### Update Comment
```http
PATCH /comments/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "comment": "Komentar updated..."
}
```

#### Delete Comment
```http
DELETE /comments/{id}
Authorization: Bearer {token}
```

### 4. User Management (Editor)

#### Get All Users
```http
GET /admin/users?page=1&limit=10
Authorization: Bearer {token}
```

#### Get User Details
```http
GET /admin/users/{id}
Authorization: Bearer {token}
```

#### Update User
```http
PATCH /admin/users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "New Name",
    "email": "new@email.com",
    "role": "writer"
}
```

#### Delete User
```http
DELETE /admin/users/{id}
Authorization: Bearer {token}
```

## Response Format

Semua response menggunakan format JSON dengan struktur:

```json
{
    "data": [...],  // untuk list data
    "message": "...",  // untuk pesan sukses/error
    "errors": {...},  // untuk error validasi
    "pagination": {  // untuk data yang dipaginasi
        "first_page": "...",
        "last_page": "...",
        "page": 1,
        "next_page": "...",
        "prev_page": "..."
    }
}
```

## Error Handling

API menggunakan kode status HTTP standar:
- 200: Success
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 422: Validation Error
- 500: Server Error

## Testing

Untuk testing API, Anda dapat menggunakan Postman atau tools API testing lainnya. Pastikan untuk:
1. Register user baru
2. Login untuk mendapatkan token
3. Gunakan token di header Authorization untuk request yang memerlukan autentikasi
4. Perhatikan role user untuk akses ke endpoint tertentu

