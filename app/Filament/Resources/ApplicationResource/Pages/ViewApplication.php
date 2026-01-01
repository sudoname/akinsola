<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Storage;

class ViewApplication extends ViewRecord
{
    protected static string $resource = ApplicationResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Application Information')
                    ->schema([
                        Split::make([
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('user.name')
                                        ->label('Applicant Name')
                                        ->weight(FontWeight::Bold)
                                        ->size(TextEntry\TextEntrySize::Large),
                                    TextEntry::make('user.email')
                                        ->label('Email')
                                        ->icon('heroicon-m-envelope')
                                        ->copyable(),
                                    TextEntry::make('cycle.title')
                                        ->label('Scholarship Cycle')
                                        ->badge()
                                        ->color('primary'),
                                    TextEntry::make('track')
                                        ->label('Track')
                                        ->badge()
                                        ->formatStateUsing(fn (string $state): string => ucfirst($state))
                                        ->color(fn (string $state): string => match ($state) {
                                            'secondary' => 'info',
                                            'university' => 'success',
                                            'polytechnic' => 'warning',
                                            default => 'gray',
                                        }),
                                    TextEntry::make('status')
                                        ->label('Status')
                                        ->badge()
                                        ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state)))
                                        ->color(fn (string $state): string => match ($state) {
                                            'draft' => 'gray',
                                            'submitted' => 'info',
                                            'under_review' => 'warning',
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            'waitlisted' => 'secondary',
                                            default => 'gray',
                                        }),
                                    TextEntry::make('submission_at')
                                        ->label('Submitted At')
                                        ->dateTime('M d, Y h:i A')
                                        ->placeholder('Not submitted yet'),
                                ]),
                        ]),
                    ])->columns(2),

                Section::make('Personal Information')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('user.applicantProfile.phone')
                                    ->label('Phone Number')
                                    ->icon('heroicon-m-phone')
                                    ->placeholder('Not provided'),
                                TextEntry::make('user.applicantProfile.dob')
                                    ->label('Date of Birth')
                                    ->date('M d, Y')
                                    ->placeholder('Not provided'),
                                TextEntry::make('user.applicantProfile.state')
                                    ->label('State')
                                    ->placeholder('Not provided'),
                                TextEntry::make('user.applicantProfile.lga')
                                    ->label('LGA')
                                    ->placeholder('Not provided'),
                                TextEntry::make('user.applicantProfile.ward')
                                    ->label('Ward')
                                    ->placeholder('Not provided'),
                                TextEntry::make('user.applicantProfile.address')
                                    ->label('Address')
                                    ->columnSpan(3)
                                    ->placeholder('Not provided'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('user.applicantProfile.next_of_kin_name')
                                    ->label('Next of Kin Name')
                                    ->placeholder('Not provided'),
                                TextEntry::make('user.applicantProfile.next_of_kin_phone')
                                    ->label('Next of Kin Phone')
                                    ->placeholder('Not provided'),
                            ]),
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('user.applicantProfile.indigene_issuer')
                                    ->label('Indigene Certificate Issuer')
                                    ->placeholder('Not provided'),
                                TextEntry::make('user.applicantProfile.indigene_issue_date')
                                    ->label('Issue Date')
                                    ->date('M d, Y')
                                    ->placeholder('Not provided'),
                                TextEntry::make('user.applicantProfile.indigene_proof_url')
                                    ->label('Indigene Proof')
                                    ->formatStateUsing(fn ($state) => $state ? 'View Document' : 'Not uploaded')
                                    ->url(fn ($record) => $record->user?->applicantProfile?->indigene_proof_url
                                        ? Storage::url($record->user->applicantProfile->indigene_proof_url)
                                        : null, shouldOpenInNewTab: true)
                                    ->color(fn ($state) => $state ? 'success' : 'gray'),
                            ]),
                    ])->collapsible(),

                Section::make('Education Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('educationRecord.institution_name')
                                    ->label('Institution Name')
                                    ->placeholder('Not provided'),
                                TextEntry::make('educationRecord.level_or_class')
                                    ->label('Level of Study')
                                    ->placeholder('Not provided'),
                                TextEntry::make('educationRecord.program')
                                    ->label('Course/Program')
                                    ->placeholder('Not provided')
                                    ->visible(fn ($record) => in_array($record->track, ['university', 'polytechnic'])),
                                TextEntry::make('educationRecord.year_of_study')
                                    ->label('Year of Study')
                                    ->formatStateUsing(fn ($state) => $state ? "Year $state" : null)
                                    ->placeholder('Not provided')
                                    ->visible(fn ($record) => in_array($record->track, ['university', 'polytechnic'])),
                                TextEntry::make('educationRecord.cgpa')
                                    ->label('Current GPA/CGPA')
                                    ->placeholder('Not provided')
                                    ->visible(fn ($record) => in_array($record->track, ['university', 'polytechnic'])),
                                TextEntry::make('educationRecord.graduation_year')
                                    ->label('Expected Graduation')
                                    ->placeholder('Not provided')
                                    ->visible(fn ($record) => in_array($record->track, ['university', 'polytechnic'])),
                            ]),
                    ])->collapsible(),

                Section::make('Uploaded Documents')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('scholarship_essay')
                                    ->label('Scholarship Essay')
                                    ->formatStateUsing(function ($record) {
                                        $doc = $record->documents->where('type', 'scholarship_essay')->first();
                                        return $doc ? '📄 View Essay (PDF)' : 'Not uploaded';
                                    })
                                    ->url(function ($record) {
                                        $doc = $record->documents->where('type', 'scholarship_essay')->first();
                                        return $doc ? Storage::url($doc->url) : null;
                                    }, shouldOpenInNewTab: true)
                                    ->color(function ($record) {
                                        return $record->documents->where('type', 'scholarship_essay')->first() ? 'success' : 'gray';
                                    }),

                                TextEntry::make('academic_transcript')
                                    ->label('Academic Transcript')
                                    ->formatStateUsing(function ($record) {
                                        $doc = $record->documents->where('type', 'academic_transcript')->first();
                                        return $doc ? '📄 View Transcript' : 'Not uploaded';
                                    })
                                    ->url(function ($record) {
                                        $doc = $record->documents->where('type', 'academic_transcript')->first();
                                        return $doc ? Storage::url($doc->url) : null;
                                    }, shouldOpenInNewTab: true)
                                    ->color(function ($record) {
                                        return $record->documents->where('type', 'academic_transcript')->first() ? 'success' : 'gray';
                                    }),

                                TextEntry::make('admission_letter')
                                    ->label('Admission Letter')
                                    ->formatStateUsing(function ($record) {
                                        $doc = $record->documents->where('type', 'admission_letter')->first();
                                        return $doc ? '📄 View Letter' : 'Not uploaded';
                                    })
                                    ->url(function ($record) {
                                        $doc = $record->documents->where('type', 'admission_letter')->first();
                                        return $doc ? Storage::url($doc->url) : null;
                                    }, shouldOpenInNewTab: true)
                                    ->color(function ($record) {
                                        return $record->documents->where('type', 'admission_letter')->first() ? 'success' : 'gray';
                                    })
                                    ->visible(fn ($record) => in_array($record->track, ['university', 'polytechnic'])),

                                TextEntry::make('school_id_card')
                                    ->label('School ID Card')
                                    ->formatStateUsing(function ($record) {
                                        $doc = $record->documents->where('type', 'school_id_card')->first();
                                        return $doc ? '📄 View ID Card' : 'Not uploaded';
                                    })
                                    ->url(function ($record) {
                                        $doc = $record->documents->where('type', 'school_id_card')->first();
                                        return $doc ? Storage::url($doc->url) : null;
                                    }, shouldOpenInNewTab: true)
                                    ->color(function ($record) {
                                        return $record->documents->where('type', 'school_id_card')->first() ? 'success' : 'gray';
                                    }),

                                TextEntry::make('birth_certificate')
                                    ->label('Birth Certificate')
                                    ->formatStateUsing(function ($record) {
                                        $doc = $record->documents->where('type', 'birth_certificate')->first();
                                        return $doc ? '📄 View Certificate' : 'Not uploaded';
                                    })
                                    ->url(function ($record) {
                                        $doc = $record->documents->where('type', 'birth_certificate')->first();
                                        return $doc ? Storage::url($doc->url) : null;
                                    }, shouldOpenInNewTab: true)
                                    ->color(function ($record) {
                                        return $record->documents->where('type', 'birth_certificate')->first() ? 'success' : 'gray';
                                    }),

                                TextEntry::make('other_documents')
                                    ->label('Other Supporting Documents')
                                    ->formatStateUsing(function ($record) {
                                        $doc = $record->documents->where('type', 'other_supporting_documents')->first();
                                        return $doc ? '📄 View Documents' : 'Not uploaded';
                                    })
                                    ->url(function ($record) {
                                        $doc = $record->documents->where('type', 'other_supporting_documents')->first();
                                        return $doc ? Storage::url($doc->url) : null;
                                    }, shouldOpenInNewTab: true)
                                    ->color(function ($record) {
                                        return $record->documents->where('type', 'other_supporting_documents')->first() ? 'success' : 'gray';
                                    }),
                            ]),
                    ])->collapsible(),

                Section::make('Review & Scoring')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('score_academic')
                                    ->label('Academic Score')
                                    ->suffix(' / 100')
                                    ->placeholder('Not scored'),
                                TextEntry::make('score_need')
                                    ->label('Need Score')
                                    ->suffix(' / 100')
                                    ->placeholder('Not scored'),
                                TextEntry::make('score_service')
                                    ->label('Service Score')
                                    ->suffix(' / 100')
                                    ->placeholder('Not scored'),
                                TextEntry::make('score_leadership')
                                    ->label('Leadership Score')
                                    ->suffix(' / 100')
                                    ->placeholder('Not scored'),
                            ]),
                        TextEntry::make('score_total')
                            ->label('Total Score')
                            ->suffix(' / 100')
                            ->weight(FontWeight::Bold)
                            ->size(TextEntry\TextEntrySize::Large)
                            ->placeholder('Not scored'),
                        TextEntry::make('reviewer.name')
                            ->label('Reviewer')
                            ->placeholder('Not assigned'),
                        TextEntry::make('decision_note')
                            ->label('Internal Notes')
                            ->columnSpanFull()
                            ->placeholder('No notes'),
                    ])->collapsible()
                    ->visible(fn () => auth()->user()->isReviewer()),
            ]);
    }
}
