<?php

namespace Tests\Feature;

use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Livewire\livewire;
use function Pest\Laravel\get;
use Illuminate\Support\Str;

it('can render page', function () {
    get(CategoryResource::getUrl('index'))->assertOk();
});

it('can render create page', function () {
    get(CategoryResource::getUrl('create'))->assertOk();
});

it('can list category list', function () {
    $categories = Category::factory()->count(10)->create();

    livewire(CategoryResource\Pages\ListCategories::class)
        ->assertCanSeeTableRecords($categories);
});

it('can render edit page', function () {
    get(CategoryResource::getUrl('edit', [
        'record' => Category::factory()->create(),
    ]))->assertOk();
});

it('can retrieve data', function () {
    $category = Category::factory()->create();
    $name = $category->name;

    livewire(CategoryResource\Pages\EditCategory::class, [
        'record' => $category->getRouteKey(),
    ])
        ->assertFormSet([
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $category->description,
            'visible' => $category->visible,
        ]);
});

it('can save edited data', function () {
    $category = Category::factory()->create();
    $newData = Category::factory()->make();
    $name = $newData->name;

    livewire(CategoryResource\Pages\EditCategory::class, [
        'record' => $category->getRouteKey(),
    ])
        ->fillForm([
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $newData->description,
            'visible' => $newData->visible,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($category->refresh())
        ->name->toBe($newData->name)
        ->slug->toBe(Str::slug($newData->name))
        ->description->toBe($newData->description)
        ->visible->toBe((int)$newData->visible);
});

it('can validate edit input', function () {
    $category = Category::factory()->create();

    livewire(CategoryResource\Pages\EditCategory::class, [
        'record' => $category->getRouteKey(),
    ])
        ->fillForm([
            'name' => null,
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});
