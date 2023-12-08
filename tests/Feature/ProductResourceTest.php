<?php

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use function Pest\Laravel\get;

it('can render index page', function () {
    get(ProductResource::getUrl('index'))->assertOk();
});

it('can render create page', function () {
    get(ProductResource::getUrl('create'))->assertOk();
});

it('can render edit page', function () {
    get(ProductResource::getUrl('edit', [
        'record' => Product::factory()->create(),
    ]))->assertOk();
});
