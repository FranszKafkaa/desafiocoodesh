<?php

use App\Enum\ProductEnum;
use App\Models\Product;

beforeEach(function () {
    $this->product = Product::factory()->create();
});

afterEach(function () {
    $this->product->delete();
});

test('teste de listagem de produtos', function () {
    $this->getJson(route('products.list'))->assertJson(
        [
            'data' => [],
            'links' => [],
        ]
    )->assertStatus(200);
});

test('teste de DELETE', function () {
    $this->deleteJson(route('products.delete', $this->product->code))->assertJson(
        [
            'data' => [
                'status' => ProductEnum::TRASH->value,
            ],
        ]
    )->assertStatus(200);
});

test('teste de update', function () {
    $this->putJson(route('products.update', $this->product->code),
        [
            'creator' => 'test',
        ])->assertJson([
            'data' => [
                'creator' => 'test',
            ],
        ])->assertStatus(200);
});
