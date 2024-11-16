<?php

namespace App\Models;

use App\Enum\ProductEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $casts = [
        'status' => ProductEnum::class,
    ];

    protected $hidden = [
        'id',
    ];

    public $timestamps = false;

    protected $fillable = [
        'code',
        'status',
        'imported_t',
        'url',
        'creator',
        'created_t',
        'last_modified_t',
        'product_name',
        'quantity',
        'brands',
        'categories',
        'labels',
        'cities',
        'purchase_places',
        'stores',
        'ingredients_text',
        'traces',
        'serving_size',
        'serving_quantity',
        'nutriscore_score',
        'nutriscore_grade',
        'main_category',
        'image_url',
    ];

    public static function findByCode(string $code): ?Model
    {
        return self::where('code', $code)->first();
    }
}
