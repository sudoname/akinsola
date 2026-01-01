<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemorialPhotoResource\Pages;
use App\Filament\Resources\MemorialPhotoResource\RelationManagers;
use App\Models\MemorialPhoto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemorialPhotoResource extends Resource
{
    protected static ?string $model = MemorialPhoto::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $modelLabel = 'Memorial Photo';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Photo Upload')
                    ->schema([
                        Forms\Components\FileUpload::make('photo_path')
                            ->label('Photo')
                            ->image()
                            ->disk('public')
                            ->directory('memorial-gallery')
                            ->visibility('public')
                            ->imageEditor()
                            ->imageResizeTargetWidth('1200')
                            ->imageResizeTargetHeight('800')
                            ->imageCropAspectRatio('3:2')
                            ->required()
                            ->columnSpanFull()
                            ->helperText('Upload memorial photos. Images will be resized to 1200x800 pixels.'),
                    ]),

                Forms\Components\Section::make('Details')
                    ->schema([
                        Forms\Components\TextInput::make('caption')
                            ->label('Caption')
                            ->maxLength(255)
                            ->placeholder('Enter a caption for this photo (optional)')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Lower numbers appear first in the slideshow'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->required()
                            ->helperText('Only active photos will be shown in the slideshow'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo_path')
                    ->label('Photo')
                    ->disk('public')
                    ->size(100),
                Tables\Columns\TextColumn::make('caption')
                    ->searchable()
                    ->limit(50)
                    ->placeholder('No caption'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->sortable()
                    ->badge(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Photos Only')
                    ->boolean()
                    ->trueLabel('Only active')
                    ->falseLabel('Only inactive')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListMemorialPhotos::route('/'),
            'create' => Pages\CreateMemorialPhoto::route('/create'),
            'edit' => Pages\EditMemorialPhoto::route('/{record}/edit'),
        ];
    }
}
