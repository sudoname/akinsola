<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\Setting;
use App\Notifications\ApplicationDecision;
use App\Notifications\PaymentPendingNotification;
use App\Notifications\PaymentVerifiedNotification;
use App\Notifications\PaymentSentNotification;
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

                Forms\Components\Section::make('Uploaded Documents')
                    ->description('View all documents uploaded by the applicant')
                    ->schema([
                        Forms\Components\Placeholder::make('documents_list')
                            ->label('Documents')
                            ->content(function ($record) {
                                if (!$record || !$record->documents || $record->documents->isEmpty()) {
                                    return new \Illuminate\Support\HtmlString('<p class="text-sm text-gray-500">No documents uploaded yet</p>');
                                }

                                $html = '<div class="space-y-3">';

                                foreach ($record->documents as $document) {
                                    $typeLabel = ucwords(str_replace('_', ' ', $document->type));
                                    $fileSize = $document->size_bytes ? round($document->size_bytes / 1024, 2) . ' KB' : 'Unknown size';
                                    $uploadedDate = $document->uploaded_at ? $document->uploaded_at->format('M d, Y H:i') : 'Unknown date';

                                    $html .= '<div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">';
                                    $html .= '<div class="flex items-center space-x-3">';
                                    $html .= '<svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
                                    $html .= '<div>';
                                    $html .= '<p class="text-sm font-semibold text-gray-900 dark:text-gray-100">' . $typeLabel . '</p>';
                                    $html .= '<p class="text-xs text-gray-500 dark:text-gray-400">' . $fileSize . ' • Uploaded ' . $uploadedDate . '</p>';
                                    $html .= '</div>';
                                    $html .= '</div>';
                                    $html .= '<a href="' . $document->getFullUrl() . '" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>Download</a>';
                                    $html .= '</div>';
                                }

                                $html .= '</div>';

                                return new \Illuminate\Support\HtmlString($html);
                            })
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(false),
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

                Forms\Components\Section::make('Bank Account Information')
                    ->description('Bank account details provided by the winner for scholarship payment')
                    ->schema([
                        Forms\Components\Placeholder::make('bank_status')
                            ->label('')
                            ->content(fn ($record) => $record && $record->bank_account_number
                                ? new \Illuminate\Support\HtmlString('<div class="text-green-600 dark:text-green-400 font-medium">✓ Bank account information provided</div>')
                                : new \Illuminate\Support\HtmlString('<div class="text-yellow-600 dark:text-yellow-400 font-medium">⚠ Waiting for winner to provide bank account details</div>')
                            )
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('bank_account_name')
                            ->label('Account Holder Name')
                            ->placeholder('Not provided yet')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('bank_name')
                            ->label('Bank Name')
                            ->placeholder('Not provided yet')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('bank_account_number')
                            ->label('Account Number')
                            ->placeholder('Not provided yet')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('bank_account_type')
                            ->label('Account Type')
                            ->placeholder('Not provided yet')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(fn ($state) => $state ? ucfirst($state) : null),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => $record && $record->status === 'approved')
                    ->collapsible(),
                Forms\Components\Section::make("Payment Tracking")
                    ->description("Track scholarship payment status and send notifications to the winner")
                    ->schema([
                        Forms\Components\Placeholder::make("payment_status_badge")
                            ->label("Current Status")
                            ->content(function ($record) {
                                if (!$record) return "N/A";

                                $statusConfig = [
                                    "not_applicable" => ["label" => "Not Applicable", "color" => "gray", "icon" => "⚪"],
                                    "pending" => ["label" => "Payment Processing", "color" => "blue", "icon" => "🔄"],
                                    "requirements_verified" => ["label" => "Requirements Verified", "color" => "indigo", "icon" => "✅"],
                                    "sent" => ["label" => "💰 PAYMENT SENT", "color" => "green", "icon" => "✅"],
                                    "received" => ["label" => "✅ PAYMENT RECEIVED", "color" => "emerald", "icon" => "✅"],
                                ];

                                $status = $record->payment_status ?? "not_applicable";
                                $config = $statusConfig[$status] ?? $statusConfig["not_applicable"];

                                $html = "<div style=\"display: inline-flex; align-items: center; padding: 12px 20px; background: linear-gradient(135deg, #{$config['color']}-500, #{$config['color']}-600); border-radius: 12px; font-size: 16px; font-weight: bold; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);\">";
                                $html .= "<span style=\"margin-right: 8px; font-size: 20px;\">{$config['icon']}</span>";
                                $html .= "<span>{$config['label']}</span>";
                                $html .= "</div>";

                                if ($status === "sent" && $record->payment_sent_at) {
                                    $html .= "<p style=\"margin-top: 8px; font-size: 14px; color: #059669;\">Sent on: " . $record->payment_sent_at->format("M d, Y \\a\\t H:i") . "</p>";
                                }

                                if ($status === "received" && $record->payment_received_at) {
                                    $html .= "<p style=\"margin-top: 8px; font-size: 14px; color: #059669;\">Confirmed on: " . $record->payment_received_at->format("M d, Y \\a\\t H:i") . "</p>";
                                }

                                return new \Illuminate\Support\HtmlString($html);
                            })
                            ->columnSpanFull(),

                        Forms\Components\Select::make("payment_status")
                            ->label("Payment Status (Technical)")
                            ->options([
                                "not_applicable" => "Not Applicable",
                                "pending" => "Payment Processing",
                                "requirements_verified" => "Requirements Verified",
                                "sent" => "Payment Sent",
                                "received" => "Received by Winner",
                            ])
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(false),

                        Forms\Components\DateTimePicker::make("payment_pending_at")
                            ->label("Payment Pending Since")
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn ($record) => $record && $record->payment_pending_at),

                        Forms\Components\DateTimePicker::make("payment_verified_at")
                            ->label("Requirements Verified At")
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn ($record) => $record && $record->payment_verified_at),

                        Forms\Components\DateTimePicker::make("payment_sent_at")
                            ->label("Payment Sent At")
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn ($record) => $record && $record->payment_sent_at),

                        Forms\Components\DateTimePicker::make("payment_received_at")
                            ->label("Payment Received At")
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn ($record) => $record && $record->payment_received_at),

                        Forms\Components\Textarea::make("payment_note")
                            ->label("Payment Notes (Internal)")
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText("Add notes about payment processing, issues, etc."),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => $record && $record->status === "approved")
                    ->collapsible(),
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

                Tables\Columns\TextColumn::make('calculated_score')
                    ->label('Total Score')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query; // Can't sort calculated field
                    })
                    ->toggleable()
                    ->state(function (Application $record): string {
                        $weights = Setting::getScoringWeights();
                        $score = $record->calculateTotalScore($weights);
                        return number_format($score, 2);
                    })
                    ->tooltip(function (Application $record): string {
                        return "Academic: {$record->score_academic} | Need: {$record->score_need} | Service: {$record->score_service} | Leadership: {$record->score_leadership}";
                    }),

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

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Payment')
                    ->colors([
                        'secondary' => 'not_applicable',
                        'info' => 'pending',
                        'primary' => 'requirements_verified',
                        'success' => 'sent',
                        'success' => 'received',
                    ])
                    ->icons([
                        'heroicon-o-minus-circle' => 'not_applicable',
                        'heroicon-o-arrow-path' => 'pending',
                        'heroicon-o-check-badge' => 'requirements_verified',
                        'heroicon-o-currency-dollar' => 'sent',
                        'heroicon-o-check-circle' => 'received',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'not_applicable' => 'N/A',
                        'pending' => 'Processing',
                        'requirements_verified' => 'Verified',
                        'sent' => '💰 PAID',
                        'received' => '✅ Confirmed',
                        default => $state,
                    })
                    ->sortable()
                    ->toggleable()
                    ->visible(fn () => auth()->user()->isApprover()),
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


                Tables\Actions\Action::make("startPayment")
                    ->label("Start Payment")
                    ->icon("heroicon-o-currency-dollar")
                    ->color("info")
                    ->visible(fn (Application $record) => 
                        $record->status === "approved" && 
                        $record->payment_status === "not_applicable" && 
                        $record->bank_account_number &&
                        auth()->user()->isApprover()
                    )
                    ->requiresConfirmation()
                    ->modalHeading("Start Payment Processing")
                    ->modalDescription("This will notify the winner that payment processing has started.")
                    ->action(function (Application $record) {
                        $record->update([
                            "payment_status" => "pending",
                            "payment_pending_at" => now(),
                        ]);

                        $record->user->notify(new PaymentPendingNotification($record));

                        AuditLog::logAction(
                            "application.payment_pending",
                            auth()->id(),
                            Application::class,
                            $record->id
                        );

                        Notification::make()
                            ->success()
                            ->title("Payment Processing Started")
                            ->body("Winner has been notified.")
                            ->send();
                    }),

                Tables\Actions\Action::make("verifyPayment")
                    ->label("Verify Requirements")
                    ->icon("heroicon-o-check-badge")
                    ->color("primary")
                    ->visible(fn (Application $record) => 
                        $record->payment_status === "pending" && auth()->user()->isApprover()
                    )
                    ->requiresConfirmation()
                    ->modalHeading("Verify Payment Requirements")
                    ->modalDescription("Confirm that all requirements have been verified and payment is ready to send.")
                    ->action(function (Application $record) {
                        $record->update([
                            "payment_status" => "requirements_verified",
                            "payment_verified_at" => now(),
                        ]);

                        $record->user->notify(new PaymentVerifiedNotification($record));

                        AuditLog::logAction(
                            "application.payment_verified",
                            auth()->id(),
                            Application::class,
                            $record->id
                        );

                        Notification::make()
                            ->success()
                            ->title("Requirements Verified")
                            ->body("Winner has been notified.")
                            ->send();
                    }),

                Tables\Actions\Action::make("markSent")
                    ->label("Mark as Sent")
                    ->icon("heroicon-o-paper-airplane")
                    ->color("success")
                    ->visible(fn (Application $record) => 
                        $record->payment_status === "requirements_verified" && auth()->user()->isApprover()
                    )
                    ->requiresConfirmation()
                    ->modalHeading("Mark Payment as Sent")
                    ->modalDescription("Confirm that payment has been sent to the winner's bank account.")
                    ->action(function (Application $record) {
                        $record->update([
                            "payment_status" => "sent",
                            "payment_sent_at" => now(),
                        ]);

                        $record->user->notify(new PaymentSentNotification($record));

                        AuditLog::logAction(
                            "application.payment_sent",
                            auth()->id(),
                            Application::class,
                            $record->id
                        );

                        Notification::make()
                            ->success()
                            ->title("Payment Marked as Sent")
                            ->body("Winner has been notified.")
                            ->send();
                    }),


                Tables\Actions\Action::make('viewDocuments')
                    ->label('Documents')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->modalHeading(fn (Application $record) => 'Documents - ' . $record->user->name)
                    ->modalDescription(fn (Application $record) => 'Applicant: ' . $record->user->email)
                    ->modalContent(function (Application $record) {
                        $documents = $record->documents;

                        if ($documents->isEmpty()) {
                            return new \Illuminate\Support\HtmlString('<div class="text-center py-8"><p class="text-gray-500">No documents uploaded yet</p></div>');
                        }

                        $html = '<div class="space-y-3">';
                        foreach ($documents as $document) {
                            $typeLabel = ucwords(str_replace('_', ' ', $document->type));
                            $fileSize = $document->size_bytes ? round($document->size_bytes / 1024, 2) . ' KB' : 'Unknown';
                            $uploadedDate = $document->uploaded_at ? $document->uploaded_at->format('M d, Y H:i') : 'Unknown';

                            $html .= '<div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">';
                            $html .= '<div>';
                            $html .= '<p class="font-semibold text-gray-900 dark:text-gray-100">' . htmlspecialchars($typeLabel) . '</p>';
                            $html .= '<p class="text-xs text-gray-500 dark:text-gray-400">' . htmlspecialchars($fileSize) . ' • ' . htmlspecialchars($uploadedDate) . '</p>';
                            $html .= '</div>';
                            $html .= '<div class="flex gap-2">';
                            $html .= '<a href="' . htmlspecialchars($document->getFullUrl()) . '" target="_blank" style="color: white !important; background-color: #2563eb; padding: 0.375rem 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; display: inline-flex; align-items: center; text-decoration: none;" onmouseover="this.style.backgroundColor=\'#1d4ed8\'" onmouseout="this.style.backgroundColor=\'#2563eb\'">View</a>';
                            $html .= '<a href="' . htmlspecialchars($document->getFullUrl()) . '" download style="color: white !important; background-color: #4b5563; padding: 0.375rem 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; display: inline-flex; align-items: center; text-decoration: none; margin-left: 0.5rem;" onmouseover="this.style.backgroundColor=\'#374151\'" onmouseout="this.style.backgroundColor=\'#4b5563\'">Download</a>';
                            $html .= '</div>';
                            $html .= '</div>';
                        }
                        $html .= '</div>';
                        $html .= '<div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg"><p class="text-sm text-blue-700 dark:text-blue-300"><strong>Total:</strong> ' . $documents->count() . ' document(s)</p></div>';

                        return new \Illuminate\Support\HtmlString($html);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalWidth('2xl')
                    ->slideOver(),
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
