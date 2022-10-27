<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('code')
                            ->label('Code')
                            ->unique(table: Product::class, ignoreRecord: true)
                            ->required(),
                        TextInput::make('name')
                            ->label('Name')
                            ->required(),
                        TextInput::make('price')->mask(fn (TextInput\Mask $mask) =>
                        $mask->money(prefix: '$', thousandsSeparator: ',', decimalPlaces: 2)),
                        TextInput::make('min_quantity')
                            ->label('Min Quantity')
                            ->numeric()
                            ->mask(
                                fn (TextInput\Mask $mask) => $mask
                                    ->numeric()
                                    ->decimalPlaces(2) // Set the number of digits after the decimal point.
                                    ->decimalSeparator(',') // Add a separator for decimal numbers.                                    
                                    ->mapToDecimalSeparator([',']) // Map additional characters to the decimal separator.
                                    ->normalizeZeros() // Append or remove zeros at the end of the number.
                                    ->padFractionalZeros() // Pad zeros at the end of the number to always maintain the maximum number of decimal places.
                                ,
                            ),
                        TextInput::make('discount_rate')
                            ->label('Discount Rate')
                            ->numeric()
                            ->mask(
                                fn (TextInput\Mask $mask) => $mask
                                    ->numeric()
                                    //->decimalPlaces(2) // Set the number of digits after the decimal point.
                                    //->decimalSeparator(',') // Add a separator for decimal numbers.                                    
                                    ->mapToDecimalSeparator([',']) // Map additional characters to the decimal separator.
                                    ->minValue(0) // Set the minimum value that the number can be.
                                    ->maxValue(100) // Set the maximum value that the number can be.                                
                                    ->normalizeZeros() // Append or remove zeros at the end of the number.
                                    ->padFractionalZeros() // Pad zeros at the end of the number to always maintain the maximum number of decimal places.
                                ,
                            ),
                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name'),
                        SpatieMediaLibraryFileUpload::make('image')
                            ->collection('image')
                            ->maxSize(10000000)
                            ->label(__('Image')),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label(__('Code'))
                    ->searchable(),
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                TextColumn::make('price')
                    ->label(__('Price'))
                    ->money('usd', true)
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ReplicateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
