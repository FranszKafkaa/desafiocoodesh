<?php

namespace App\Jobs;

use App\Enum\ProductEnum;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InsertAndUpdateProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private array $data) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $product = Product::findByCode($this->data['code']);

        $this->data['imported_t'] = Carbon::now()->toDateTime();
        $this->data['status'] = ProductEnum::DAFT;

        if ($product) {
            $timestamp = Carbon::createFromTimestamp($this->data['last_modified_t']);
            $currentTimestamp = Carbon::createFromTimestamp($product->last_modified_t);

            if ($currentTimestamp->isBefore($timestamp)) {
                $product->update($this->data);
            }

        } else {
            Product::create($this->data);
        }

    }

    public function failed(): void {}
}
