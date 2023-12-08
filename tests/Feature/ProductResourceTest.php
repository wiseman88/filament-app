<?php

use App\Filament\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

it('can render index page', function () {
    get(ProductResource::getUrl('index'))->assertOk();
});

it('can render create page', function () {
    get(ProductResource::getUrl('create'))->assertOk();
});

it('can create new product', function () {
    $category = Category::factory()->create();
    $categoryId = $category->id;

    $newData = Product::factory()->make();
    $name = $newData->name;

    livewire(ProductResource\Pages\CreateProduct::class)
        ->fillForm([
            'name' => $name,
            'slug' => Str::slug($name),
            'category_id' => $categoryId,
            'sku' => $newData->sku,
            'description' => $newData->description,
            'quantity' => $newData->quantity,
            'price' => $newData->price,
            'visible' => $newData->visible,
            'image' => $newData->image
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $product = Product::where('name', $name)->firstOrFail();

    $this->assertDatabaseHas(Product::class, [
        'name' => $name,
        'slug' => Str::slug($name),
        'sku' => $newData->sku,
        'description' => $newData->description,
        'quantity' => $newData->quantity,
        'price' => $newData->price,
        'visible' => $newData->visible,
        'image' => $newData->image->name
    ]);

    $this->assertTrue($product->categories->contains($categoryId));
});

it('can render edit page', function () {
    get(ProductResource::getUrl('edit', [
        'record' => Product::factory()->create(),
    ]))->assertOk();
});
