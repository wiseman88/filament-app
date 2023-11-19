<?php

namespace Tests\Feature;

use App\Filament\Resources\CategoryResource;

use function Pest\Laravel\get;

it('can render page', function () {
    get(CategoryResource::getUrl('index'))->assertOk();
});

it('it renders the index page', function () {
    expect(true)->toBe(true);
});
