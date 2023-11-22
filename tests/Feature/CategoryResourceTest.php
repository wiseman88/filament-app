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
