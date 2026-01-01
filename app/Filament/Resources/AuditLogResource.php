<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditLogResource\Pages;
use App\Filament\Resources\AuditLogResource\RelationManagers;
use App\Models\AuditLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Audit Logs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('action')
                    ->disabled(),
                Forms\Components\TextInput::make('user.name')
                    ->label('User')
                    ->disabled(),
                Forms\Components\TextInput::make('target_type')
                    ->disabled(),
                Forms\Components\TextInput::make('target_id')
                    ->disabled(),
                Forms\Components\KeyValue::make('meta_json')
                    ->label('Metadata')
                    ->disabled(),
                Forms\Components\TextInput::make('ip_address')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable()
                    ->default('System'),

                Tables\Columns\TextColumn::make('action')
                    ->label('Action')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        str_contains($state, 'create') => 'success',
                        str_contains($state, 'delete') => 'danger',
                        str_contains($state, 'update') || str_contains($state, 'edit') => 'warning',
                        str_contains($state, 'publish') => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('target_type')
                    ->label('Resource')
                    ->formatStateUsing(fn (?string $state): string =>
                        $state ? class_basename($state) : 'N/A'
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('target_id')
                    ->label('Resource ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date & Time')
                    ->dateTime('M d, Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name'),

                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'cycle.create' => 'Cycle Created',
                        'cycle.update' => 'Cycle Updated',
                        'cycle.publish_results_early' => 'Results Published Early',
                        'cycle.send_result_notifications' => 'Notifications Sent',
                        'application.decision_made' => 'Decision Made',
                        'application.review_started' => 'Review Started',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // No bulk actions for audit logs
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
            'index' => Pages\ListAuditLogs::route('/'),
            'view' => Pages\ViewAuditLog::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->isSuperAdmin();
    }
}
