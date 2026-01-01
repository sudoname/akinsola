<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CycleResource\Pages;
use App\Models\AuditLog;
use App\Models\Cycle;
use App\Models\User;
use App\Notifications\ApplicationDecision;
use App\Notifications\CyclePublished;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CycleResource extends Resource
{
    protected static ?string $model = Cycle::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->regex('/^[a-z0-9\-]+$/'),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'closed' => 'Closed',
                            ])
                            ->required()
                            ->default('draft'),

                        Forms\Components\TextInput::make('budget_total')
                            ->numeric()
                            ->prefix('₦')
                            ->step(0.01),
                    ])->columns(2),

                Forms\Components\Section::make('Dates & Timeline')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_at')
                            ->required()
                            ->native(false),

                        Forms\Components\DateTimePicker::make('deadline_at')
                            ->required()
                            ->native(false)
                            ->after('start_at'),

                        Forms\Components\DateTimePicker::make('results_release_at')
                            ->required()
                            ->native(false)
                            ->after('deadline_at')
                            ->helperText('Results will automatically become visible at this date/time'),

                        Forms\Components\DateTimePicker::make('manual_published_at')
                            ->native(false)
                            ->disabled()
                            ->helperText('Set via "Publish Now" action'),
                    ])->columns(2),

                Forms\Components\Section::make('Tracks & Settings')
                    ->schema([
                        Forms\Components\CheckboxList::make('tracks_json')
                            ->label('Enabled Tracks')
                            ->options([
                                'secondary' => 'Secondary School',
                                'university' => 'University',
                                'polytechnic' => 'Polytechnic',
                            ])
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'success' => 'published',
                        'danger' => 'closed',
                    ]),

                Tables\Columns\TextColumn::make('start_at')
                    ->dateTime('M d, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('deadline_at')
                    ->dateTime('M d, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('results_release_at')
                    ->label('Results Release')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),

                Tables\Columns\IconColumn::make('resultsAreVisible')
                    ->label('Results Visible')
                    ->boolean()
                    ->getStateUsing(fn (Cycle $record) => $record->resultsAreVisible()),

                Tables\Columns\TextColumn::make('applications_count')
                    ->counts('applications')
                    ->label('Applications'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'closed' => 'Closed',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('notifyApplicants')
                    ->label('Notify Applicants')
                    ->icon('heroicon-o-megaphone')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalHeading('Send Cycle Notification')
                    ->modalDescription(fn (Cycle $record) => 'This will send email notifications to all applicants who have applications in this cycle (' . $record->applications()->distinct('user_id')->count('user_id') . ' applicants).')
                    ->visible(fn (Cycle $record) => $record->status === 'published')
                    ->action(function (Cycle $record) {
                        // Get only users who have applied for this cycle
                        $applicantIds = $record->applications()
                            ->distinct()
                            ->pluck('user_id');

                        $applicants = User::whereIn('id', $applicantIds)->get();

                        $count = 0;
                        foreach ($applicants as $applicant) {
                            $applicant->notify(new CyclePublished($record));
                            $count++;
                        }

                        AuditLog::logAction(
                            'cycle.notify_applicants',
                            auth()->id(),
                            Cycle::class,
                            $record->id,
                            [
                                'cycle_title' => $record->title,
                                'applicant_count' => $count,
                            ]
                        );

                        Notification::make()
                            ->success()
                            ->title('Notifications Sent')
                            ->body("Cycle announcement emails queued for {$count} applicants in this cycle.")
                            ->send();
                    }),

                Tables\Actions\Action::make('publishNow')
                    ->label('Publish Results Now')
                    ->icon('heroicon-o-rocket-launch')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Publish Results Early')
                    ->modalDescription('This will make all results visible to applicants immediately, overriding the scheduled release date.')
                    ->visible(fn (Cycle $record) => !$record->resultsAreVisible())
                    ->action(function (Cycle $record) {
                        $record->publishResultsNow();

                        AuditLog::logAction(
                            'cycle.publish_results_early',
                            auth()->id(),
                            Cycle::class,
                            $record->id,
                            ['cycle_title' => $record->title]
                        );

                        Notification::make()
                            ->success()
                            ->title('Results Published')
                            ->body('Results are now visible to applicants.')
                            ->send();
                    }),

                Tables\Actions\Action::make('sendNotifications')
                    ->label('Send Result Notifications')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Send Result Notifications')
                    ->modalDescription(fn (Cycle $record) => 'This will send email notifications to all applicants in this cycle with decisions (' . $record->applications()->whereIn('status', ['approved', 'rejected', 'waitlisted'])->count() . ' applicants).')
                    ->visible(fn (Cycle $record) => $record->resultsAreVisible())
                    ->action(function (Cycle $record) {
                        // Get all applications with decisions in this cycle only
                        $applications = $record->applications()
                            ->whereIn('status', ['approved', 'rejected', 'waitlisted'])
                            ->with('user')
                            ->get();

                        $count = 0;
                        foreach ($applications as $application) {
                            // Send notification to applicant
                            $application->user->notify(new ApplicationDecision($application));
                            $count++;
                        }

                        AuditLog::logAction(
                            'cycle.send_result_notifications',
                            auth()->id(),
                            Cycle::class,
                            $record->id,
                            [
                                'cycle_title' => $record->title,
                                'applicant_count' => $count,
                            ]
                        );

                        Notification::make()
                            ->success()
                            ->title('Notifications Queued')
                            ->body("Result emails queued for {$count} applicants.")
                            ->send();
                    }),

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
            'index' => Pages\ListCycles::route('/'),
            'create' => Pages\CreateCycle::route('/create'),
            'edit' => Pages\EditCycle::route('/{record}/edit'),
        ];
    }
}
