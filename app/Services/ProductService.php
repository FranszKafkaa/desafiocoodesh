<?php

namespace App\Services;

use App\Enum\ProductEnum;
use App\Jobs\InsertAndUpdateProduct;
use App\Models\Product;
use App\Traits\FileManegement;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    use FileManegement;

    public function insert(): void
    {
        if (! Cache::has('count_source')) {
            Cache::put('count_source', 0);
        }

        $count = Cache::get('count_source');
        $list = $this->downloadFile(config('product.data_source')[$count++]);

        foreach ($list as $item) {
            InsertAndUpdateProduct::dispatch($item);
        }

        if ($count > count(config('product.data_source')) - 1) {
            $count = 0;
        }

        Cache::put('count_source', $count);
        Cache::put('cron_time', Carbon::now()->toDateTimeString());
    }

    public function listAll(): LengthAwarePaginator
    {
        return Product::WhereNotLike('status', 'trash')->paginate();
    }

    public function show(string $code): array|null
    {
        return Product::findByCode($code)?->toArray();
    }

    public function update(FormRequest $request, string $code): array
    {
        $product = Product::findByCode($code);

        if($product){
            $product->update($request->toArray());
        }else {
            abort(404, "produto nao encontrado");
        }

        return $product->toArray();
    }

    public function delete(string $code): array
    {
        $product = Product::findByCode($code);
        if($product) {
            $product->status = ProductEnum::TRASH;
            $product->save();
        }else{
            abort(404, "produto nao encontrado");
        }

        return $product->toArray();
    }
}
