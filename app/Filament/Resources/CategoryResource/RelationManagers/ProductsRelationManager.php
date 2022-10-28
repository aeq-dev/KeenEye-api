<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'name';

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
                                    ->decimalPlaces(2)
                                    ->decimalSeparator(',')
                                    ->mapToDecimalSeparator([','])
                                    ->normalizeZeros()
                                    ->padFractionalZeros(),
                            ),
                        TextInput::make('discount_rate')
                            ->label('Discount Rate')
                            ->numeric()
                            ->mask(
                                fn (TextInput\Mask $mask) => $mask
                                    ->numeric()
                                    ->mapToDecimalSeparator([','])
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->normalizeZeros()
                                    ->padFractionalZeros(),
                            ),
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
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
