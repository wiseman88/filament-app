<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $categoryId = $data['category_id'];
        unset($data['category_id']);

        $product = Product::create($data);
        $product->categories()->sync($categoryId);

        return $product;
    }
}
