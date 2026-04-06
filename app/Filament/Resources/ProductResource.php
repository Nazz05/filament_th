<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $slug = 'sv23810310082/products';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(12)
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Danh mục')
                            ->relationship('category', 'name', fn ($query) => $query->orderBy('name'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpan(6),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'out_of_stock' => 'Out of stock',
                            ])
                            ->required()
                            ->default('draft')
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                $set('slug', Str::slug($state));
                            })
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->prefix('VNĐ')
                            ->columnSpan(4),
                        Forms\Components\TextInput::make('stock_quantity')
                            ->required()
                            ->integer()
                            ->minValue(0)
                            ->default(0)
                            ->columnSpan(4),
                        Forms\Components\TextInput::make('discount_percent')
                            ->label('Giảm giá (%)')
                            ->integer()
                            ->minValue(0)
                            ->maxValue(90)
                            ->default(0)
                            ->helperText('Trường sáng tạo: phần trăm giảm giá cho sản phẩm.')
                            ->columnSpan(4),
                        Forms\Components\Placeholder::make('final_price_preview')
                            ->label('Giá sau giảm (xem nhanh)')
                            ->content(function (Get $get): string {
                                $price = (float) ($get('price') ?? 0);
                                $discount = (int) ($get('discount_percent') ?? 0);
                                $finalPrice = max(0, $price * (1 - ($discount / 100)));

                                return number_format($finalPrice, 0, ',', '.') . ' VNĐ';
                            })
                            ->columnSpan(12),
                        Forms\Components\RichEditor::make('description')
                            ->columnSpan(8),
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Ảnh đại diện')
                            ->image()
                            ->maxFiles(1)
                            ->directory('products')
                            ->disk('public')
                            ->columnSpan(4),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Giá')
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 0, ',', '.') . ' VNĐ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_percent')
                    ->label('Giảm (%)')
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Tồn kho')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Ảnh'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'published',
                        'danger' => 'out_of_stock',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Danh mục')
                    ->options(fn () => Category::query()->orderBy('name')->pluck('name', 'id')->all()),
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
