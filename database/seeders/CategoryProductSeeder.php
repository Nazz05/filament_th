<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $laptop = Category::query()->updateOrCreate(
            ['slug' => 'laptop'],
            [
                'name' => 'Laptop',
                'description' => 'Danh mục laptop.',
                'is_visible' => true,
            ],
        );

        $accessory = Category::query()->updateOrCreate(
            ['slug' => 'phu-kien'],
            [
                'name' => 'Phu kien',
                'description' => 'Danh mục phụ kiện.',
                'is_visible' => true,
            ],
        );

        Product::query()->updateOrCreate(
            ['slug' => 'laptop-vp-a14'],
            [
                'category_id' => $laptop->id,
                'name' => 'Laptop VP A14',
                'description' => 'Laptop văn phòng pin lâu.',
                'price' => 15990000,
                'stock_quantity' => 12,
                'status' => 'published',
                'discount_percent' => 10,
            ],
        );

        Product::query()->updateOrCreate(
            ['slug' => 'chuot-khong-day-x2'],
            [
                'category_id' => $accessory->id,
                'name' => 'Chuot khong day X2',
                'description' => 'Chuột không dây êm tay.',
                'price' => 350000,
                'stock_quantity' => 0,
                'status' => 'out_of_stock',
                'discount_percent' => 5,
            ],
        );
    }
}
