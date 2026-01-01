<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\Setting;
use App\Notifications\ApplicationDecision;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Application Details')
                    ->schema([
                        Forms\Components\TextInput::make('user.name')
                            ->label('Applicant Name')
                            ->disabled(),

                        Forms\Components\TextInput::make('user.email')
                            ->label('Email')
                            ->disabled(),

                        Forms\Components\TextInput::make('cycle.title')
                            ->label('Cycle')
                            ->disabled(),

                        Forms\Components\Select::make('track')
                            ->options([
                                'secondary' => 'Secondary School',
                                'university' => 'University',
                                'polytechnic' => 'Polytechnic',
                            ])
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'submitted' => 'Submitted',
                                'under_review' => 'Under Review',
                                'decision_pending_release' => 'Decision Pending Release',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'waitlisted' => 'Waitlisted',
                            ])
                            ->disabled(),

                        Forms\Components\DateTimePicker::make('submission_at')
                            ->label('Submitted At')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Scoring')
                    ->schema([
                        Forms\Components\TextInput::make('score_academic')
                            ->label('Academic Score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.01)
                            ->suffix('/ 100'),

                        Forms\Components\TextInput::make('score_need')
                            ->label('Financial Need Score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.01)
                            ->suffix('/ 100'),

                        Forms\Components\TextInput::make('score_service')
                            ->label('Community Service Score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.01)
                            ->suffix('/ 100'),

                        Forms\Components\TextInput::make('score_leadership')
                            ->label('Leadership Score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.01)
                            ->suffix('/ 100'),

                        Forms\Components\Placeholder::make('score_total_calculated')
                            ->label('Weighted Total Score')
                            ->content(function ($record) {
                                if (!$record) return 'N/A';
                                $weights = Setting::getScoringWeights();
                                return $record->calculateTotalScore($weights) . ' / 100';
                            })
                            ->columnSpanFull(),
                    ])->columns(2)
                    ->visible(fn () => auth()->user()->isReviewer()),

                Forms\Components\Section::make('Decision')
                    ->schema([
                        Forms\Components\Select::make('decision_reason_code')
                            ->label('Reason Code')
                            ->options(function () {
                                $codes = Setting::getDecisionReasonCodes();
                                if (empty($codes)) return [];
                                return array_combine($codes, array_map(fn($c) => ucwords(str_replace('_', ' ', $c)), $codes));
                            }),

                        Forms\Components\TextInput::make('scholarship_amount')
                            ->label('Scholarship Amount (Naira)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(200000)
                            ->prefix('₦')
                            ->step(0.01)
                            ->helperText('Maximum amount per candidate is ₦200,000 per cycle')
                            ->rules(['nullable', 'numeric', 'min:0', 'max:200000'])
                            ->visible(fn ($record) => $record && in_array($record->status, ['approved', 'decision_pending_release'])),

                        Forms\Components\Textarea::make('decision_note')
                            ->label('Internal Note')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('decision_set_at')
                            ->label('Decision Date')
                            ->disabled(),
                    ])->columns(2)
                    ->visible(fn () => auth()->user()->isReviewer()),

                Forms\Components\Section::make('Awardee Information')
                    ->description('Photo and profile to display on the public awardees page (only for approved applications)')
                    ->schema([
                        Forms\Components\FileUpload::make('awardee_photo')
                            ->label('Awardee Photo')
                            ->image()
                            ->disk('public')
                            ->directory('awardees')
                            ->visibility('public')
                            ->imageEditor()
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('400')
                            ->imageResizeTargetHeight('400')
                            ->helperText('Square photo of the awardee (400x400px recommended). Will be displayed on the public awardees page.')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('awardee_profile')
                            ->label('Awardee Profile')
                            ->rows(4)
                            ->helperText('Brief profile/bio of the awardee to display on the public awardees page (e.g., achievements, plans, etc.)')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => $record && $record->status === 'approved' && auth()->user()->isApprover()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Applicant')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('cycle.title')
                    ->label('Cycle')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('track')
                    ->colors([
                        'primary' => 'secondary',
                        'success' => 'university',
                        'warning' => 'polytechnic',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'info' => 'submitted',
                        'warning' => 'under_review',
                        'primary' => 'decision_pending_release',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'gray' => 'waitlisted',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state))),

                Tables\Columns\TextColumn::make('score_total')
                    ->label('Total Score')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('scholarship_amount')
                    ->label('Scholarship Amount')
                    ->money('NGN')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('submission_at')
                    ->label('Submitted')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('reviewer.name')
                    ->label('Reviewer')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('cycle_id')
                    ->relationship('cycle', 'title')
                    ->label('Cycle'),

                Tables\Filters\SelectFilter::make('track')
                    ->options([
                        'secondary' => 'Secondary',
                        'university' => 'University',
                        'polytechnic' => 'Polytechnic',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Submitted',
                        'under_review' => 'Under Review',
                        'decision_pending_release' => 'Decision Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'waitlisted' => 'Waitlisted',
                    ])
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\Action::make('review')
                    ->label('Start Review')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->visible(fn (Application $record) => $record->status === 'submitted' && auth()->user()->isReviewer())
                    ->action(function (Application $record) {
                        $record->update([
                            'status' => 'under_review',
                            'reviewer_id' => auth()->id(),
                        ]);

                        AuditLog::logAction(
                            'application.start_review',
                            auth()->id(),
                            Application::class,
                            $record->id
                        );

                        Notification::make()
                            ->success()
                            ->title('Review Started')
                            ->send();
                    }),

                Tables\Actions\Action::make('makeDecision')
                    ->label('Make Decision')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Application $record) =>
                        $record->status === 'under_review' && auth()->user()->isApprover()
                    )
                    ->form([
                        Forms\Components\Select::make('decision')
                            ->label('Decision')
                            ->options([
                                'approved' => 'Approve',
                                'rejected' => 'Reject',
                                'waitlisted' => 'Waitlist',
                            ])
                            ->required(),

                        Forms\Components\Select::make('reason_code')
                            ->label('Reason Code')
                            ->options(function () {
                                $codes = Setting::getDecisionReasonCodes();
                                if (empty($codes)) return [];
                                return array_combine($codes, array_map(fn($c) => ucwords(str_replace('_', ' ', $c)), $codes));
                            }),

                        Forms\Components\Textarea::make('note')
                            ->label('Internal Note')
                            ->rows(3),
                    ])
                    ->action(function (Application $record, array $data) {
                        // Calculate and save total score
                        $weights = Setting::getScoringWeights();
                        $totalScore = $record->calculateTotalScore($weights);

                        $record->update(['score_total' => $totalScore]);

                        // Set decision
                        $record->setDecision(
                            $data['decision'],
                            $data['reason_code'] ?? null,
                            $data['note'] ?? null
                        );

                        // Update status to the actual decision but keep it hidden
                        $record->update(['status' => $data['decision']]);

                        AuditLog::logAction(
                            'application.decision_made',
                            auth()->id(),
                            Application::class,
                            $record->id,
                            [
                                'decision' => $data['decision'],
                                'reason_code' => $data['reason_code'] ?? null,
                            ]
                        );

                        Notification::make()
                            ->success()
                            ->title('Decision Recorded')
                            ->body('Decision will be visible to applicant after release date.')
                            ->send();
                    }),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->isReviewer()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('exportCsv')
                        ->label('Export to CSV')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($records) {
                            $filename = 'applications_' . now()->format('Y-m-d_His') . '.csv';

                            $headers = [
                                'Content-Type' => 'text/csv',
                                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                            ];

                            $callback = function() use ($records) {
                                $file = fopen('php://output', 'w');

                                // Headers
                                fputcsv($file, [
                                    'ID',
                                    'Applicant Name',
                                    'Email',
                                    'Cycle',
                                    'Track',
                                    'Status',
                                    'Institution',
                                    'Course',
                                    'Academic Score',
                                    'Need Score',
                                    'Service Score',
                                    'Leadership Score',
                                    'Total Score',
                                    'Submitted At',
                                    'Decision Reason',
                                ]);

                                // Data rows
                                foreach ($records as $application) {
                                    fputcsv($file, [
                                        $application->id,
                                        $application->user->name,
                                        $application->user->email,
                                        $application->cycle->title,
                                        ucfirst($application->track),
                                        ucwords(str_replace('_', ' ', $application->status)),
                                        $application->educationRecord?->institution_name ?? 'N/A',
                                        $application->educationRecord?->program ?? 'N/A',
                                        $application->score_academic ?? 0,
                                        $application->score_need ?? 0,
                                        $application->score_service ?? 0,
                                        $application->score_leadership ?? 0,
                                        $application->score_total ?? 0,
                                        $application->submission_at?->format('Y-m-d H:i:s') ?? 'Not submitted',
                                        $application->decision_reason ? ucwords(str_replace('_', ' ', $application->decision_reason)) : 'N/A',
                                    ]);
                                }

                                fclose($file);
                            };

                            return response()->stream($callback, 200, $headers);
                        }),

                    Tables\Actions\BulkAction::make('assignReviewer')
                        ->label('Assign to Me')
                        ->icon('heroicon-o-user-plus')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if ($record->status === 'submitted') {
                                    $record->update([
                                        'status' => 'under_review',
                                        'reviewer_id' => auth()->id(),
                                    ]);
                                }
                            }

                            Notification::make()
                                ->success()
                                ->title('Applications Assigned')
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->visible(fn () => auth()->user()->isReviewer()),

                    Tables\Actions\ExportBulkAction::make()
                        ->visible(fn () => auth()->user()->isSuperAdmin()),
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
            'index' => Pages\ListApplications::route('/'),
            'view' => Pages\ViewApplication::route('/{record}'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'cycle', 'reviewer', 'educationRecord', 'documents']);
    }
}
