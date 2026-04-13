<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Select::make('role')
                            ->options([
                                'applicant' => 'Applicant',
                                'reviewer' => 'Reviewer',
                                'approver' => 'Approver',
                                'super_admin' => 'Super Admin',
                            ])
                            ->required()
                            ->default('applicant'),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255),

                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->native(false)
                            ->helperText('Set a date/time to manually verify this user\'s email. Leave empty to require email verification.')
                            ->displayFormat('M d, Y H:i')
                            ->seconds(false),

                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('verify_now')
                                ->label('Verify Email Now')
                                ->icon('heroicon-o-check-circle')
                                ->color('success')
                                ->action(function (\Filament\Forms\Set $set) {
                                    $set('email_verified_at', now());
                                })
                                ->visible(fn ($get) => !$get('email_verified_at')),
                        ]),
                    ])->columns(2),

                Forms\Components\Section::make('Social Auth (Read Only)')
                    ->schema([
                        Forms\Components\TextInput::make('provider')
                            ->disabled(),

                        Forms\Components\TextInput::make('provider_id')
                            ->disabled(),
                    ])->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('role')
                    ->colors([
                        'secondary' => 'applicant',
                        'info' => 'reviewer',
                        'warning' => 'approver',
                        'success' => 'super_admin',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state))),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !is_null($record->email_verified_at)),

                Tables\Columns\TextColumn::make('provider')
                    ->label('Social Auth')
                    ->default('Email')
                    ->formatStateUsing(fn ($state) => $state ? ucfirst($state) : 'Email')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'applicant' => 'Applicant',
                        'reviewer' => 'Reviewer',
                        'approver' => 'Approver',
                        'super_admin' => 'Super Admin',
                    ]),

                Tables\Filters\Filter::make('verified')
                    ->query(fn ($query) => $query->whereNotNull('email_verified_at'))
                    ->label('Verified Only'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
