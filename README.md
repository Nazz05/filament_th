# Filament Admin - MSSV 23810310082

Phan he admin quan ly Danh muc va San pham duoc xay dung bang Laravel 10 + Filament 3.

## Yeu cau da hoan thanh

- MSSV: `23810310082`
- Ten bang DB co prefix MSSV:
	- `sv23810310082_categories`
	- `sv23810310082_products`
- Resource slug co prefix MSSV:
	- `sv23810310082/categories`
	- `sv23810310082/products`
- Primary color Filament da doi sang `#0D9488` (khong dung Amber mac dinh).
- Truong sang tao them cho Product: `discount_percent` (kem logic tinh gia sau giam).

## Cau truc du lieu

### Category
- `id`
- `name` (unique)
- `slug` (unique)
- `description`
- `is_visible` (boolean)

### Product
- `id`
- `category_id` (foreign key)
- `name`
- `slug` (unique)
- `description`
- `price`
- `stock_quantity`
- `image_path`
- `status` (`draft`, `published`, `out_of_stock`)
- `discount_percent` (truong sang tao)

## Chuc nang Filament Admin

### Category Resource
- Tu dong tao slug khi nhap name.
- Table co bo loc `is_visible`.

### Product Resource
- Form dung Grid layout.
- Mo ta dung Rich Editor.
- Upload 01 anh dai dien (`image_path`).
- Gia tien hien thi dinh dang `VNĐ`.
- Tim kiem theo ten san pham.
- Loc theo danh muc.
- Validation:
	- `price >= 0`
	- `stock_quantity` la so nguyen va `>= 0`

### Logic truong sang tao
- `discount_percent` duoc gioi han trong khoang 0-90.
- Hien thi xem nhanh gia sau giam trong form.
- Bang danh sach co cot `Gia sau giam`.

## Cai dat va chay (localhost - XAMPP)

1. Cai dependency:

```bash
composer install
```

2. Tao file env va sinh key:

```bash
cp .env.example .env
php artisan key:generate
```

3. Cau hinh DB trong `.env` (MySQL).

4. Chay migrate + seed:

```bash
php artisan migrate:fresh --seed
```

5. Tao storage link:

```bash
php artisan storage:link
```

6. Tao admin user:

```bash
php artisan make:filament-user
```

7. Bat Apache + MySQL trong XAMPP Control Panel.

8. Truy cap project tren localhost:

- Trang chu: `http://localhost/filament`
- Admin login: `http://localhost/filament/admin/login`
