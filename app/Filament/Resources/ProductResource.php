<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make([
                        Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                            ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            $set('slug', Str::slug($state));
                        }),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Product::class, 'slug', ignoreRecord: true),
                        Forms\Components\Select::make('category_id')
                            ->required()
                            ->label('Category')
                            ->options(Category::all()->pluck('name', 'id'))
                            ->relationship('categories', 'name'),
                        Forms\Components\TextInput::make('sku')
                            ->required()
                            ->maxLength(50)
                            ->label('SKU'),
                        Forms\Components\MarkdownEditor::make('description')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->default(0)
                            ->step(1),
                        Forms\Components\TextInput::make('price')
                            ->numeric(),
                        Forms\Components\Toggle::make('visible')
                            ->helperText('Enable or disable product visibility')
                            ->default(false),
                        Forms\Components\Section::make('Image')
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->directory('products')
                                    ->preserveFilenames()
                                    ->image()
                                    ->imageEditor(),
                            ])->collapsible()->columnSpan(1),
                    ])->columnSpanFull()
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('categories.name'),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\ToggleColumn::make('visible'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
